@php
    $pricingSectionContent = getContent('pricing.content', true);
    $plans = App\Models\Plan::where('status', Status::PLAN_ENABLE)->get();
@endphp

<section class="pricing my-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <span class="section-heading__name">{{ __($pricingSectionContent->data_values->title) }}</span>
                    <h2 class="section-heading__title">{{ __($pricingSectionContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __($pricingSectionContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav custom--tab nav--pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-monthly-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-monthly" type="button" role="tab" aria-controls="pills-monthly"
                            aria-selected="true">@lang('Monthly')</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-yearly-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-yearly" type="button" role="tab" aria-controls="pills-yearly"
                            aria-selected="false">@lang('Yearly')</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-monthly" role="tabpanel"
                        aria-labelledby="pills-monthly-tab">
                        <div class="row justify-content-center gy-4">
                            @foreach ($plans ?? [] as $item)
                                @if ($item->period == 'monthly')
                                    <div class="col-md-6 col-lg-4 col-xxl-3">
                                        <div class="pricing__card {{ $item->is_popular ? 'active' : '' }}">
                                            @if ($item->is_popular)
                                                <span class="badge bg--success popular-card">@lang('Most Popular')</span>
                                            @endif
                                            <h4 class="pricing__name">{{ __($item->name) }}</h4>
                                            <h3 class="pricing__price">
                                                 @if($item->price > 0)
                                                  {{ $general->cur_sym }}{{ showAmount($item->price) }}/
                                                 @else
                                                  {{ $general->cur_sym }}{{ showAmount($item->price) }}/
                                                 @endif
                                                <span>@lang('month')</span>
                                            </h3>
                                            <p class="pricing__desc">{{ __($item->short_description) }}</p>
                                            <a href="{{route('user.plan.payment',$item->id)}}" class="btn btn-outline--base w--100">@lang('GET START')</a>
                                            <ul class="pricing__list">
                                                @foreach ($item->features ?? [] as $val)
                                                    <li>{{ $val }}</li>
                                                @endforeach
                                            </ul>
                                            <div class="pricing__shape">
                                                <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}"
                                                    class="fit--img" alt="@lang('Shape Image')">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
                        <div class="row justify-content-center gy-4">
                            @foreach ($plans ?? [] as $item)
                                @if ($item->period == 'yearly')
                                    <div class="col-md-6 col-lg-4 col-xxl-3">
                                        <div class="pricing__card {{ $item->is_popular ? 'active' : '' }}">
                                            @if ($item->is_popular)
                                                <span class="badge bg--success popular-card">@lang('Most Popular')</span>
                                            @endif
                                            <h4 class="pricing__name">{{ __($item->name) }}</h4>
                                            <h3 class="pricing__price">
                                                 @if($item->price > 0)
                                                  {{ $general->cur_sym }}{{ showAmount($item->price) }}/
                                                 @else
                                                  {{ $general->cur_sym }}{{ showAmount($item->price) }}/
                                                 @endif
                                                <span>@lang('year')</span>
                                            </h3>
                                            <p class="pricing__desc">{{ __($item->short_description) }}</p>
                                            <a href="{{route('user.plan.payment',$item->id)}}" class="btn btn-outline--base w--100">@lang('GET START')</a>
                                            <ul class="pricing__list">
                                                @foreach ($item->features ?? [] as $val)
                                                    <li>{{ $val }}</li>
                                                @endforeach
                                            </ul>
                                            <div class="pricing__shape">
                                                <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" class="fit--img" alt="@lang('Shape Image')">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

