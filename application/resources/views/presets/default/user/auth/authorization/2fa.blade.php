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
                                <h2 class="account__title">@lang('2FA Verification')</h2>
                                <p class="account__desc">
                                    @lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->user()->mobile) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3 gy-4">
                        <div class="verification-area mt-0">
                            <form action="{{ route('user.go2fa.verify') }}" method="POST" class="submit-form">
                                @csrf
                                @include($activeTemplate . 'components.verification_code')
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection


@push('style')
    <style>
        .verification-code input {
            letter-spacing: 92px !important;
            text-indent: 19px !important;
        }

        @media screen and (max-width: 1440px) {
            .verification-code input {
                letter-spacing: 70px !important;
                text-indent: 6px !important;
            }
        }

        @media screen and (max-width: 1023px) {
            .verification-code input {
                letter-spacing: 70px !important;
                text-indent: 6px !important;
            }
        }

        @media screen and (max-width: 991px) {
            .verification-code input {
                letter-spacing: 70px !important;
                text-indent: 6px !important;
            }
        }

        @media screen and (max-width: 426px) {
            .verification-code input {
                letter-spacing: 46px !important;
                text-indent: 7px !important;
            }
        }

        @media screen and (max-width: 376px) {
            .verification-code input {
                letter-spacing: 41px !important;
                text-indent: 5px !important;
            }
        }

        @media screen and (max-width: 321px) {
            .verification-code input {
                letter-spacing: 32px !important;
                text-indent: 2px !important;
            }
        }

        .verification-code::after {
            background-color: transparent;
        }

        .verification-code span {
            background: transparent !important;
            border: solid 1px hsl(var(--base) / 0.2);
        }
    </style>
@endpush
