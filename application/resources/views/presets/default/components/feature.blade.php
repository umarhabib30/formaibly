  <div class="features__card">
      <div class="row justify-content-between align-content-center gx-5 gy-4">
          <div class="col-lg-6">
              <div class="features__thumb-wrap">
                  <div class="features__shape">
                      <img src="{{ getImage(getFilePath('shape') . 'shape.png') }}" alt="@lang('shape-image')">
                  </div>
                  <div class="features__thumb">
                      <img src="{{ getImage(getFilePath('feature') . $item->data_values->image_one) }}"
                          alt="@lang('image')">
                  </div>
                  <div class="features__thumb-2">
                      <img src="{{ getImage(getFilePath('feature') . $item->data_values->image_two) }}"
                          alt="@lang('image')">
                  </div>
              </div>
          </div>
          <div class="col-lg-6">
              <div class="features__content">
                  <span class="features__subtitle">{{ __($item->data_values->title) }}</span>
                  <h3 class="features__title">{{ __($item->data_values->heading) }}
                  </h3>
                  <div class="wyg">
                      @php
                          echo $item->data_values->description;
                      @endphp
                  </div>
                  <div class="features__btn">
                      <a href="{{route('user.form.create')}}" class="btn btn-outline--base">{{ __($item->data_values->button_name) }}</a>
                  </div>
              </div>
          </div>
      </div>
  </div>
