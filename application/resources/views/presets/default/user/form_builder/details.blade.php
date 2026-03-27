@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="answer__data d-flex justify-content-between align-items-center">
                                    <h4>@lang('Form Builder Data')</h4>
                                    <a href="{{ route('user.form.index') }}" class="btn btn--base mb-4">@lang('Back')</a>
                                </div>
                                <div class="survey-preview">
                                    @foreach ($formBuilder->form_data['form'] as $index => $field)
                                        <div class="answer__item">
                                                <h5>{{ $index + 1 < 10 ? '0' . ($index + 1) : $index + 1 }}.
                                                {{ $field['label'] }}</h5>
                                                <ul>
                                                    <li>
                                                        <span>@lang('Type'):</span>
                                                        @if ($field['tag'] === 'input')
                                                            @lang('Text Input')
                                                        @elseif($field['tag'] === 'textarea')
                                                            @lang('Written Textarea')
                                                        @elseif($field['tag'] === 'select')
                                                            @lang('Select Input')
                                                        @elseif($field['tag'] === 'radio')
                                                            @lang('Radio Input')
                                                        @elseif($field['tag'] === 'checkbox')
                                                            @lang('Checkbox Input')
                                                        @else
                                                            {{ ucfirst($field['tag']) }}
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <span>@lang('Required'):</span>
                                                        {{ $field['required'] ? 'Yes' : 'No' }}
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
