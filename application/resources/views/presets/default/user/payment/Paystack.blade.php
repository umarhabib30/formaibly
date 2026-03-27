@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-md-12">
                <div class="profile__wrap card p-4">
                    <h5 class="mb-3">@lang('Paystack')</h5>
                    <form action="{{ route('ipn.' . $deposit->gateway->alias) }}" method="POST" class="text-center">
                        @csrf
                        <div class="row g-4">
                            <div class="col-sm-12">
                                <ul class="list-group text-center">
                                    <li class="list-group-item d-flex justify-content-between">
                                        @lang('You have to pay '):
                                        <strong>{{ showAmount($deposit->final_amo) }}
                                            {{ __($deposit->method_currency) }}</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        @lang('You will get '):
                                        <strong>{{ showAmount($deposit->amount) }}
                                            {{ __($general->cur_text) }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn--base w-100 mt-3"
                                    id="btn-confirm">@lang('Pay Now')</button>
                            </div>
                        </div>
                        <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}" data-email="{{ $data->email }}"
                            data-amount="{{ round($data->amount) }}" data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}"
                            data-custom-button="btn-confirm"></script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
