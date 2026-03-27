@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card bg--white radius--base br--solid p-16">
                <form action="{{ route('admin.plan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form--label">@lang('Name')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="@lang('Plan name')"
                                        name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form--label">@lang('Price')</label>
                                <div class="input-group">
                                    <input type="number" min="0" step="any" class="form-control" name="price"
                                        placeholder="@lang('Price')" value="{{ old('price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="period">@lang('Period')</label>
                                <select name="period" id="period" class="form-control" required>
                                    <option value="monthly"
                                        {{ old('period', $plan->period ?? '') == 'monthly' ? 'selected' : '' }}>
                                        @lang('Monthly')</option>
                                    <option value="yearly"
                                        {{ old('period', $plan->period ?? '') == 'yearly' ? 'selected' : '' }}>
                                        @lang('Yearly')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Form Input Limit')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" min="1" step="1"
                                        name="input_limit" value="1" placeholder="@lang('Question Limit')"
                                        value="{{ old('input_limit') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Credit')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" min="0" step="1" name="credit"
                                        placeholder="@lang('Credit')" value="{{ old('credit') ?? 0 }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form--label required">@lang('Features')</label>
                        <div id="features">
                            @php
                                $oldFeatures = old('features', isset($plan) ? $plan->features : ['']);
                            @endphp

                            @foreach ($oldFeatures as $feature)
                                <div class="input-group mb-3">
                                    <input type="text" name="features[]" class="form-control" value="{{ $feature }}"
                                        placeholder="@lang('Enter plan feature')" required>
                                    <button class="btn btn-outline-danger remove-item" type="button">x</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline--primary" id="add-plan">
                            <i class="fa-solid fa-plus"></i>
                            @lang('Add Item')
                        </button>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="short_description" class="form-label required">@lang('Short Description')</label>
                            <input type="text" name="short_description" id="short_description"
                                value="{{ old('short_description') ?? '' }}" class="form-control"
                                placeholder="@lang('Enter Short description')" required>
                        </div>
                    </div>

                    <div class="form-group col-12 d-flex mb-0">
                        <label class="fw--500 mb-0">@lang('Is Popular')</label>
                        <label class="switch m-0">
                            <input type="checkbox" class="toggle-switch" name="is_popular">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="text-end">
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn--primary" href="{{ route('admin.plan.index') }}"><i class="fa-solid fa-angles-left"></i>
        @lang('Back') </a>
@endpush



@push('script')
    <script>
        (function($) {
            "use strict";

            function initDynamicFields(wrapperSelector, addBtnSelector, inputName) {
                const $wrapper = $(wrapperSelector);
                const $addBtn = $(addBtnSelector);
                $addBtn.on('click', function() {
                    const $field = $('<div class="input-group mb-3">' +
                        '<input type="text" name="' + inputName +
                        '[]" class="form-control" placeholder="@lang('Enter plan feature')" required>' +
                        '<button class="btn btn-outline--danger remove-item" type="button">x</button>' +
                        '</div>');
                    $wrapper.append($field);
                });

                $wrapper.on('click', '.remove-item', function() {
                    $(this).parent().remove();
                });
            }

            // Initialize dynamic fields
            initDynamicFields('#features', '#add-plan', 'features');


        })(jQuery);
    </script>
@endpush
