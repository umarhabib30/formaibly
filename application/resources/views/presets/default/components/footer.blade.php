@php
    $contactSection = getContent('contact_us.content', true);
    $socialIcons = getContent('social_icon.element', false);
    $companyLinks = App\Models\Menu::with(['items', 'menuItems'])
        ->where('code', 'company_link')
        ->first();
    $quickLinks = App\Models\Menu::with(['items', 'menuItems'])
        ->where('code', 'quick_link')
        ->first();
    $policyLinks = getContent('policy_pages.element', false, null, true);
@endphp

<!-- ==================== Footer Start Here ==================== -->
<footer class="footer">
    <div class="footer__shape">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" class="fit--img" alt="@lang('Shape image')">
    </div>
    <div class="footer__shape">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/shape.png') }}" class="fit--img" alt="@lang('Shape image')">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-3 footer__item_left pb-60 pt-120">
                <div class="footer__item footer__first-item">
                    <div class="footer__logo">
                        <a href="{{ route('home') }}" class="main__logo">
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo_white.png', '?' . time()) }}"
                                alt="@lang('logo image')">
                        </a>
                    </div>
                    <p class="footer__desc">{{ __($contactSection->data_values->footer_short_details) }}</p>
                    <ul class="social-list">
                        @foreach ($socialIcons ??[] as $item)
                            <li class="social-list__item">
                                <a href="{{ $item->data_values->url }}" class="social-list__link flex-center">
                                    @php echo $item->data_values->social_icon; @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-9 footer__item_right pb-60  ps-xl-5 ">
                <div class="footer__inner">
                    <div class="footer__item left-reveal">
                        <div class="footer__item-inner ">
                            <h5 class="footer__title">@lang('Company links')</h5>
                            <ul class="footer__menu">
                                @foreach ($companyLinks->items ??[] as $k => $data)
                                    @if ($data->link_type == 2)
                                        <li class="footer__menu-item">
                                            <a href="{{ $data->url ?? '' }}" target="_blank">{{ __($data->title) }}</a>
                                        </li>
                                    @else
                                        <li class="footer__menu-item">
                                            <a href="{{ route('pages', [$data->url]) }}"
                                                class="footer__menu-link {{ Request::url() == url($data->url) ? 'active' : '' }}">
                                                {{ __(ucfirst(strtolower($data->title)) ?? '') }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="footer__item">
                        <div class="footer__item-inner">
                            <h5 class="footer__title">@lang('Quick Links')</h5>
                            <ul class="footer__menu">
                                @foreach ($quickLinks->items ??[] as $k => $data)
                                    @if ($data->link_type == 2)
                                        <li class="footer__menu-item">
                                            <a href="{{ $data->url ?? '' }}"
                                                target="_blank">{{ __($data->title) }}</a>
                                        </li>
                                    @else
                                        <li class="footer__menu-item">
                                            <a href="{{ route('pages', [$data->url]) }}"
                                                class="footer__menu-link {{ Request::url() == url($data->url) ? 'active' : '' }}">
                                                {{ __(ucfirst(strtolower($data->title ?? ''))) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                <li class="footer__menu-item">
                                    <a href="{{ url('/') . '/policy/terms-of-service/42' }}"
                                        class="footer__menu-link {{ Request::url() == url('/') . '/policy/terms-of-service/42' ? 'active' : '' }}">
                                        @lang('Privacy Policy')
                                    </a>
                                </li>
                                <li class="footer__menu-item">
                                    <a href="{{ url('/') . '/policy/terms-of-service/43' }}"
                                        class="footer__menu-link 
                                        {{ Request::url() == url('/') . '/policy/terms-of-service/43' ? 'active' : '' }}">
                                        @lang('Terms And Conditions')
                                    </a>
                                </li>
                                <li class="footer__menu-item">
                                    <a href="{{ url('/') . '/cookie-policy' }}"
                                        class="footer__menu-link {{ Request::url() == url('/') . '/cookie-policy' ? 'active' : '' }}">
                                        @lang('Cookie Policy')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer__item footer__newsletter right-reveal">
                        <div class="footer__item-inner">
                            <h5 class="footer__title">@lang('Newsletter')</h5>
                            <p class="footer__desc">@lang('Subscribe to get account listings & deals')</p>
                            <form action="{{ route('subscribe') }}" method="POST" class="footer__form">
                                 @csrf
                                <div class="input--group">
                                    <input type="email" class="form--control" name="email"
                                         placeholder="@lang('Your email please')">
                                    <button class="btn" type="submit">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Top End-->
    <!-- bottom Footer -->
    <div class="footer__bottom">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-12 text-center">
                    <div class="bottom-footer-text text--white">@php echo $contactSection->data_values->website_footer; @endphp</div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ==================== Footer End Here ==================== -->
