@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="row g-4">
                    @if (!auth()->user()->ts)
                        <div class="col-xl-4">
                            <div class="profile__wrap card p-4">
                                <div class="card-header pt-0 mb-4">
                                    <h5 class="card-title fs--20 fw--600">@lang('Two Factor Authenticator')</h5>
                                </div>
                                <div class="profile__form">
                                    <div class="two__factor__info">
                                        <p class="fs-14 fw--500 mb-4">
                                            @lang('Use the QR code or setup key on your Google Authenticator app to add your account.')
                                        </p>
                                        <div class="text-center qr-code">
                                            <img src="{{ $qrCodeUrl }}"alt="@lang('QR Code')">
                                        </div>
                                    </div>
                                    <div class="two__factor__key">
                                        <label class="form-label">@lang('Setup Key')</label>
                                        <div class="input--group">
                                            <input type="text" class="form--control referralURL"
                                                value="{{ $secret }}" readonly="" id="key">
                                            <button class="input-group-text copytext" id="copyBoard">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-xl-8">
                        @if (auth()->user()->ts)
                            <div class="profile__wrap card p-4">
                                <div class="card-header pt-0 mb-4">
                                    <h5 class="card-title fs--20 fw--600">@lang('Disable 2FA Security')</h5>
                                </div>
                                <div class="profile__form">
                                    <form action="{{ route('user.twofactor.disable') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $secret }}">
                                        <div class="two__factor__key">
                                            <label class="form-label" for="code">@lang('Google Authenticator OTP')</label>
                                            <input class="form--control" type="text" name="code" required=""
                                                id="code" placeholder="@lang('Enter OTP')">
                                            <button type="submit"
                                                class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="profile__wrap card p-4">
                                <div class="card-header pt-0 mb-4">
                                    <h5 class="card-title fs--20 fw--600">@lang('Enable 2FA Security')</h5>
                                </div>
                                <div class="profile__form">
                                    <form action="{{ route('user.twofactor.enable') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $secret }}">
                                        <div class="two__factor__key">
                                            <label class="form-label required" for="code">@lang('Google Authenticator OTP')</label>
                                            <input class="form--control" type="text" name="code" required=""
                                                id="code" placeholder="@lang('Enter OTP')" required>
                                            <button type="submit"
                                                class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                       
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
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                notify('success','Copied this code');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
