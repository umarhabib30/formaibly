@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12">
        <div class="card bg--white radius--base p-16 br--solid">
            <div class="d-flex justify-content-between  align-items-center mb-4">
                <h5 class="mb-0">@lang('KYC Form for User')</h5>
                <button type="button" class="btn btn--primary float-end form-generate-btn"><i class="fa-solid fa-plus"></i>@lang('Add New')</button>
            </div>

            <div class="card-body">
                <form action="" method="post">
                    @csrf
                    <div class="row addedField">
                        @if($form)
                        @foreach($form->form_data as $formData)
                        @php
                        $jsonData = json_encode([
                        'type'=>$formData->type,
                        'is_required'=>$formData->is_required,
                        'label'=>$formData->name,
                        'extensions'=>explode(',',$formData->extensions) ?? 'null',
                        'options'=>$formData->options,
                        'old_id'=>'',
                        ]);
                        @endphp
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card br--solid" id="{{ $loop->index }}">

                                <input type="hidden" name="form_generator[is_required][]"
                                    value="{{ $formData->is_required }}">
                                <input type="hidden" name="form_generator[extensions][]"
                                    value="{{ $formData->extensions }}">
                                <input type="hidden" name="form_generator[options][]"
                                    value="{{ implode(',',$formData->options) }}">

                                <div class="card-body">
                                    <div class="button--group d-flex align-items-center justify-content-end gap-2">
                                        <button type="button"
                                            class="edit--btn editFormData"
                                            data-form_item="{{ $jsonData }}"
                                            data-update_id="{{ $loop->index }}"><i class="fa-solid fa-pen-to-square"></i></button>

                                        <button type="button"
                                            class="btn btn--danger removeFormData"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>


                                    <div class="form-group">
                                        <label>@lang('Label')</label>
                                        <input type="text" name="form_generator[form_label][]" class="form-control"
                                            value="{{ $formData->name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Type')</label>
                                        <input type="text" name="form_generator[form_type][]" class="form-control"
                                            value="{{ $formData->type }}" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                   <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn--primary">@lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-form-generator></x-form-generator>
@endsection

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
    formGenerator.totalField = {{ $form ? count((array) $form -> form_data) : 0 }}
</script>

<script src="{{ asset('assets/common/js/form_actions.js') }}"></script>
@endpush
