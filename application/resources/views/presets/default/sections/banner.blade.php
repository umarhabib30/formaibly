@php
    $bannerSectionContent = getContent('banner.content', true);
@endphp


<section class="banner">
    <div class="banner__shape">
        <img src="{{ getFilePath('shape') . 'shape.png' }}" alt="@lang('shape image')">
    </div>
    <div class="banner__shape">
        <img src="{{ getFilePath('shape') . 'shape.png' }}" alt="@lang('shape image')">
    </div>
    <div class="glove--base"></div>
    <div class="container">
        <div class="row g-4 g-md-5">
            <div class="col-lg-6">
                <div class="banner__content">
                    <div class="banner__subtitle">
                        <div class="banner__subtitle-icon">
                            <img src="{{getImage(getFilePath('banner').'icon.png')}}" class="img-fluid" alt="@lang('banner-image')">
                        </div>
                        <span>{{__($bannerSectionContent->data_values->title)}}</span>
                    </div>
                    <h1 class="banner__title">{{__($bannerSectionContent->data_values->heading)}}</h1>
                    <p class="banner__desc">{{__($bannerSectionContent->data_values->subheading)}}</p>
                    <div class="banner__btns mt-40">
                        <a href="{{ url('/').$bannerSectionContent->data_values->button_one_url }}" class="btn btn--base">{{__($bannerSectionContent->data_values->button_one)}}</a>
                        <a href="{{ url('/').$bannerSectionContent->data_values->button_two_url }}" class="btn btn--white">{{__($bannerSectionContent->data_values->button_two)}}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="banner__thumb-wrap">
                    <div class="banner__thumb">
                        <img src="{{getImage(getFilePath('banner').$bannerSectionContent->data_values->image_one)}}" alt="@lang('banner-image')">
                    </div>
                    <div class="banner__thumb-2-wrap">
                        <div class="banner__thumb-2">
                            <img src="{{getImage(getFilePath('banner').$bannerSectionContent->data_values->image_two)}}" alt="@lang('banner-image')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

