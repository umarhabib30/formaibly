@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-md-12">
                <div class="profile__wrap card p-4">
                    <h3 class="mb-3">@lang('Payment Preview')</h3>
                    <div class="row g-4">
                        <div class="col-sm-12">
                            <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text-success">
                                    {{ $data->amount }}</span> {{ __($data->currency) }}</h4>
                            <h5 class="mb-2">@lang('TO') <span class="text-success">
                                    {{ $data->sendto }}</span></h5>
                            <img src="{{ $data->img }}" alt="@lang('Image')">
                            <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
