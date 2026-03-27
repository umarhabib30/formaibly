<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Frontend;
use App\Models\Language;
use App\Constants\Status;
use App\Models\Subscriber;
use App\Models\FormBuilder;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\UserNotification;
use App\Models\AdminNotification;
use App\Models\FormBuilderAnswer;
use Illuminate\Support\Facades\Cookie;

class SiteController extends Controller
{
    public function index()
    {
        if (isset($_GET['reference'])) {
            session()->put('reference', $_GET['reference']);
        }
        $pageTitle = 'Home';
        $sections = Page::where('slug', '/')->first();
        return view('Template::home', compact('pageTitle', 'sections'));
    }

    public function pages($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view('Template::pages', compact('pageTitle', 'sections'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug, $id)
    {
        $policy = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view('Template::policy', compact('policy', 'pageTitle'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blog(Request $request)
    {
        $pageTitle = 'Our Blog Posts';
        $blogs = Frontend::where('data_keys', 'blog.element')
            ->when($request->search, function ($query) use ($request) {
                $search = strtolower($request->search);
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(data_values, '$.title'))) LIKE ?", ["%$search%"]);
            })
            ->orderBy('id', 'desc')
            ->paginate(getPaginate(9));
        $sections = Page::where('slug', 'blog')->first();
        return view('Template::blog', compact('pageTitle', 'blogs', 'sections'));
    }


    public function blogDetails($slug, $id)
    {
        $latests   = Frontend::where('data_keys', 'blog.element')->orderBy('id', 'desc')->limit(6)->get();
        $blog      = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = 'Blog Details';
        return view('Template::blog_details', compact('blog', 'pageTitle', 'latests'));
    }

    public function about()
    {
        $pageTitle = "About Us";
        $sections  = Page::where('slug', 'about')->first();
        return view('Template::about', compact('pageTitle', 'sections'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $sections  = Page::where('slug', 'contact')->first();
        return view('Template::contact', compact('pageTitle', 'sections'));
    }



    public function cookieAccept()
    {
        $general = gs();
        Cookie::queue('gdpr_cookie', $general->site_name, 43200);
        return back();
    }

    public function cookiePolicy()
    {
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        $general = gs();
        if ($general->maintenance_mode) {
            $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
            return view('Template::maintenance', compact('pageTitle', 'maintenance'));
        }
        return to_route('home');
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 255, 255, 255);
        $bgFill    = imagecolorallocate($image, 28, 35, 47);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }


    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:subscribers',
        ]);
        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();
        $notify[] = ['success', 'You have successfully subscribed to the Newsletter'];
        return back()->withNotify($notify);
    }

    public function formDetails($type,$id)
    {
    
        $pageTitle   = "Form Details";
        $formBuilder = FormBuilder::where('status', Status::FORM_BUILDER_ENABLE)->where('id', $id)->first();
        if(!$formBuilder){
            abort(404);
        }
        return view('UserTemplate::form_builder.form_details', compact('pageTitle', 'formBuilder'));
    }

    public function answerSubmit(Request $request)
    {
        $isAuthor = FormBuilder::where('id', $request->form_builder_id)->first();
        if ($isAuthor->user_id == auth()->id()) {
            $notify[] = ['error', 'You are owner this form builder, so you can not submit answer.'];
            return back()->withNotify($notify);
        }

        $isExists = FormBuilderAnswer::where('form_builder_id', $request->form_builder_id)
            ->where('user_id', auth()->id())
            ->whereNotIn('status', [Status::FORM_BUILDER_ANSWER_REJECTED])
            ->exists();

        if ($isExists) {
            $notify[] = ['error', 'Form Builder answer has already been submitted.'];
            return back()->withNotify($notify);
        }

        if ($isExists && $isExists->form_builder_people <= $isExists->form_builder_people_answer) {
            $notify[] = ['error', 'The form builder limit has been reached. No more responses can be submitted.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'form_builder_id' => 'required|integer|exists:form_builders,id',
        ]);

        $formArray = json_decode($request->form_json, true);
        $evaluateFormAnswers = evaluateFormAnswers($formArray);

        $formAnswer                  = new FormBuilderAnswer();
        $formAnswer->user_id         = auth()->check() ? auth()->id() : 0;
        $formAnswer->form_builder_id = $request->form_builder_id;
        $formAnswer->answer          = $evaluateFormAnswers['results'];
        $formAnswer->total_question  = $evaluateFormAnswers['summary']['total_questions'];
        $formAnswer->total_answer    = $evaluateFormAnswers['summary']['answered'];
        $formAnswer->empty_answer    = $evaluateFormAnswers['summary']['empty_answers'];
        $formAnswer->average_quality = $evaluateFormAnswers['summary']['average_score'];
        $formAnswer->status          = Status::FORM_BUILDER_ANSWER_PENDING;
        $formAnswer->save();


        if (auth()->check()) {
            $userNotification            = new UserNotification();
            $userNotification->user_id   = auth()->id();
            $userNotification->title     = 'Form Builder Answer Submitted';
            $userNotification->click_url = urlPath('user.form.submission');
            $userNotification->save();

            $userNotification            = new UserNotification();
            $userNotification->user_id   = $isAuthor->user_id;
            $userNotification->title     = 'New Form Builder Answer Received';
            $userNotification->click_url = urlPath('user.form.answer.user.list', ['id' => $request->form_builder_id]);
            $userNotification->save();
        }


        $notify[] = ['success', 'Form Builder answer has been submitted successfully.'];
        return back()->withNotify($notify);
    }
}
