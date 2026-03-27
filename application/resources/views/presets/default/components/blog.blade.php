 @forelse ($blogs ?? [] as $item)
     <div class="col-sm-6 col-lg-4">
         <div class="blog__card">
             <div class="blog__thumb-wrap">
                 <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id]) }}"
                     class="blog__thumb">
                     <img src="{{ getImage(getFilePath('blog') . 'thumb_' . $item->data_values->blog_image) }}"
                         class="fit--img" alt="@lang('blog image')">
                 </a>
             </div>
             <div class="blog__body">
                 <span class="blog__date"><i class="fa-regular fa-calendar"></i>
                     {{ showDateTime($item->created_at, 'd M,Y') }}</span>
                 <h4 class="blog__title">
                     <a
                         href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id]) }}">
                         @if (strlen(__($item->data_values->title)) > 55)
                             {{ strLimit(__($item->data_values->title), 55) }}
                         @else
                             {{ __($item->data_values->title) }}
                         @endif
                     </a>
                 </h4>
                 <p class="blog__desc">
                    @if (strlen(__($item->data_values->description)) > 85)
                             @php echo strLimit(__($item->data_values->description), 85); @endphp
                         @else
                                @php echo $item->data_values->description; @endphp
                         @endif
                 </p>
                 <a href="{{ route('blog.details', ['slug' => slug($item->data_values->title), 'id' => $item->id]) }}"
                     class="btn--unstyle">@lang('Read More') <i class="ti ti-arrow-up-right"></i></a>
                 <div class="blog__shape">
                     <img src="{{getImage(getFilePath('shape').'shape.png')}}" alt="@lang('image')">
                 </div>
             </div>
         </div>
     </div>
 @empty
     <div class="col-12 text-center">
         <h6>@lang('No Data Found')</h6>
     </div>
 @endforelse
