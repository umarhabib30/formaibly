@php
    $credentials = $general->socialite_credentials;
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- sign-up section start -->
    <main>
        <section class="account register">
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
                    <form class="account__form" action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="account__header">
                                    <h6 class="account__subtitle">@lang('Welcome back!')</h6>
                                    <h2 class="account__title">@lang('Register your account')</h2>
                                    <p class="account__desc">@lang('Secure Access to Your World: Log in to Experience Seamless and Personalized Services.')</p>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-3">
                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control checkUser" name="username"
                                    value="{{ old('username') }}" id="usernameInput" placeholder="@lang('Username')"
                                    required>
                                <p class="text--danger usernameExist"></p>
                            </div>
                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <input type="email" class="form--control checkUser" id="emailInput"
                                    value="{{ old('email') }}" name="email" placeholder="@lang('Email Address')" required>
                                <p class="text--danger mt-1 emailExist"></p>
                            </div>

                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <select id="country" name="country" class="form--control select-2" required>
                                    @foreach ($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}"
                                            data-code="{{ $key }}">
                                            {{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <input type="hidden" name="mobile_code">
                                <input type="hidden" name="country_code">
                                <div class="input--group">
                                    <span class="input-group-text mobile-code"></span>
                                    <input type="number" name="mobile" id="phoneInput" value=""
                                        class="form--control checkUser" required id="mobile"
                                        placeholder="@lang('Phone Number')">
                                </div>
                                <p class="text--danger mobileExist"></p>
                            </div>

                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <div class="position-relative password__field w-100">
                                    <input type="password" class="form--control" id="passwordInput"
                                        placeholder="@lang('Password')" name="password" type="password" autocomplete="off" required>
                                    <div class="password-show-hide">
                                        <i class="fa-solid fa-eye close-eye-icon"></i>
                                        <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                    </div>
                                    @if ($general->secure_password)
                                        <div class="input-popup">
                                            <p class="error lower text--white">@lang('1 small letter minimum')</p>
                                            <p class="error capital text--white">@lang('1 capital letter minimum')</p>
                                            <p class="error number text--white">@lang('1 number minimum')</p>
                                            <p class="error special text--white">@lang('1 special character minimum')</p>
                                            <p class="error minimum text--white">@lang('6 character password')</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 col-sm-6">
                                <div class="position-relative password__field w-100">
                                    <input type="password" class="form--control" id="confirmPasswordInput"
                                        placeholder="Confirm Password" name="password_confirmation" type="password" autocomplete="off"
                                        required>
                                    <div class="password-show-hide">
                                        <i class="fa-solid fa-eye close-eye-icon"></i>
                                        <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6 col-sm-6 recap">
                                <x-captcha></x-captcha>
                            </div>

                            @if ($general->agree)
                                <div class="col-sm-12">
                                    <div class="form--check">
                                        <input class="form-check-input" type="checkbox" name="agree"
                                            @checked(old('agree')) id="remember" required>
                                        <label class="form-check-label w-auto" for="remember">
                                            @lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}"
                                                    class="btn--underline">{{ __($policy->data_values->title) ?? '' }}</a>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn--base w-100" id="recaptcha">@lang('Sign Up')</button>
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
                            
                            <div class="col-sm-12">
                                <div class="have-account text-center">
                                    <p class="have-account__text">@lang('You have any account?') <a href="{{ route('user.login') }}"
                                            class="have-account__link text--base fw-semibold">@lang('Sign In')</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- sign-up section start -->


    {{-- =======-** Sign Up End **-======= --}}
    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center my-4">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('user.login') }}" class="btn btn--base btn--md">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/common/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').on('focus'function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').on('focusout', function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;

                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {


                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);

        $(document).ready(function() {
            'use strict';
            $('.recap').each(function() {
                if ($(this).children().length === 0) {

                    $(this).addClass('d-none');
                } else {
                    $(this).removeClass('d-none');
                }
            });
        });
    </script>
@endpush
