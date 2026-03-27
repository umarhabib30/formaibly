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
                    <form class="account__form" method="POST" action="{{ route('user.password.email') }}">
                        @csrf
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
                            <div class="col-lg-12 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="value"
                                value="{{ old('value') }}" id="usernameInput" placeholder="@lang('Email or Username')" required autofocus="off">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100">
                                    @lang('Submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
