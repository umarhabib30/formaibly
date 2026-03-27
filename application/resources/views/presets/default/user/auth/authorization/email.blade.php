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
                                <h2 class="account__title">@lang('Verify Email Address')</h2>
                                <p class="account__desc">
                                    @lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(auth()->user()->email) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3 gy-4">
                        <div class="verification-area mt-0">
                            <form action="{{ route('user.verify.email') }}" method="POST" class="submit-form">
                                @csrf

                                @include($activeTemplate . 'components.verification_code')
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                                <div>
                                    <p>
                                        @lang('If you don\'t get any code'),
                                        <a href="{{ route('user.send.verify.code', 'email') }}"><u>@lang('Try again')</u></a>
                                    </p>
                                    @if ($errors->has('resend'))
                                        <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                                    @endif
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
            letter-spacing: 5.75rem !important;
            text-indent: 1.1875rem !important;
        }

        @media screen and (max-width: 90rem) {
            .verification-code input {
                letter-spacing: 4.375rem !important;
                text-indent: .375rem !important;
            }
        }

        @media screen and (max-width: 63.9375rem) {
            .verification-code input {
                letter-spacing: 4.375rem !important;
                text-indent: .375rem !important;
            }
        }

        @media screen and (max-width: 61.9375rem) {
            .verification-code input {
                letter-spacing: 4.375rem !important;
                text-indent: .375rem !important;
            }
        }

        @media screen and (max-width: 26.625rem) {
            .verification-code input {
                letter-spacing: 2.875rem !important;
                text-indent: .4375rem !important;
            }
        }

        @media screen and (max-width: 23.5rem) {
            .verification-code input {
                letter-spacing: 2.5625rem !important;
                text-indent: .3125rem !important;
            }
        }

        @media screen and (max-width: 20.0625rem) {
            .verification-code input {
                letter-spacing: 2rem !important;
                text-indent: .125rem !important;
            }
        }

        .verification-code::after {
            background-color: transparent;
        }

        .verification-code span {
            background: transparent !important;
            border: solid .0625rem hsl(var(--base) / 0.2);
        }
    </style>
@endpush
