@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-12 col-md-12 mb-30">
            <div class="card p-16 bg--white radius--base br--solid overflow-hidden">
                <h5 class="mb-20">@lang('Form Builder Data')</h5>
                @foreach ($formBuilder->form_data['form'] as $index => $field)
                    <div class="mb-3">
                        <div class="question mb-4">
                            <strong>{{ $index + 1 < 10 ? '0' . ($index + 1) : $index + 1 }}.
                                {{ $field['label'] }}</strong><br>
                            <small><strong>@lang('Id'):</strong> {{ $field['id'] }}</small><br>
                            <small><strong>@lang('Type'):</strong>

                                @if ($field['tag'] === 'input')
                                    @lang('Written Input')
                                @elseif($field['tag'] === 'textarea')
                                    @lang('Written Textarea')
                                @elseif($field['tag'] === 'select')
                                    @lang('Mcq Single')
                                @elseif($field['tag'] === 'radio')
                                    @lang(' Mcq Single')
                                @elseif($field['tag'] === 'checkbox')
                                    @lang('Mcq Multiple')
                                @else
                                    {{ ucfirst($field['tag']) }}
                                @endif
                            </small>
                            <br>
                            <small><strong>@lang('Required'):</strong>
                                {{ $field['required'] ? 'Yes' : 'No' }}</small><br>

                            @if (isset($field['options']) && count($field['options']))
                                <div class="options mt-2">
                                    @foreach ($field['options'] as $option)
                                        <div>{{ $option }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
