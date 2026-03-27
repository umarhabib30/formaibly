@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card br--solid radius--base bg--white mb-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-8 col-xl-9 order-1 order-lg-0">
                            <h5 class="mb-20">@lang('Answer Data')</h5>
                            <div class="survey-preview">
                                @foreach ($submissionFormBuilderAnswerDetail->answer as $index => $field)
                                    <div class="question mb-4">
                                        <strong>{{ $index + 1 < 10 ? '0' . ($index + 1) : $index + 1 }}.
                                            {{ $field['label'] }}</strong><br>
                                        <small><strong>@lang('Type'):</strong>
                                            @if ($field['type'] === 'input')
                                                @lang('Input')
                                            @elseif($field['type'] === 'textarea')
                                                @lang('Textarea')
                                            @elseif($field['type'] === 'select')
                                                @lang('Single')
                                            @elseif($field['type'] === 'radio')
                                                @lang('Radio')
                                            @elseif($field['type'] === 'checkbox')
                                                @lang('Checkbox')
                                            @else
                                                {{ ucfirst($field['type']) }}
                                            @endif
                                        </small>
                                        <br>

                                        <small><strong>@lang('Answer'):</strong>
                                            {{ is_array($field['answer']) ? implode(', ', $field['answer']) : $field['answer'] }}
                                        </small>

                                        @if (isset($field['options']) && count($field['options']))
                                            <div class="options mt-2">
                                                @foreach ($field['options'] as $option)
                                                    <div>{{ $option }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-3 text-end order-0 order-lg-1">
                            <ul class="submission-data__list">
                                <li class="mb-20">
                                    @lang('Total Questions'):
                                    {{ $submissionFormBuilderAnswerDetail->total_question < 10 ? '0' . $submissionFormBuilderAnswerDetail->total_question : $submissionFormBuilderAnswerDetail->total_question }}

                                </li>
                                <li class="mb-20">
                                    @lang('Total Answer'):
                                    {{ $submissionFormBuilderAnswerDetail->total_answer < 10 ? '0' . $submissionFormBuilderAnswerDetail->total_answer : $submissionFormBuilderAnswerDetail->total_answer }}
                                </li>
                                <li class="mb-20">
                                    @lang('Empty Answer'):
                                    {{ $submissionFormBuilderAnswerDetail->empty_answer < 10 ? '0' . $submissionFormBuilderAnswerDetail->empty_answer : $submissionFormBuilderAnswerDetail->empty_answer }}
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.form.builder.submission.list', $submissionFormBuilderAnswerDetail->form_builder_id) }}"
        class="btn btn-sm btn--primary"><i class="fa-solid fa-arrow-left"></i>
         @lang('Back')
    </a>
@endpush


