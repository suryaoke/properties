   @php
       use Illuminate\Support\Str;
       $shortDescription = Str::words($about->description, 10, '...');
   @endphp
   <footer class="site-footer">
       <div class="container">
           <div class="row">
               <div class="col-lg-4">
                   <div class="mb-5">
                       <h3 class="footer-heading mb-4">{{ $about->title }}</h3>
                       @if (Str::wordCount($about->description) > 10)
                           <p>{{ $shortDescription }} <a href="{{ route('front.about') }}">Selengkapnya</a></p>
                       @else
                           <p>{{ $about->description }}</p>
                       @endif
                       <p> {{ $about->address }} </p>
                       <p>{{ $about->phone }}</p>
                   </div>



               </div>
               <div class="col-lg-4 mb-5 mb-lg-0">
                   <div class="row mb-5">
                       <div class="col-md-12">
                           <h3 class="footer-heading mb-4">Navigations</h3>
                       </div>
                       <div class="col-md-6 col-lg-6">
                           <ul class="list-unstyled">
                               <li><a href="{{ route('front.index') }}">Home</a></li>
                               <li><a href="{{ route('front.blog.all') }}">Blog</a></li>
                               <li><a href="{{ route('front.about') }}">About</a></li>
                               <li><a href="{{ route('front.contact') }}">Contact</a></li>
                           </ul>
                       </div>

                   </div>


               </div>

               <div class="col-lg-4 mb-5 mb-lg-0">
                   <h3 class="footer-heading mb-4">Follow Us</h3>

                   <div>
                       <a href="{{ $about->fb }}" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                       <a href="{{ $about->twitter }}" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                       <a href="{{ $about->instagram }}" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                       <a href="{{ $about->linkedin }}" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
                   </div>



               </div>

           </div>

       </div>
   </footer>
