@php
    $featureSectionContent = getContent('feature.content', true);
    $featureSectionElements = getContent('feature.element', false, false, true);
@endphp


@if (Route::is('home'))
    <section class="features my-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <span class="section-heading__name">{{ __($featureSectionContent->data_values->title) }}</span>
                        <h2 class="section-heading__title">{{ __($featureSectionContent->data_values->heading) }}</h2>
                        <p class="section-heading__desc">{{ __($featureSectionContent->data_values->subheading) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="custom--tab-wrap">
                        <ul class="nav custom--tab underline">
                            @foreach ($featureSectionElements ?? [] as $index => $item)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index + 1 == 1 ? 'active' : '' }}"
                                        id="{{ slug($item->data_values->title) }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#{{ slug($item->data_values->title) }}" type="button"
                                        role="tab" aria-controls="{{ slug($item->data_values->title) }}"
                                        aria-selected="true">{{ __($item->data_values->title) }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-content" id="underline-tabContent">
                        @foreach ($featureSectionElements ?? [] as $index => $item)
                            <div class="tab-pane fade {{ $index + 1 == 1 ? 'show active' : '' }}"
                                id="{{ slug($item->data_values->title) }}"
                                aria-labelledby="{{ slug($item->data_values->title) }}-tab">
                                @include('Template::components.feature')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <section class="features my-120">
        <div class="container">
            <div class="row gy-4">
                @foreach ($featureSectionElements ?? [] as $index => $item)
                    <div class="col-12">
                        @include('Template::components.feature')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

