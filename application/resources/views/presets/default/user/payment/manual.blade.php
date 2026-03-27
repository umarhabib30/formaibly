@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-md-12">
                <div class="profile__wrap card p-4">
                     <h3 class="mb-3 text-center">@lang('Payment Via') {{ __($data->gateway->name) }}</h3>
                    <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p class="text-center mt-2">@lang('You have requested')
                                    <b class="text-success">{{ showAmount($data['amount']) }}
                                        {{ __($general->cur_text) }}</b> , @lang('Please pay')
                                    <b class="text-success">{{ showAmount($data['final_amo']) . ' ' . $data['method_currency'] }}
                                    </b> @lang('for successful payment')
                                </p>
                                <h5 class="text-center mb-4">@lang('Please follow the instruction below')</h5>
                                <p class="my-4 text-center">@php echo  $data->gateway->description @endphp</p>
                            </div>

                            <x-custom-form identifier="id" identifierValue="{{ $gateway->form_id }}"></x-custom-form>

                            <div class="col-md-12">
                                <div class="form-group">
                                    @if ($data->is_credit_purchase)
                                        <button type="submit"
                                            class="btn btn--base btn--lg w-100">@lang('Credit Purchase')</button>
                                    @else
                                        <button type="submit"
                                            class="btn btn--base btn--lg w-100">@lang('Payment Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
