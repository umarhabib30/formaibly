@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-9">
                <div class="profile-items">
                    <div class="text-end">
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2 mb-3">
                            @if ($formBuilderAnswerDetail->status == Status::FORM_BUILDER_ANSWER_PENDING)
                                <a href="javascript:void(0)" class="btn btn--sm btn--base confirmationBtn"
                                    data-question="@lang('Are you sure to approved this answer?')"
                                    data-action="{{ route('user.form.answer.status', [1, $formBuilderAnswerDetail->id]) }}">
                                    @lang('Approved')
                                </a>
                                <a href="javascript:void(0)" class="btn btn--sm btn--danger confirmationBtn"
                                    data-question="@lang('Are you sure to reject this answer?')"
                                    data-action="{{ route('user.form.answer.status', [3, $formBuilderAnswerDetail->id]) }}">
                                    @lang('Rejected')
                                </a>
                            @endif
                            <a href="{{ route('user.form.answer.user.list', $formBuilderAnswerDetail->form_builder_id) }}"
                                class="btn btn--sm btn--base">@lang('Back')
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-12">
                            <div class="profile__wrap card p-4">
                                <div class="answer__data">
                                    <h4>@lang('Answer Data')</h4>
                                    <ul>
                                        <li>
                                            @lang('Total Questions'):
                                            <span>{{ $formBuilderAnswerDetail->total_question < 10 ? '0' . $formBuilderAnswerDetail->total_question : $formBuilderAnswerDetail->total_question }}</span>
                                        </li>
                                        <li>
                                            @lang('Total Answer'):
                                            <span>
                                                {{ $formBuilderAnswerDetail->total_answer < 10 ? '0' . $formBuilderAnswerDetail->total_answer : $formBuilderAnswerDetail->total_answer }}
                                            </span>
                                        </li>
                                        <li>
                                        @lang('Empty Answer'):
                                        <span>
                                            {{ $formBuilderAnswerDetail->empty_answer < 10 ? '0' . $formBuilderAnswerDetail->empty_answer : $formBuilderAnswerDetail->empty_answer }}
                                        </span>
                                        </li>
                                        <li>
                                            @lang('Average Quality'): <span>{{ $formBuilderAnswerDetail->average_quality }}%</span>
                                        </li>
                                    </ul>
                                </div>
                                @foreach ($formBuilderAnswerDetail->answer ?? [] as $index => $q)
                                    <div class="answer__item">
                                        <h5>@lang('Question') {{ $index + 1 < 10 ? '0' . ($index + 1) : $index + 1 }}.
                                            {{ $q['label'] }}</h4>
                                        <ul>
                                            <li><span>@lang('Type'):</span> {{ ucwords(str_replace('_', ' ', $q['type'])) }}</li>
                                            <li><span>@lang('Quality'):</span> {{ $q['score'] }}%</li>
                                            <li>
                                                <span>@lang('Answer'):</span> 
                                            @if (is_array($q['answer']))
                                                {{ implode(', ', $q['answer']) }}
                                            @else
                                                {{ $q['answer'] ?? '--' }}
                                            @endif
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>
@endsection


