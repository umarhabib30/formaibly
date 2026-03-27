@php
    $howItWorkSectionContent = getContent('how_it_work.content', true);
    $howItWorkSectionElements = getContent('how_it_work.element', false, false, true);
@endphp

<section class="work-process py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading style-white">
                    <span class="section-heading__name">{{ __($howItWorkSectionContent->data_values->title) }}</span>
                    <h2 class="section-heading__title">{{ __($howItWorkSectionContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __($howItWorkSectionContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($howItWorkSectionElements ?? [] as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="work-process__card left-reveal">
                        <h4 class="work-process__title">{{ __($item->data_values->heading) }}</h4>
                        <p class="work-process__desc">{{ __($item->data_values->subheading) }}</p>
                        <span
                            class="work-process__count">{{ ($loop->iteration < 10 ? '0' : '') . $loop->iteration }}</span>
                        <div class="work-process__shape">
                            <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" alt="@lang('image')">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
