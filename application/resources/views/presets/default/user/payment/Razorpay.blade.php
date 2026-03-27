@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-md-12">
                <div class="profile__wrap card p-4">
                    <h5 class="mb-3">@lang('Razorpay')</h5>
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
                            <form action="{{ $data->url }}" method="{{ $data->method }}">
                                <input type="hidden" custom="{{ $data->custom }}" name="hidden">
                                <form src="{{ $data->checkout_js }}"
                                    @foreach ($data->val as $key => $value)
                                                data-{{ $key }}="{{ $value }}" @endforeach>
                                    </script>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn btn--base w-100");
        })(jQuery);
    </script>
@endpush
