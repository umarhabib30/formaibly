@extends('admin.layouts.app')

@section('panel')

    <div class="row gy-4 pb-4 mb-2">
        <div class="col-sm-6 col-xxl-3 col-xl-3">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.withdraw.log', ['status' => 'all']) }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-dollar-sign"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Withdrawals Requests')</span>
                    <h5 class="number">{{ $logCount }}</h5>
                </div>
                <span class="arrow--btn position-absolute"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </div>

        <div class="col-sm-6 col-xxl-3 col-xl-3">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.withdraw.log', ['status' => 'approved']) }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-check-to-slot"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Approved Withdrawals')</span>
                    <h5 class="number">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h5>
                </div>
                <span class="arrow--btn position-absolute"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </div>


        <div class="col-sm-6 col-xxl-3 col-xl-3">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.withdraw.log', ['status' => 'pending']) }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-hourglass-half"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Pending Withdrawals')</span>
                    <h5 class="number">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h5>
                </div>
                <span class="arrow--btn position-absolute"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </div>

        <div class="col-sm-6 col-xxl-3 col-xl-3">
            <a class="dashboard-widget--card position-relative" href="{{ route('admin.withdraw.log', ['status' => 'rejected']) }}">
                <div class="dashboard-widget__icon">
                    <i class="dashboard-card-icon fa-solid fa-ban"></i>
                </div>
                <div class="dashboard-widget__content">
                    <span class="title">@lang('Rejected Withdrawals')</span>
                    <h5 class="number">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h5>
                </div>
                <span class="arrow--btn position-absolute"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </div>


    </div>


    <div class="row gy-4 justify-content-between mb-3 pb-3">
        <div class="col-lg-5">

                <form>
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <div class="search-input--wrap position-relative w-100">
                                <input type="search" name="search" class="form-control" placeholder="@lang('Search by username')"
                                    value="{{ request()->search }}">
                                <button class="search--btn position-absolute" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="search-input--wrap position-relative w-100">
                                <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Date from - to')" autocomplete="off" value="{{ request()->date }}">
                                <button class="search--btn position-absolute" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

        </div>

        <div class="col-xl-2 col-lg-6">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <select id="table__data__filter" name="status" class="form-control form-select bg--transparent outline">
                    <option value="all">@lang('All')</option>
                    <option value="approved">@lang('Approved')</option>
                    <option value="pending">@lang('Pending')</option>
                    <option value="rejected">@lang('Rejected')</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Gateway')||@lang('Trx')</th>
                                    <th>@lang('Amount')||@lang('Charge')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Requested at')</th>
                                    <th>@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @include('admin.components.tables.withdraw_data')
                            </tbody>
                        </table>
                    </div>
                </div>


                <div id="pagination-wrapper"  class="pagination__wrapper py-4 {{ $items->hasPages() ? '' : 'd-none' }}">
                    @if ($items->hasPages())
                    {{ paginateLinks($items) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/common/css/datepicker.min.css') }}">
@endpush


@push('script-lib')
    <script src="{{ asset('assets/common/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/datepicker.en.js') }}"></script>
@endpush


@push('style')
    <style>
        .nav-tabs {
            border: 0;
        }

        .nav-tabs li a {
            border-radius: 4px !important;
        }
    </style>
@endpush


@push('script')
    <script>
        (function ($) {
            'use strict';

            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }

            let baseUrl = `{{ route('admin.withdraw.log', ':status') }}`;

            $('#table__data__filter').on('change', function () {
                let status = $(this).val();
                let url = baseUrl.replace(':status', status);


                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        search: $('#search-box').val()
                    },
                    beforeSend: function () {
                        $('#items_table__body').html('<tr><td colspan="6">@lang("Loading")...</td></tr>');
                    },
                    success: function (response) {
                        $('#items_table__body').html(response.html);
                        $('.card-footer').html(response.pagination);

                        if ($.trim(response.pagination) === '') {
                            $('#pagination-wrapper').addClass('d-none');
                        } else {
                            $('#pagination-wrapper').removeClass('d-none');
                        }
                    },
                    error: function () {
                        alert('Failed to load filtered tickets.');
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
