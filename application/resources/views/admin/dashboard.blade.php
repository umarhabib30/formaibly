@extends('admin.layouts.app')
@section('panel')

    @adminHas('dashboard')
        @if (isset($general->system_info) && !empty(json_decode($general->system_info)->message))
            @if (json_decode($general->system_info)->message)
                <div class="row">
                    @foreach (json_decode($general->system_info)->message as $msg)
                        <div class="col-md-12">
                            <div class="alert border border--primary" role="alert">
                                <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                                <p class="alert__message">@php echo $msg; @endphp</p>
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="row gy-4">
            <div class="col-xl-12">
                <div class="row gy-4">
                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <a class="dashboard-widget--card position-relative" href="{{ route('admin.form.builder.index') }}">
                            <div class="dashboard-widget__icon">
                                <i class="dashboard-card-icon fa-regular fa-newspaper"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="title">@lang('Total Forms')</span>
                                <h5 class="number">{{ $widget['total_forms'] }}</h5>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <a class="dashboard-widget--card position-relative" href="{{ route('admin.form.builder.index') }}">
                            <div class="dashboard-widget__icon">
                                <i class="dashboard-card-icon fa-solid fa-paste"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="title">@lang('Total Submissions')</span>
                                <h5 class="number">{{ $widget['total_form_submissions'] }}</h5>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <a class="dashboard-widget--card position-relative" href="{{ route('admin.plan.index') }}">
                            <div class="dashboard-widget__icon">
                                <i class="dashboard-card-icon fa-solid fa-calendar"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="title">@lang('Total Plans')</span>
                                <h5 class="number">{{ $widget['total_plans'] }}</h5>
                            </div>
                        </a>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <a class="dashboard-widget--card position-relative" href="{{ route('admin.plan.subscriptions') }}">
                            <div class="dashboard-widget__icon">
                                <i class="dashboard-card-icon fa-solid fa-ticket"></i>
                            </div>
                            <div class="dashboard-widget__content">
                                <span class="title">@lang('Total Subscriptions')</span>
                                <h5 class="number">{{ $widget['total_subscriptions'] }}</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-xl-6">
                <div class="card bg--white br--solid">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <h5 class="card-title mb-0">@lang('Monthly Payment & Plan Subscription Report')</h5>
                        </div>
                        <div id="account-chart" data-plan_subscriptions="{{ base64_encode(json_encode($planSubscriptionChart)) }}"
                            data-deposits="{{ base64_encode(json_encode($depositsChart)) }}"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card bg--white br--solid">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <h5 class="card-title mb-0">@lang('Recent Transactions')</h5>
                        </div>
                        <div class="table-responsive table-responsive--sm">
                            <table class="table align-items-center style--three table--light">
                                <thead>
                                    <tr>
                                        <th>@lang('Customer')</th>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $key=>$trx)
                                        <tr>
                                            <td class="user--td">
                                                <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                                                    <div
                                                        class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                                                        <div class="user--thumb">
                                                            @if (!empty($trx->user->image))
                                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $trx?->user?->image) }}"
                                                                    alt="@lang('Image')">
                                                            @else
                                                                <img src="{{ getImage('assets/images/general/avatar.png') }}"
                                                                    alt="@lang('Image')">
                                                            @endif
                                                        </div>
                                                        <div class="user--content">
                                                            <a class="text-start"
                                                                href="{{ route('admin.report.transaction') . '?search=' . $trx->user?->username }}">
                                                                {{ $trx->user?->fullname }}
                                                            </a>
                                                            <p class="text-start">{{ $trx?->user?->email }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{ $trx->trx }}
                                            </td>

                                            <td>
                                                <h6>{{ showDateTime($trx->created_at, 'd M Y') }}</h6>
                                            </td>
                                            <td>
                                                <h6>{{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}</h6>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-md-8">
                <div class="card bg--white br--solid">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <h5 class="card-title mb-0">@lang('Top Customers')</h5>
                        </div>

                        <div class="table-responsive table-responsive--sm">
                            <table class="table align-items-center style--three table--light">
                                <thead>
                                    <tr>
                                        <th>@lang('Customer')</th>
                                        <th>@lang('Subject')</th>
                                        <th>@lang('Priority')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $key=>$ticket)
                                        <tr>
                                            <td>
                                                <div
                                                    class="d-flex flex-wrap flex-md-nowrap justify-content-end justify-content-lg-start align-items-center gap-2">
                                                    <div class="user--thumb">
                                                        @if (!empty($ticket->user->image))
                                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $ticket?->user?->image) }}"
                                                                alt="@lang('Image')">
                                                        @else
                                                            <img src="{{ getImage('assets/images/general/avatar.png') }}"
                                                                alt="@lang('Image')">
                                                        @endif
                                                    </div>
                                                    <div class="user--content">
                                                        @if ($ticket->user_id)
                                                            <a class="text-start"
                                                                href="{{ route('admin.users.detail', $ticket->user->id) }}">
                                                                {{ $ticket->user->fullname }}
                                                            </a>
                                                            <p class="text-start">{{ $ticket?->user?->email }}</p>
                                                        @else
                                                            <p class="text-start">{{ $ticket?->name }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.ticket.view', $ticket->id) }}" class="fw--500">
                                                    @lang('Ticket')#{{ $ticket->ticket }} -
                                                    {{ strLimit($ticket->subject, 15) }}
                                                </a>
                                            </td>

                                            <td>
                                                @php echo $ticket->priorityBadge; @endphp
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-4">
                <div class="card bg--white br--solid">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                            <h5 class="card-title mb-0">@lang('Transactions')</h5>
                        </div>

                        <div class="d-flex justify-content-center align-items-center gap-5 py-4">
                            <div class="order-info--item d-flex gap-4 flex-column justify-content-center align-items-center">
                                <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                    <div
                                        class="number--wrap one d-flex justify-content-center align-items-center flex-shrink-0">
                                        <h2 class="m-0 text--white">{{ getAmount($widget['plus_transactions']) }}</h2>
                                    </div>
                                    <p class="fs-6">@lang('Plus Transactions')</p>
                                </div>
                                <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                    <div
                                        class="number--wrap two d-flex justify-content-center align-items-center flex-shrink-0">
                                        <h2 class="m-0 text--white">{{ getAmount($widget['minus_transactions']) }}</h2>
                                    </div>
                                    <p class="fs-6">@lang('Minus Transactions')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-12">
                <p>@lang('You have no permission to view the page content!')</p>
            </div>
        </div>
    @endadminHas

@endsection

@adminHas('dashboard')
    @push('script-lib')
        <script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
    @endpush

    @push('script')
        <script>
            (function($) {
                'use strict';
                const $chartData = $('#account-chart');
                const planSubscriptionsEncoded = $chartData.data('plan_subscriptions');
                const depositsEncoded = $chartData.data('deposits');
                const planSubscriptionsChart = JSON.parse(atob(planSubscriptionsEncoded));
                const depositsChart = JSON.parse(atob(depositsEncoded));

                var options = {
                    chart: {
                        type: 'bar',
                        stacked: false,
                        height: '333px'
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 4,
                        borderColor: '#e0e0e0',
                        position: 'back'
                    },
                    stroke: {
                        width: 0
                    },
                    dataLabels: {
                        enabled: false
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%',
                            horizontal: false
                        }
                    },
                    colors: ['#FFA500', '#00A86B'],
                    series: [{
                            name: 'Plan Subscriptions',
                            data: planSubscriptionsChart.values
                        },
                        {
                            name: 'Deposits',
                            data: depositsChart.values
                        }
                    ],
                    fill: {
                        opacity: 1
                    },
                    labels: depositsChart.labels,
                    xaxis: {
                        categories: depositsChart.labels,
                        title: {
                            text: 'Months',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        labels: {
                            show: true
                        },
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true
                        }
                    },
                    yaxis: {
                        min: 0,
                        title: {
                            text: 'Amount',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                return typeof y !== "undefined" ? "{{ __($general->cur_sym) }}" + y.toFixed(0) : y;
                            }
                        }
                    },
                    legend: {
                        labels: {
                            useSeriesColors: true
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#account-chart"), options);
                chart.render();
            })(jQuery)
        </script>
    @endpush
@endadminHas
