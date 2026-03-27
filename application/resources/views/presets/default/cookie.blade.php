@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!--==========================  privacy-policy Section Start  ==========================-->
    <div class="privacy-policy my-120">
        <div class="privacy-policy__shape1"> </div>
        <div class="privacy-policy__shape2"></div>
        <div class="container">
            <div class="privacy-policy__items">
                <div class="privacy-policy__content">
                    <div class="wyg">
                        @php
                            echo $cookie->data_values->description;
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==========================  privacy-policy Section End  ==========================-->
@endsection
