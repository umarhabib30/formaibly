@php
    $hmenu = App\Models\Menu::where('code', 'header_menu')->first();
    $pages = $hmenu ? $hmenu->items()->get() : [];
    $languages = App\Models\Language::all();
    $socialIcons = getContent('social_icon.element', false);
    $currentLang = $languages->firstWhere('code', session('lang', 'en'));
@endphp


<!-- ==================== Scroll to Top Start ==================== -->
<a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>
<!-- ==================== Scroll to Top End ==================== -->


<!--==========================  Overlay Start  ==========================-->
<div class="overlay"></div>
<!--==========================  Overlay End  ==========================-->
<!--==========================  Offcanvas Section Start  ==========================-->
<div class="offcanvas__area">
    <div class="offcanvas__topbar">
        <a href="{{ route('home') }}">
            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}" alt="@lang('logo')">
        </a>
        <span class="menu__close"><i class="las la-times"></i></span>
    </div>
    <div class="offcanvas__main">
        <div class="offcanvas__widgets">
            <div class="offcanvas__language">
                <div class="dropdown">
                    <div role="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="language__item">
                            <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image ?? '', getFileSize('language')) }}"
                                alt="@lang('image')">
                            <p>{{ ucfirst($currentLang->name) }}</p>
                        </div>
                    </div>
                    <ul class="dropdown-menu">
                        @foreach ($languages as $language)
                            <li>
                                <div class="language__item dropdown-item lang-change" data-lang="{{ $language->code }}">
                                    <img src="{{ getImage(getFilePath('language') . '/' . $language->image ?? '', getFileSize('language')) }}"
                                        alt="@lang('flag-image')">
                                    <p>{{ ucfirst($language->name) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="offcanvas__login">

                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @lang('Account')
                    </button>
                    <ul class="dropdown-menu">
                        @auth
                            <li>
                                <a href="{{ route('user.home') }}" class="dropdown-item">@lang('Dashboard')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.logout') }}" class="dropdown-item">@lang('Logout')</a>
                            </li>
                        @endauth
                        @guest
                            <li>
                                <a href="{{ route('user.login') }}" class="dropdown-item">@lang('Sign In')</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
        <div class="offcanvas__menu">
            <ul>
                @foreach ($pages as $k => $data)
                    @if ($data->link_type == 2)
                        <li>
                            <a href="{{ $data->url ?? '' }}" target="_blank">{{ __($data->title) }}</a>
                        </li>
                    @else
                        <li class="{{ route('pages', [$data->url]) == url()->current() ? 'active' : null }}">
                            <a href="{{ route('pages', [$data->url]) }}"
                                class="{{ Request::url() == url($data->url) ? 'active' : '' }}">{{ __($data->title) }}</a>
                        </li>
                    @endif
                @endforeach
                @auth
                    <li class="{{ route('user.home') ? 'active' : null }}">
                        <a href="{{ route('user.home') }}">@lang('Dashboard')</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
<!--==========================  Offcanvas Section End  ==========================-->

<!-- ==================== Header Start Here ==================== -->
<header class="header__area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="header__main">
                    <a href="{{ route('home') }}" class="main__logo">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png', '?' . time()) }}"
                            alt="@lang('logo')">
                    </a>
                    <div class="header__menu">
                        <ul>
                            @foreach ($pages as $k => $data)
                                @if ($data->link_type == 2)
                                    <li>
                                        <a href="{{ $data->url ?? '' }}" target="_blank">{{ __($data->title) }}</a>
                                    </li>
                                @else
                                    <li
                                        class="{{ route('pages', [$data->url]) == url()->current() ? 'active' : null }}">
                                        <a href="{{ route('pages', [$data->url]) }}"
                                            class="{{ Request::url() == url($data->url) ? 'active' : '' }}">{{ __($data->title) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="header__widgets">
                        <div class="dropdown">
                            <div role="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="language__item">
                                    <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image ?? '', getFileSize('language')) }}"
                                        alt="@lang('image')">
                                    <p>{{ ucfirst($currentLang->name) }}</p>
                                </div>
                            </div>
                            <ul class="dropdown-menu">
                                @foreach ($languages as $language)
                                    <li>
                                        <div class="language__item dropdown-item lang-change"
                                            data-lang="{{ $language->code }}">
                                            <img src="{{ getImage(getFilePath('language') . '/' . $language->image ?? '', getFileSize('language')) }}"
                                                alt="@lang('flag-image')">
                                            <p>{{ ucfirst($language->name) }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="header__login">
                            <div class="dropdown">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    @lang('Account')
                                </button>
                                <ul class="dropdown-menu">
                                    @auth
                                        <li>
                                            <a href="{{ route('user.home') }}" class="dropdown-item">@lang('Dashboard')</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.profile.setting') }}" class="dropdown-item">@lang('Profile Setting')</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.twofactor') }}" class="dropdown-item">@lang('2FA Security')</a>
                                        </li>

                                        <li>
                                            <a href="{{ route('user.logout') }}" class="dropdown-item">@lang('Logout')</a>
                                        </li>
                                    @endauth
                                    @guest
                                        <li>
                                            <a href="{{ route('user.login') }}" class="dropdown-item">@lang('Sign In')</a>
                                        </li>
                                    @endguest
                                </ul>
                            </div>
                        </div>
                    </div>
                    <span class="menu__open"><i class="fa-solid fa-bars"></i></span>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ==================== Header End Here ==================== -->
