 @php
     $testimonialSectionContent = getContent('testimonial.content', true);
     $testimonialSectionElements = getContent('testimonial.element', false, false, true);
 @endphp
 <div class="testimonial">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <div class="testimonial__inner py-120">
                     <div class="testimonial__shape">
                         <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" class="fit--img"
                             alt="@lang('image')">
                     </div>
                     <div class="testimonial__shape">
                         <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" class="fit--img"
                             alt="@lang('image')">
                     </div>
                     <div class="testimonial__slider">
                         @foreach ($testimonialSectionElements ?? [] as $item)
                             <div class="testimonial__item">
                                 <div class="testimonial__thumb">
                                     <img src="{{getImage(getFilePath('testimonial').$item->data_values->image)}}" alt="@lang('image')">
                                 </div>
                                 <div>
                                     <p class="testimonial__desc">{{__($item->data_values->title)}}</p>
                                     <span class="testimonial__designation">{{__($item->data_values->location)}}</span>
                                 </div>
                             </div>
                         @endforeach
                      
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

