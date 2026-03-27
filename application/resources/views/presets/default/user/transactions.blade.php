@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <form action="">
                        <div class="row gy-3">
                            <!-- Transaction Number -->
                            <div class="col-md-3">
                                <label class="form-label">@lang('Transaction Number')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form--control"
                                    placeholder="@lang('Search by transactions')">
                            </div>

                            <!-- Type -->
                            <div class="col-md-3">
                                <label class="form-label">@lang('Type')</label>
                                <select name="type" class="form--control select-2">
                                    <option value="">@lang('All')</option>
                                    <option value="+" @selected(request()->type == '+')>@lang('Plus')</option>
                                    <option value="-" @selected(request()->type == '-')>@lang('Minus')</option>
                                </select>

                            </div>

                            <!-- Remark -->
                            <div class="col-md-3">
                                <label class="form-label">@lang('Remark')</label>
                                <select class="form--control select-2" name="remark">
                                    <option value="">@lang('Any')</option>
                                    @foreach ($remarks as $remark)
                                        <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                            {{ __(keyToTitle($remark->remark)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Button -->
                            <div class="col-md-3 mt-auto">
                                <button class="btn btn--base btn--lg custom-filter-btn w-100">
                                    <i class="fa-solid fa-filter"></i> @lang('Filter')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="dashboard__table">
                <div class="card">
                    <h5>@lang('Transactions')</h5>
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
@endsection
