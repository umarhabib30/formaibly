 @php
     $faqSectionContent = getContent('faq.content', true);
     $faqSectionElements = getContent('faq.element', false, false, true);
 @endphp

 <section class="faq my-120">
     <div class="container">
         <div class="row justify-content-between align-items-center g-5">
             <div class="col-xl-5">
                 <div class="row gy-4">
                     <div class="col-12">
                         <div class="section-heading style-left">
                             <span class="section-heading__name">{{ __($faqSectionContent->data_values->title) }}</span>
                             <h2 class="section-heading__title">{{ __($faqSectionContent->data_values->heading) }}</h2>
                             <p class="section-heading__desc">{{ __($faqSectionContent->data_values->subheading) }}</p>
                         </div>
                         <div class="faq__content-wrap">
                             <div class="faq__content-wrap">
                                 @foreach ($faqSectionElements ?? [] as $index => $item)
                                     <div class="faq__item {{$index+1 == 1 ? 'open ':''}}">
                                         <div class="faq__number"><span>{{ ($loop->iteration < 10 ? '0' : '') . $loop->iteration }}</span></div>
                                         <div class="faq__content">
                                             <h5 class="faq__title">{{__($item->data_values->question)}}</h5>
                                             <div class="faq__body">
                                                 <div class="faq__desc">@php echo $item->data_values->answer @endphp</div>
                                             </div>
                                         </div>
                                     </div>
                                 @endforeach
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-xl-6">
                 <div class="faq__thumb-wrap">
                     <h3 class="faq__thumb-title">@lang('ASKUS')</h3>
                     <div class="faq__shape">
                         <img src="{{getImage(getFilePath('shape').'shape.png')}}" alt="@lang('image')">
                     </div>
                     <div class="faq__thumb">
                         <img src="{{getImage(getFilePath('faq').$faqSectionContent->data_values->image)}}" alt="@lang('image')">
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>

