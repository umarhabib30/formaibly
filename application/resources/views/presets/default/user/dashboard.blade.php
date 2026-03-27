@php
    $user = auth()->user();
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row gy-4">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('user.deposit') }}" class="dashboard-widgets">
                    <div class="dashboard-widgets__content">
                        <p class="dashboard-widgets__title">@lang('Current Balance')</p>
                        <h3 class="dashboard-widgets__amount">{{ $general->cur_sym . showAmount($user->balance) }}</h3>
                    </div>
                    <div class="dashboard-widgets__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'dashboard-shape.png') }}" alt="@lang('dashboard-shape')">
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('user.credit.purchase') }}" class="dashboard-widgets">
                    <div class="dashboard-widgets__content">
                        <p class="dashboard-widgets__title">@lang('Total Credit')</p>
                        <h3 class="dashboard-widgets__amount">{{ $user->credit }}</h3>
                    </div>
                    <div class="dashboard-widgets__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'dashboard-shape.png') }}" alt="@lang('dashboard-shape')">
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('user.form.index') }}" class="dashboard-widgets">
                    <div class="dashboard-widgets__content">
                        <p class="dashboard-widgets__title">@lang('Total Forms')</p>
                        <h3 class="dashboard-widgets__amount">{{ $totalForms }}</h3>
                    </div>
                    <div class="dashboard-widgets__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'dashboard-shape.png') }}" alt="@lang('dashboard-shape')">
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{url('/').'/pricing'}}" class="dashboard-widgets">
                    <div class="dashboard-widgets__content">
                        <p class="dashboard-widgets__title">@lang('Active Plan')</p>
                        @if ($subscription)
                            <h6 class="dashboard-widgets__amount">{{ $subscription->plan?->name }}</h6>
                        @else
                            <h6>@lang('You currently have no active plan')</h6>
                        @endif
                    </div>
                    <div class="dashboard-widgets__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'dashboard-shape.png') }}" alt="@lang('dashboard-shape')">
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table__topbar">
                        <h5>@lang('Latest Transactions')</h5>
                        <div class="table__topbar__right">
                            <div class="search__box">
                                <input type="text" class="form--control" placeholder="Search">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="dashboard__table">
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th class="text-center">@lang('Transacted')</th>
                                    <th class="text-center">@lang('Amount')</th>
                                    <th class="text-center">@lang('Post Credit')</th>
                                    <th class="text-center">@lang('Post Balance')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td>{{ $trx->trx }}</td>

                                        <td class="text-center">
                                            {{ showDateTime($trx->created_at) }}
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="@if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                                {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                                {{ $general->cur_text }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            {{ $trx->post_credit }}
                                        </td>

                                        <td class="text-center">
                                            {{ showAmount($trx->post_balance) }}
                                            {{ __($general->cur_text) }}
                                        </td>

                                        <td>{{ __($trx->details) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">
                                            {{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
