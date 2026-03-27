<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Constants\Status;
use App\Models\Subscriber;
use App\Models\FormBuilder;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Models\UserNotification;
use App\Models\FormBuilderAnswer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Gateway\PaypalSdk\PayPalHttp\Serializer\Form;

class FormBuilderController extends Controller
{
    public function allForm(Request $request)
    {
        $pageTitle = "All Form";
        $forms = FormBuilder::with(['user'])
            ->searchable(['title'])
            ->where('status', Status::FORM_BUILDER_ENABLE)
            ->latest()->paginate(getPaginate());

        return view('UserTemplate::form_builder.all_form', compact('pageTitle', 'forms'));
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = FormBuilder::with('user')->searchable(['title'])->where('user_id', auth()->id())->latest();
        switch ($status) {
            case 'disable':
                $query->where('status', Status::FORM_BUILDER_DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::FORM_BUILDER_ENABLE);
                break;
            case 'all':
                $query->whereIn('status', [Status::FORM_BUILDER_ENABLE, Status::FORM_BUILDER_DISABLE]);
                break;
            default:
                break;
        }

        $forms = $query->paginate(getPaginate());
        $pageTitle = ucfirst($status) . ' Forms';
        return view('UserTemplate::form_builder.index', compact('forms', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'New Form Create';
        $planSubscription = Subscription::with('plan')->where('user_id', auth()->id())
            ->where('expired_at', '>=', now())
            ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
            ->latest()
            ->first();
        return view('UserTemplate::form_builder.create', compact('pageTitle', 'planSubscription'));
    }


    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');
        $apiKey = gs()->open_ai_key;
        $elementCount = $request->input('element_count', 2);

        if ((gs()->credit_cost_per_question_prompt * $elementCount) > auth()->user()->credit) {

            return response()->json([
                'status' => 'error',
                'message' => 'You do not have enough credits.',
                'data' => null
            ]);
        }

        $planSubscription = Subscription::where('user_id', auth()->id())
            ->where('expired_at', '>=', now())
            ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
            ->latest()
            ->first();
        if (!$planSubscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have an active subscription plan.',
            ], 422);
        }

        $response = $this->generateFormBuilderJson($apiKey, $elementCount, $prompt);

        if ($response['status'] == 'success' && count($response['data']['form']) > 0) {

            $user = auth()->user();
            $user->credit -= (gs()->credit_cost_per_question_prompt * count($response['data']['form']));
            $user->save();
        }

        return response()->json($response);
    }

    protected function generateFormBuilderJson($apiKey, $elementCount = 2, $prompt, $model = 'gpt-4o-mini', $temperature = 0.4)
    {
        $client = new Client();
        $messages = [
            [
                "role" => "system",
                "content" => "Generate a form with {$elementCount} elements. Use question types: input, textarea, select, checkbox, radio. Always respond with valid JSON only with valid JSON in the following exact schema. Do NOT change key names or structure. The schema is: {
                    \"type\": \"object\",
                    \"properties\": {
                        \"title\": { \"type\": \"string\" },
                        \"form\": {
                            \"type\": \"array\",
                            \"items\": {
                                \"type\": \"object\",
                                \"properties\": {
                                    \"id\": { \"type\": \"string\", \"pattern\": \"^el_[a-z0-9]{7}$\" },
                                    \"tag\": { \"enum\": [\"input\", \"textarea\", \"select\", \"checkbox\", \"radio\"] },
                                    \"type\": { \"enum\": [\"text\", \"email\", \"textarea\", \"select\", \"radio\", \"checkbox\"] },
                                    \"label\": { \"type\": \"string\" },
                                    \"placeholder\": { \"type\": \"string\" },
                                    \"required\": { \"type\": \"boolean\" },
                                    \"options\": { \"type\": \"array\", \"items\": { \"type\": \"string\" }, \"minItems\": 2 }
                                },
                                \"required\": [\"id\", \"tag\", \"type\", \"label\", \"placeholder\", \"required\"],
                                \"allOf\": [
                                    { \"if\": { \"properties\": { \"type\": { \"enum\": [\"select\", \"radio\", \"checkbox\"] } } },
                                    \"then\": { \"required\": [\"options\"] } }
                                ]
                            },
                            \"uniqueItems\": true
                        }
                    },
                    \"required\": [\"title\", \"form\"]
                }"
            ],
            ["role" => "user", "content" => $prompt]
        ];

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => $temperature,
                    'max_tokens' => 1200,
                ],
            ]);

            $result = json_decode($response->getBody(), true);



            if (!isset($result['choices'][0]['message']['content'])) {
                return [
                    'status' => 'error',
                    'message' => 'Empty response from OpenAI.',
                    'data' => null
                ];
            }

            // Remove ```json ``` fencing if present
            $content = trim($result['choices'][0]['message']['content']);
            $content = preg_replace('/^```json|```$/m', '', $content);
            $json = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid JSON format received.',
                    'data' => $content
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Form Builder generated successfully.',
                'data' => $json
            ];
        } catch (\Exception $e) {
            Log::error("OpenAI API Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred while generating the form builder.',
                'data' => null
            ];
        }
    }

    public function store(Request $request)
    {

        $data = $request->except('_token');
        $validator = Validator::make($data, [
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'])],
            'submission_limit' => 'required|numeric|min:1',
            'title' => 'required|string|max:255',
            'form_json' => [
                'required',
                'json',
                function ($attribute, $value, $fail) {
                    $data = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return $fail('Invalid JSON format in form_json.');
                    }

                    if (empty($data['template'])) {
                        return $fail('The form template field is required.');
                    }

                    if (empty($data['form']) || !is_array($data['form'])) {
                        return $fail('The form must be a non-empty array.');
                    }

                    foreach ($data['form'] as $index => $item) {
                        if (empty($item['id']) || empty($item['label']) || empty($item['tag'])) {
                            return $fail("Each form element must have id, label, and tag (error at index {$index}).");
                        }
                        if (in_array($item['tag'], ['select', 'radio', 'checkbox']) && empty($item['options'])) {
                            return $fail("The form element '{$item['label']}' must include options.");
                        }
                    }
                },
            ],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $planSubscription = Subscription::with('plan')
            ->where('expired_at', '>=', now())
            ->where('user_id', auth()->id())
            ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
            ->latest()
            ->first();

        if (!$planSubscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have an active subscription plan.',
            ], 403);
        }

        if ($planSubscription->expired_at <= now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You plan has expired. Please renew your subscription to create new forms.',
            ], 403);
        }

        if ($planSubscription->plan->period == 'yearly') {
            $startDate = Carbon::parse($planSubscription->created_at);
            $expiryDate = $startDate->addYear();
            $now = Carbon::now();
            if ($now->greaterThan($expiryDate)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You plan has expired. Please renew your subscription to create new forms.',
                ], 403);
            }
        }

        $formData = json_decode($request->form_json, true);
        $elementsCount = count($formData['form'] ?? []);

        if ($planSubscription->plan->input_limit < $elementsCount) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have exceeded your form builder limit for your current plan.',
            ], 403);
        }

        $formBuilder = new FormBuilder();
        $formBuilder->title = $request->title;
        $formBuilder->user_id = auth()->id();
        $formBuilder->submission_limit = $request->submission_limit;
        $formBuilder->question_limit = $elementsCount;
        $formBuilder->form_data = json_decode($request->form_json);
        $formBuilder->status = Status::FORM_BUILDER_ENABLE;
        if ($request->hasFile('image')) {
            try {
                $formBuilder->image = fileUploader($request->image, getFilePath('form'), getFileSize('form'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $formBuilder->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Form data saved successfully.'
        ]);
    }

    public function view($id)
    {
        $formBuilder = FormBuilder::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$formBuilder) {
            $notify[] = ['error', 'Form Not Found'];
            return back()->withNotify($notify);
        }
        $pageTitle = 'Form Builder Details';
        return view('UserTemplate::form_builder.details', compact('pageTitle', 'formBuilder'));
    }


    public function status($id)
    {
        $formBuilder = FormBuilder::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$formBuilder) {
            $notify[] = ['error', 'Form Not Found'];
            return back()->withNotify($notify);
        }

        $formBuilder->status = $formBuilder->status == 1 ? 0 : 1;
        $formBuilder->save();
        $notify[] = ['success', 'Form Status has been updated successfully'];
        return back()->withNotify($notify);
    }


    public function answerList(Request $request, $id)
    {

        $pageTitle = 'Answer List';
        $status = $request->get('status', 'all');
        $formBuilderAnswers = FormBuilderAnswer::with('form_builder', 'user')->whereHas('form_builder', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('form_builder_id', $id)
            ->latest()->paginate(getPaginate());
        return view('UserTemplate::form_builder.answer_user_list', compact('pageTitle', 'formBuilderAnswers'));
    }

    public function answerDetails($id)
    {
        $pageTitle = 'Form Builder Answer Details';
        $formBuilderAnswerDetail = FormBuilderAnswer::with('form_builder', 'user')->where('id', $id)->first();
        return view('UserTemplate::form_builder.answer_detail', compact('pageTitle', 'formBuilderAnswerDetail'));
    }


    public function answerStatus($status, $id)
    {
        $formBuilderAnswer = FormBuilderAnswer::with('form_builder', 'user')->where('id', $id)->first();
        if (!$formBuilderAnswer) {
            $notify[] = ['error', 'Form Builder answer is not valid'];
            return back()->withNotify($notify);
        }

        if ($status == Status::FORM_BUILDER_ANSWER_APPROVED) {
            $formBuilderAnswer->status = Status::FORM_BUILDER_ANSWER_APPROVED;
            $formBuilderAnswer->save();

            $formBuilderAnswer->form_builder->people_answer += 1;
            $formBuilderAnswer->form_builder->save();
            $notify[] = ['success', 'Form Builder answer has been approved.'];
        } else {
            $formBuilderAnswer->status = Status::FORM_BUILDER_ANSWER_REJECTED;
            $formBuilderAnswer->save();
            $notify[] = ['success', 'Form Builder answer has been rejected.'];
        }

        return back()->withNotify($notify);
    }


    public function submission()
    {
        $formSubmissions = FormBuilderAnswer::with('form_builder')->searchable(['form_builder:title'])->where('user_id', auth()->id())->paginate(getPaginate());
        $pageTitle = 'Form Builder Submissions';
        return view('UserTemplate::form_builder.submission', compact('pageTitle', 'formSubmissions'));
    }

    public function submissionDetails($id)
    {
        $pageTitle = 'Submission Answer Details';
        $submissionFormBuilderAnswerDetail = FormBuilderAnswer::with('form_builder', 'user')->where('id', $id)->first();
        return view('UserTemplate::form_builder.submission_detail', compact('pageTitle', 'submissionFormBuilderAnswerDetail'));
    }
}
