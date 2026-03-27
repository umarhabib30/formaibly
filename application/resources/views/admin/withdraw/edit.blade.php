@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card br--solid radius--base bg--white mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.withdraw.method.update', $method->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row gy-4 mb-2">
                            <div class="col-lg-4 col-sm-12">
                                <h5 class="mb-4">@lang('Gateway Image')</h5>
                                <div class="logo-upload--box">
                                    <x-image-uploader name="image" :imagePath="getImage(
                                        getFilePath('withdrawMethod') . '/' . $method->image,
                                        getFileSize('withdrawMethod'),
                                    )" :size="getFileSize('withdrawMethod')" :isImage="true"
                                        class="w-100" id="uploadLogo3" :required="false" />
                                </div>
                            </div>

                            <div class="col-lg-8 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" value="{{ $method->name }}"
                                        required />
                                </div>

                                <div class="form-group">
                                    <label>@lang('Currency')</label>
                                    <div class="input-group">
                                        <input type="text" name="currency" class="form-control border-radius-5"
                                            value="{{ $method->currency }}" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Dollar Rate')</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg--primary text--white">1
                                            {{ __($general->cur_text) }} = </span>
                                        <input type="number" class="form-control" name="rate"
                                            value="{{ getAmount($method->rate) }}" min="0" step="any" required />
                                        <span class="currency_symbol input-group-text bg--primary text--white"></span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="payment-method-item">
                            <div class="row my-4">
                                <div class="col-md-8 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card border mb-2">
                                                <h5 class="card-header">@lang('Withdrawal Limit')</h5>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>@lang('Min')</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="min_limit"
                                                                value="{{ getAmount($method->min_limit) }}" required />
                                                            <span class="input-group-text bg--primary text--white">
                                                                {{ __($general->cur_text) }} </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('Max')</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="max_limit"
                                                                value="{{ getAmount($method->max_limit) }}" required />
                                                            <span class="input-group-text bg--primary text--white">
                                                                {{ __($general->cur_text) }} </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card border">
                                                <h5 class="card-header">@lang('Transaction Charge')</h5>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>@lang('Fixed')</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="fixed_charge"
                                                                value="{{ getAmount($method->fixed_charge) }}" required />
                                                            <span class="input-group-text bg--primary text--white">
                                                                {{ __($general->cur_text) }} </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('Percentage')</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="percent_charge"
                                                                value="{{ getAmount($method->percent_charge) }}" required>
                                                            <span class="input-group-text bg--primary text--white">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="card border">
                                        <h5 class="card-header">@lang('Special Instructions') </h5>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <textarea rows="3" class="form-control trumEdit border-radius-5" name="instruction">{{ $method->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-method-body">
                                <div class="row gy-3">
                                    <div class="col-lg-12">

                                        <div class="d-flex justify-content-between mb-4">
                                            <h5>@lang('User Input Fields')</h5>
                                            <button type="button" class="btn btn-sm btn--primary float-end form-generate-btn">
                                                <i class="fa-solid fa-plus"></i> @lang('Add New')</button>
                                        </div>

                                        <div class="row gy-4 addedField">
                                            @if ($form)
                                                @foreach ($form->form_data as $formData)
                                                    <div class="col-md-4">
                                                        <div class="card radius--base br--solid mb-3" id="{{ $loop->index }}">
                                                            <input type="hidden" name="form_generator[is_required][]"
                                                                value="{{ $formData->is_required }}">
                                                            <input type="hidden" name="form_generator[extensions][]"
                                                                value="{{ $formData->extensions }}">
                                                            <input type="hidden" name="form_generator[options][]"
                                                                value="{{ implode(',', $formData->options) }}">
                                                            @php
                                                                $jsonData = json_encode([
                                                                    'type' => $formData->type,
                                                                    'is_required' => $formData->is_required,
                                                                    'label' => $formData->name,
                                                                    'extensions' =>
                                                                        explode(',', $formData->extensions) ?? 'null',
                                                                    'options' => $formData->options,
                                                                    'old_id' => '',
                                                                ]);
                                                            @endphp
                                                            <div class="card-body">
                                                                <div class="button--group d-flex align-items-center justify-content-end gap-2">
                                                                    <button type="button"
                                                                        class="edit--btn editFormData"
                                                                        data-form_item="{{ $jsonData }}"
                                                                        data-update_id="{{ $loop->index }}">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn--danger removeFormData">
                                                                        <i class="fa-regular fa-trash-can"></i> </button>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>@lang('Label')</label>
                                                                    <input type="text"
                                                                        name="form_generator[form_label][]"
                                                                        class="form-control"
                                                                        value="{{ $formData->name }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>@lang('Type')</label>
                                                                    <input type="text"
                                                                        name="form_generator[form_type][]"
                                                                        class="form-control"
                                                                        value="{{ $formData->type }}" readonly>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn--primary mt-2">@lang('Save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-form-generator></x-form-generator>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.withdraw.method.index') }}" class="btn btn-sm btn--primary">
        <i class="fa-solid fa-arrow-left"></i> @lang('Back')
    </a>
@endpush


@push('style')
    <style>
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 239px !important;
            height: 239px;
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/common/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/common/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict"
        var formGenerator = new FormGenerator();
        formGenerator.totalField = {{ $form ? count((array) $form->form_data) : 0 }}
    </script>

    <script src="{{ asset('assets/common/js/form_actions.js') }}"></script>
@endpush




@push('script')
    <script src="{{ asset('assets/common/js/ckeditor.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            document.querySelectorAll('.trumEdit').forEach(element => {
                ClassicEditor
                    .create(element)
                    .then(editor => {
                        window.editor = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });

            $('input[name=currency]').on('input', function() {
                $('.currency_symbol').text($(this).val());
            });
            $('.currency_symbol').text($('input[name=currency]').val());

            $('.addUserData').on('click', function() {
                var html = `
                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-4">
                                    <input name="field_name[]" class="form-control" type="text" required>
                                </div>
                                <div class="col-md-3 mt-md-0 mt-2">
                                    <select name="type[]" class="form-control form-select" required>
                                        <option value="text" > @lang('Input Text') </option>
                                        <option value="textarea" > @lang('Textarea') </option>
                                        <option value="file"> @lang('File') </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-md-0 mt-2">
                                    <select name="validation[]"
                                            class="form-control form-select" required>
                                        <option value="required"> @lang('Required') </option>
                                        <option value="nullable">  @lang('Optional') </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-md-0 mt-2 text-end">
                                    <span class="input-group-btn">
                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;

                $('.addedField').append(html);
            });


            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.user-data').remove();
            });

            @if (old('currency'))
                $('input[name=currency]').trigger('input');
            @endif
        })(jQuery);
    </script>
@endpush
