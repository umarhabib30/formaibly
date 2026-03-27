<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $general->siteName(__('404')) }}</title>
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
</head>


<body>
    <!--==========================  404 Section Start  ==========================-->
    <div class="error">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" class="img-fluid"
                            alt="@lang('shape-image')">
                    </div>
                    <div class="error__shape">
                        <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" class="img-fluid"
                            alt="@lang('shape-image')">
                    </div>
                    <div class="glove--base"></div>
                    <div class="error__main py-60">
                        <img src="{{ getImage(getFilePath('error') . '404.png') }}" alt="@lang('error-image')">
                        <h2>404</h2>
                        <h4>@lang('Page Not Found')</h4>
                        <p>@lang('Whoops! Something Went Wrong')</p>
                        <a href="{{ route('home') }}" class="btn btn--base">@lang('Back to Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==========================  404 Section End  ==========================-->
</body>

</html>
