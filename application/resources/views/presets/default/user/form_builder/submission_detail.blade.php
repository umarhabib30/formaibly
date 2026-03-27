@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="d-flex flex-wrap justify-content-sm-end gap-2 mb-3">
                    <a href="{{ route('user.form.index') }}" class="btn btn--sm btn--base">@lang('Back')</a>
                </div>
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="answer__data">
                                    <h4>@lang('Answer Data')</h4>
                                    <ul>
                                        <li>
                                            @lang('Total Questions'):
                                            <span>{{ $submissionFormBuilderAnswerDetail->total_question < 10 ? '0' . $submissionFormBuilderAnswerDetail->total_question : $submissionFormBuilderAnswerDetail->total_question }}</span>
                                        </li>
                                        <li>
                                            @lang('Total Answer'):
                                            <span>{{ $submissionFormBuilderAnswerDetail->total_answer < 10 ? '0' . $submissionFormBuilderAnswerDetail->total_answer : $submissionFormBuilderAnswerDetail->total_answer }}</span>
                                        </li>
                                        <li>
                                            @lang('Empty Answer'):
                                            <span>{{ $submissionFormBuilderAnswerDetail->empty_answer < 10 ? '0' . $submissionFormBuilderAnswerDetail->empty_answer : $submissionFormBuilderAnswerDetail->empty_answer }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="survey-preview">
                                    @foreach ($submissionFormBuilderAnswerDetail->answer as $index => $field)
                                        <div class="answer__item">
                                            <h5>{{ $index + 1 < 10 ? '0' . ($index + 1) : $index + 1 }}.
                                                {{ $field['label'] }}</h5>
                                            <ul>
                                                <li>
                                                    <span>@lang('Type'):</span>
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
                                                </li>
                                                <li>
                                                    <span>@lang('Answer'):</span>
                                                    {{ is_array($field['answer']) ? implode(', ', $field['answer']) : $field['answer'] }}
                                                </li>
                                            </ul>

                                            @if (isset($field['options']) && count($field['options']))
                                                <div class="options mt-2 border-top pt-2">
                                                    @foreach ($field['options'] as $option)
                                                        <p class="mb-1">{{ $option }}</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
