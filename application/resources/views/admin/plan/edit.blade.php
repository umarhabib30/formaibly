@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card bg--white radius--base br--solid p-16">
                <form action="{{ route('admin.plan.update', $plan->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form--label">@lang('Name')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $plan->name) }}" placeholder="@lang('Plan name')" required>
                                </div>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form--label">@lang('Price')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="price" min="0" step="any"
                                        value="{{ old('price', $plan->price) }}" placeholder="@lang('Price')">
                                </div>
                            </div>
                        </div>

                        <!-- Period -->
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="period">@lang('Period')</label>
                                <select name="period" id="period" class="form-control" required>
                                    <option value="monthly"
                                        {{ old('period', $plan->period) == 'monthly' ? 'selected' : '' }}>
                                        @lang('Monthly')
                                    </option>
                                    <option value="yearly" {{ old('period', $plan->period) == 'yearly' ? 'selected' : '' }}>
                                        @lang('Yearly')
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Form Input Limit -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Form Input Limit')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="input_limit" min="1"
                                        step="1" value="{{ old('input_limit', $plan->input_limit) }}"
                                        placeholder="@lang('Form Input Limit')" required>
                                </div>
                            </div>
                        </div>

                        <!-- Credit -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form--label">@lang('Credit')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="credit" min="0" step="1"
                                        value="{{ old('credit', $plan->credit) }}" placeholder="@lang('Credit')">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mb-3">
                        <label class="form--label required">@lang('Features')</label>
                        <div id="features">
                            @php
                                $oldFeatures = old('features', $plan->features ?? ['']);
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
                        <label for="short_description" class="form-label required">@lang('Short Description')</label>
                        <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $plan->short_description) }}" class="form-control"
                            placeholder="@lang('Enter Short description')" required>
                    </div>

                    <!-- Is Popular -->
                    <div class="form-group col-12 d-flex mb-3">
                        <label class="fw--500 mb-0">@lang('Is Popular')</label>
                        <label class="switch m-0 ms-2">
                            <input type="checkbox" class="toggle-switch" name="is_popular"
                                {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="text-end">
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn--primary" href="{{ route('admin.plan.index') }}">@lang('Back') <i
            class="fa-solid fa-angles-right"></i></a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
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
            });
        })(jQuery);
    </script>
@endpush
