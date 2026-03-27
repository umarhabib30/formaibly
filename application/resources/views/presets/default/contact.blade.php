@php
    $contactSectionContent = getContent('contact_us.content', true);
    $socialIcons = getContent('social_icon.element', false);
    $user = auth()->user();
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- ==================== Contact Section Start Here ==================== -->
    <div class="contact section-bg-2">
        <div class="container">
            <div class="row gy-4 justify-content-center my-120">
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="contact__item">
                        <div class="contact__shape">
                            <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" alt="img">
                        </div>
                        <div class="contact__icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="contact__info">
                            <p>@lang('Phone Number')</p>
                            <h5>
                                <a href="tel:{{ $contactSectionContent->data_values?->contact_number ?? '' }}">
                                    {{ $contactSectionContent->data_values?->contact_number }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="contact__item">
                        <div class="contact__shape">
                            <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" alt="img">
                        </div>
                        <div class="contact__icon">
                            <i class="fa-solid fa-envelope-open"></i>
                        </div>
                        <div class="contact__info">
                            <p>@lang('Our Email')</p>
                            <h5>
                                <a
                                    href="mailto:{{ $contactSectionContent->data_values?->email_address ?? '' }}">{{ $contactSectionContent->data_values?->email_address ?? '' }}</a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="contact__item">
                        <div class="contact__shape">
                            <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" alt="img">
                        </div>
                        <div class="contact__icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="contact__info">
                            <p>@lang('Address')</p>
                            <h5>{{ $contactSectionContent->data_values?->contact_details }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="contact__item">
                        <div class="contact__shape">
                            <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" alt="img">
                        </div>
                        <div class="contact__icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div class="contact__info">
                            <p>@lang('Support')</p>
                            <h5>{{ $contactSectionContent->data_values?->support_number ?? '' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-between my-120">
                <div class="col-lg-6">
                    <div class="contact__form">
                        <form method="post" action="#" class="row gy-4 verify-gcaptcha">
                            @csrf
                            <div class="col-12">
                                <div class="section-heading style-left">
                                    <span
                                        class="section-heading__name">{{ __($contactSectionContent->data_values?->top_heading) }}</span>
                                    <h2 class="section-heading__title">
                                        {{ __($contactSectionContent->data_values?->heading) }}</h2>
                                    <p class="section-heading__desc">
                                        {{ __($contactSectionContent->data_values?->short_description) }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="userName" class="form--label">@lang('Username')</label>
                                <input type="text" class="form--control" id="userName" name="name"
                                    value="@if (auth()->user()) {{ auth()->user()->fullname }}@else{{ old('name') }} @endif"
                                    @if (auth()->user()) readonly @endif required
                                    placeholder="@lang('Enter Username')">
                            </div>
                            <div class="col-lg-6">
                                <label class="form--label" for="emailAddress">@lang('Email Address')</label>
                                <input type="email" class="form--control" id="emailAddress"
                                    placeholder="@lang('Enter Email Address')" name="email"
                                    value="@if (auth()->user()) {{ auth()->user()->email }}@else{{ old('email') }} @endif"
                                    @if (auth()->user()) readonly @endif required>
                            </div>
                            <div class="col-12">
                                <label class="form--label" for="subject">@lang('Subject')</label>
                                <input type="text" class="form--control" id="subject" name="subject"
                                    placeholder="@lang('Subject')" value="{{ old('subject') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label" for="message">@lang('Message')</label>
                                <textarea class="form--control" name="message" placeholder="@lang('Enter your message here')..." id="message" required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-lg-12 recap">
                                <x-captcha></x-captcha>
                            </div>
                            <div class="col-12">
                                <button class="btn btn--base"
                                    id="recaptcha">{{ __($contactSectionContent->data_values?->button_text) }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact__map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387196.07666627044!2d{{ $contactSectionContent->data_values?->longitude }}!3d{{ $contactSectionContent->data_values?->latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1757239202181!5m2!1sen!2sbd"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== Contact Section End Here ==================== -->
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script')
    <script>
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
