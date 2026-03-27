   @php
       $blogSectionContent = getContent('blog.content', true);
           $blogs = getContent('blog.element', false, 3, false);
   @endphp
   <section class="blog section-bg-2 my-120">
       <div class="row">
           <div class="col-lg-12">
               <div class="section-heading">
                   <span class="section-heading__name">{{__($blogSectionContent->data_values->title)}}</span>
                   <h2 class="section-heading__title">{{__($blogSectionContent->data_values->heading)}}</h2>
                   <p class="section-heading__desc">{{__($blogSectionContent->data_values->subheading)}}</p>
               </div>
           </div>
       </div>
       <div class="container">
           <div class="row justify-content-center g-4">
               @include($activeTemplate . 'components.blog')
           </div>
       </div>
   </section>
  
