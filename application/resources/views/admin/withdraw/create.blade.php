@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form id="withdrawForm" action="{{ route('admin.withdraw.method.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row gy-4 mb-2">
                    <div class="col-lg-4 col-sm-12">
                        <div class="card border my-2">
                            <h5 class="card-header">@lang('Gateway Image')</h5>
                            <div class="card-body">
                                <x-image-uploader name="image" :imagePath="getImage(getFilePath('withdrawMethod') . '/',getFileSize('withdrawMethod'))" :size="getFileSize('withdrawMethod')" :isImage="false" class="w-100" id="uploadImage" :required="false" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-sm-12">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required />
                        </div>

                        <div class="form-group">
                            <label>@lang('Currency')</label>
                            <div class="input-group">
                                <input type="text" name="currency" class="form-control border-radius-5" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Dollar Rate')</label>
                            <div class="input-group">
                                <span class="input-group-text bg--primary text--white">1 {{ __($general->cur_text) }} = </span>
                                <input type="text" class="form-control" name="rate" required />
                                <span class="currency_symbol input-group-text bg--primary text--white"></span>
                            </div>
                        </div>
                    </div>


                </div>

                    <div class="payment-method-item mb-4">
                        <div class="row my-4">
                            <div class="col-lg-8 col-sm-12">
                                <div class="row gy-4 mb-3">
                                    <div class="col-lg-6">
                                        <div class="card bg--white br--solid p-16 radius--base">
                                            <div class="card-header px-0 mb-3 pt-0">
                                                <h5>@lang('Withdrawal Limit')</h5>
                                            </div>

                                                <div class="form-group">
                                                    <label>@lang('Min')</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="min_limit" required />
                                                        <span class="input-group-text bg--primary text--white"> {{ __($general->cur_text) }} </span>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label>@lang('Max')</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="max_limit" required />
                                                        <span class="input-group-text bg--primary text--white"> {{__($general->cur_text) }} </span>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card bg--white br--solid p-16 radius--base">
                                            <div class="card-header px-0 mb-3 pt-0">
                                                <h5>@lang('Transaction Charge')</h5>
                                            </div>

                                                <div class="form-group">
                                                    <label>@lang('Fixed')</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="fixed_charge" required />
                                                        <span class="input-group-text bg--primary text--white"> {{ __($general->cur_text) }} </span>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label>@lang('Percentage')</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="percent_charge" required>
                                                        <span class="input-group-text bg--primary text--white">%</span>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="card bg--white br--solid p-16 radius--base">
                                    <div class="card-header px-0 mb-3 pt-0">
                                        <h5>@lang('Special Instructions') </h5>
                                    </div>

                                    <div class="form-group mb-0">
                                        <textarea rows="3" class="form-control trumEdit border-radius-5" name="instruction"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bg--white br--solid p-16 radius--base">
                                        <div class="card-header d-flex justify-content-between border-0 pb-5">
                                            <h5>@lang('User Input Fields')</h5>
                                            <button type="button" class="btn btn-sm bg--primary float-end form-generate-btn text--white"> <i class="fa-solid fa-plus"></i> @lang('Add New')</button>
                                        </div>

                                        <div class="row gy-4 addedField">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="text-end">
                    <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                </div>
            </form>
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
    <link rel="stylesheet" href="{{asset('assets/common/css/select2.min.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset('assets/common/js/select2.min.js')}}"></script>
@endpush


@push('script')
    <script>
        "use strict"
        var formGenerator = new FormGenerator();
    </script>

    <script src="{{ asset('assets/common/js/form_actions.js') }}"></script>
@endpush


@push('script')
    <script src="{{ asset('assets/common/js/ckeditor.js') }}"></script>

    <script>
        (function ($) {
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

            $('input[name=currency]').on('input', function () {
                $('.currency_symbol').text($(this).val());
            });
            $('.addUserData').on('click', function () {
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
                                            <button class="btn btn--danger btn-lg removeBtn 5w-100" type="button">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                $('.addedField').append(html);
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });
            @if (old('currency'))
                $('input[name=currency]').trigger('input');
            @endif
        })(jQuery);

    </script>
@endpush
