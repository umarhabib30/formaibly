<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\FormBuilder;
use Illuminate\Http\Request;
use App\Models\FormBuilderAnswer;
use App\Http\Controllers\Controller;

class FormBuilderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = FormBuilder::searchable(['title'])->latest();
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

        $formBuilders = $query->with('user')->paginate(getPaginate());
        $pageTitle = ucfirst($status) . ' Form Builders';

        return view('Admin::form_builder.index', compact('formBuilders', 'pageTitle'));
    }

    public function details($id)
    {
        $formBuilder = FormBuilder::where('id', $id)->first();
        if (!$formBuilder) {
            $notify[] = ['error', 'Form Builder Not Found'];
            return back()->withNotify($notify);
        }
        $pageTitle = 'Form Builder Details';
        return view('Admin::form_builder.details', compact('pageTitle', 'formBuilder'));
    }

    public function submissionList($id)
    {
        $pageTitle = 'Form Submission Details';
        $formBuilderAnswers = FormBuilderAnswer::with('form_builder', 'user')->where('form_builder_id', $id)
            ->latest()->paginate(getPaginate());

        return view('Admin::form_builder.submission_list', compact('pageTitle', 'formBuilderAnswers'));
    }

    public function submissionDetail($id)
    {
        $pageTitle = 'Form Submission Details';
        $submissionFormBuilderAnswerDetail = FormBuilderAnswer::with('form_builder', 'user')->where('id', $id)->first();
        return view('Admin::form_builder.submission_detail', compact('pageTitle', 'submissionFormBuilderAnswerDetail'));
    }

}
