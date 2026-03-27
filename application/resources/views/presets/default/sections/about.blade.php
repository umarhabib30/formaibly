@php
    $aboutSectionContent = getContent('about.content', true);
    $aboutSectionElements = getContent('about.element', false, 3, false);
@endphp
<section class="about my-120">
    <div class="container">
        <div class="row gy-4 justify-content-between align-items-center">
            <div class="col-lg-5">
                <div class="section-heading style-left">
                    <span class="section-heading__name">{{ __($aboutSectionContent->data_values->title) }}</span>
                    <h2 class="section-heading__title">
                        {{ __($aboutSectionContent->data_values->heading) }}
                    </h2>
                    <p class="section-heading__desc">
                        {{ __($aboutSectionContent->data_values->subheading) }}
                    </p>
                </div>
                <ul class="about__list">
                    @foreach ($aboutSectionElements ?? [] as $item)
                        <li class="about__list-item">
                            {{__($item->data_values->key_feature)}}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="about__banner">
                    <div class="about__shape">
                        <img src="{{getImage(getFilePath('shape').'shape.png')}}" alt="@lang('shape image')">
                    </div>
                    <div class="about__thumb">
                        <img src="{{getImage(getFilePath('about').$aboutSectionContent->data_values->image)}}" alt="@lang('image')">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

