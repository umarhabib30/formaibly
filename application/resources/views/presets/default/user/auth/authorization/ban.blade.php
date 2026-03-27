@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <main>
        <section class="account">
            <div class="account__inner">
                <div class="account__left">
                    <a href="{{ route('home') }}" class="account__logo logo">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo_white.png', '?' . time()) }}"
                            alt="@lang('logo')">
                    </a>
                    <h1 class="account__left-title">@lang('Welcome to FormBuilder')</h1>
                </div>
                <div class="account__form-wrap">

                    <div class="row">
                        <div class="col-12">
                            <div class="account__header">
                                <h2 class="account__title">{{ __($pageTitle) }}</h2>
                                <p class="account__desc">
                                    @lang('To recover your account please provide your email or username to find your account.')
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3 gy-4">
                        <div class="verification-area mt-0">
                            <h3 class="text-center text-danger">@lang('You are banned')</h3>
                            <p class="fw--500 mb-1">@lang('Reason'):</p>
                            <p>{{ $user->ban_reason }}</p>
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
