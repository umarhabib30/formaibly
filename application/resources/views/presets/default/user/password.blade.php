@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-8">
                <div class="profile__wrap card p-4">
                    <form action="" method="post">
                        @csrf
                        <div class="row g-4">
                            <div class="col-sm-12">
                                <div class="profile__form">
                                    <label class="form-label">@lang('Current Password')</label>
                                    <div class="position-relative password__field">
                                        <input type="password" class="form--control" id="currentPassword"
                                            placeholder="@lang('Current Password')" name="current_password" type="password"
                                            required>
                                        <div class="password-show-hide">
                                            <i class="fa-solid fa-eye close-eye-icon"></i>
                                            <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="profile__form">
                                    <label class="form-label">@lang('New Password')</label>
                                    <div class="position-relative password__field">
                                        <input class="form--control" type="password" id="newPassword"
                                            placeholder="@lang('Password')" name="password" type="password" required>
                                        <div class="password-show-hide">
                                            <i class="fa-solid fa-eye close-eye-icon"></i>
                                            <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                @if ($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-12">
                                <div class="profile__form">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <div class="position-relative password__field">
                                        <input class="form--control" type="password" id="confirmPassword"
                                            placeholder="@lang('Confirm Password')" name="password_confirmation" type="password"
                                            required>
                                        <div class="password-show-hide">
                                            <i class="fa-solid fa-eye close-eye-icon"></i>
                                            <i class="fa-solid fa-eye-slash open-eye-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="profile__form">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').on('focus', function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').on('focusout', function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif
        })(jQuery);
    </script>
@endpush
