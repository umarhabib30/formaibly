<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Formaibly">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>

    @include('includes.seo')
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/svg+xml" sizes="192x192" href="{{ asset('assets/pwa/icon-192x192.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/pwa/icon-192x192.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/jquery.bxslider.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    @stack('style-lib')
    @stack('style')
    <link rel="stylesheet"
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}&secondColor={{ $general->secondary_color }}">
</head>

<body>

    <div class="dashboard">
        @include('Template::components.user.side_bar')
        <div class="dashboard__wrap">
            <!--==========================   User-header Start  ==========================-->
            @include('Template::components.user.top_header')
            <!--==========================  User-header End  ==========================-->
            @yield('content')
          
        </div>
    </div>

    @include('Template::components.cookie')
 <script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/select2.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.bxslider.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script src="{{ asset('assets/common/js/pwa-install.js') }}"></script>

    @stack('script-lib')
    @stack('script')
    @include('includes.notify')
    @include('includes.plugins')

    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.lang-change', function() {
                const lang = $(this).data('lang');
                window.location.href = "{{ route('home') }}/change/" + lang;
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

        })(jQuery);
    </script>
    <script>
        (function() {
            "use strict";
            if (!("serviceWorker" in navigator) || !window.isSecureContext) {
                return;
            }

            window.addEventListener("load", function() {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(function(error) {
                    console.error("Service worker registration failed:", error);
                });
            });
        })();
    </script>
</body>

</html>
