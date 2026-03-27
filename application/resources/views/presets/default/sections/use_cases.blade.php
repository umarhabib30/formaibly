@php
    $useCasesSectionContent = getContent('use_cases.content', true);
    $useCasesSectionElements = getContent('use_cases.element', false, false, true);
@endphp
<section class="use-case my-120">
    <div class="container">
        <div class="row g-4 justify-content-between align-items-center">
            <div class="col-md-5">
                <div class="section-heading style-left">
                    <span class="section-heading__name">{{ __($useCasesSectionContent->data_values->title) }}</span>
                    <h2 class="section-heading__title">{{ __($useCasesSectionContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __($useCasesSectionContent->data_values->subheading) }}</p>
                </div>
                <div class="use-case__list">
                        @foreach ($useCasesSectionElements ?? [] as $index => $item)
                        <div class="use-case__list-item {{ $index + 1 == 1 ? 'active' : '' }}"
                            data-use-case-id="{{ slug($item->data_values->title) }}">
                            <span class="use-case__list-icon">@php echo $item->data_values->icon @endphp </span>
                            <h4 class="use-case__list-title">{{ __($item->data_values->title) }}</h4>
                        </div>
                        @endforeach
                    </div>
            </div>
            <div class="col-md-6">
                <div class="use-case__banner-wrap">
                    @foreach ($useCasesSectionElements ?? [] as $index=>$item)
                        <div class="use-case__banner {{ $index + 1 == 1 ? 'show' : '' }}"
                            id="{{ slug($item->data_values->title) }}">
                            <div class="use-case__offer-thumb">
                                <img src="{{ getImage(getFilePath('use_cases') . $useCasesSectionContent->data_values->image) }}"
                                    alt="@lang('image')">
                            </div>
                            <div class="use-case__shape">
                                <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" alt="@lang('image')">
                            </div>
                            <div class="use-case__thumb">
                                <img src="{{ getImage(getFilePath('use_cases') . $item->data_values->image) }}"
                                    alt="@lang('image')">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

