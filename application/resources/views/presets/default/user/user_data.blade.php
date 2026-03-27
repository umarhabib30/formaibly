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
                    <form class="account__form" method="POST" action="{{ route('user.data.submit') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="account__header">
                                    <h2 class="account__subtitle">{{ __($pageTitle) }}</h2>
                                    <p class="account__desc">
                                        @lang('Update Your Profile Information and Keep Your Account Details Up to Date Easily and Securely.')
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3 gy-4">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="firstname" id="firstname"
                                    value="{{ old('firstname') }}" placeholder="@lang('First Name')" required>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="lastname" id="lastname" value="{{ old('lastname') }}" placeholder="@lang('Last Name')"
                                    required>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="address" id="address" value="{{ old('address') }}" placeholder="@lang('Address')">
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="state" id="state" value="{{ old('state') }}" placeholder="@lang('State')">
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="zip" id="zip" value="{{ old('zip') }}" placeholder="@lang('Zip')">
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form--control" name="city" id="city" value="{{ old('city') }}" placeholder="@lang('City')">
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

