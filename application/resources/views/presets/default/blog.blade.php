@extends($activeTemplate . 'layouts.frontend')
@section('content')
    {{-- ==========================  Blog Section Start  ========================== --}}
    <section class="blog section-bg-2 my-120">
        <div class="container">
            <div class="row g-4">
                @include($activeTemplate . 'components.blog')
                {{-- pagination --}}
                @if ($blogs->hasPages())
                    {{-- pagination --}}
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="py-4">
                                {{ paginateLinks($blogs) }}
                            </div>
                        </div>
                    </div>
                    {{-- / pagination --}}
                @endif
            </div>
        </div>
    </section>
    <!--==========================  Blog Details Section End  ==========================-->

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
