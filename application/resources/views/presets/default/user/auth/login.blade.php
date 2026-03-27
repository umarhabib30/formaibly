@php
    $credentials = $general->socialite_credentials;
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <main>
        <section class="account">
            <div class="account__shape">
                <img src="{{ asset($activeTemplateTrue . 'images/auth/auth.png') }}" alt="image">
            </div>
            <div class="account__shape">
                <img src="{{ asset($activeTemplateTrue . 'images/auth/auth.png') }}" alt="image">
            </div>
            <div class="account__inner">
                <div class="account__left">
                    <a href="{{ route('home') }}" class="account__logo logo">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo_white.png', '?' . time()) }}"
                            alt="@lang('logo')">
                    </a>
                    <h1 class="account__left-title">@lang('Welcome to FormBuilder')</h1>
                </div>
                <div class="account__form-wrap">
                    <form class="account__form" method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="account__header">
                                    <h6 class="account__subtitle">@lang('Welcome back')!</h6>
                                    <h2 class="account__title">@lang('Sign in your account')</h2>
                                    <p class="account__desc">
                                        @lang('It only takes a minute to start earning with surveys you’ll actually enjoy.')
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-4">
                            <div class="col-lg-12 col-md-6 col-sm-6">
                                <input type="text" class="form--control" id="usernameInput" name="username"
                                    value="{{ old('username') }}" placeholder="@lang('Enter your username')">
                            </div>

                            <div class="col-lg-12 col-md-6 col-sm-6">
                                <div class="overflow-hidden">
                                    <div class="position-relative password__field">
                                        <input id="password" type="password" class="form--control" name="password"
                                            placeholder="@lang('Enter your password')">
                                        <div class="password-show-hide">
                                            <i class="fa-solid fa-eye close-eye-icon"></i>
                                            <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6 col-sm-6 recap">
                                <x-captcha></x-captcha>
                            </div>

                            <div class="col-12">
                                <div class="flex-between gap-2">
                                    <div class="form--check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="" name="remember"
                                                id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            @lang('Remember Me')
                                        </label>
                                    </div>
                                    <a class="btn--unstyle text--base" href="{{ route('user.password.request') }}">
                                        @lang('Forgot Password?')
                                    </a>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100" id="recaptcha">
                                    @lang('Sign In')
                                </button>
                            </div>

                            @if ($credentials->google->status == 1 || $credentials->facebook->status == 1)
                                <div class="col-12">
                                    <ul class="social-list">
                                        @if ($credentials->google->status == 1)
                                            <li class="social-list__item">
                                                <a href="{{ route('user.social.login', 'google') }}"
                                                    class="social-list__link flex-center">
                                                    <div class="icon">
                                                        <i class="fa-brands fa-google"></i>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($credentials->facebook->status == 1)
                                            <li class="social-list__item">
                                                <a href="{{ route('user.social.login', 'facebook') }}"
                                                    class="social-list__link flex-center">
                                                    <div class="icon">
                                                        <i class="fa-brands fa-facebook-f"></i>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <div class="col-sm">
                                <p class="account__already text-center">
                                    @lang('Don’t have an account? ')
                                    <a href="{{ route('user.register') }}"
                                        class="btn--unstyle text--base">@lang('Sign Up')</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            'use strict';
            $('.recap').each(function () {
                if ($(this).children().length === 0) {
                    $(this).addClass('d-none');
                } else {
                    $(this).removeClass('d-none');
                }
            });
        });
    </script>
@endpush