@php
    $brandSectionContent = getContent('brand.content', true);
    $brandSectionElements = getContent('brand.element', false, false, true);
@endphp

<div class="brand-logo my-120">
    <div class="container">
        <div class="brand-logo__header">
            <h4>{{ __($brandSectionContent->data_values->heading) }}</h4>
        </div>
        <div class="brand-logo__slider">
            @foreach ($brandSectionElements ?? [] as $item)
                <div class="brand-logo__item">
                    <img src="{{ getImage(getFilePath('brand') . $item->data_values->brand_image) }}" alt="@lang('brand-logo')">
                </div>
            @endforeach
        </div>
    </div>
</div>

