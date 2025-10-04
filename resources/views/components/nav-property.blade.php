     <style>
         .btn-iklan {
             display: inline-block;
             font-weight: bold;
             animation: bounce 1s infinite;
             height: 40px;
             line-height: 40px;
             /* teks biar pas di tengah */
             padding: 0 15px;
             /* spasi kanan-kiri */
             border-radius: 6px;
             /* biar tombol lebih halus */
             text-align: center;
             /* teks rata tengah */
             cursor: pointer;
         }

         @keyframes bounce {

             0%,
             100% {
                 transform: translateY(0);
             }

             50% {
                 transform: translateY(-8px);
             }
         }
     </style>
     <div class="site-loader"></div>

     <div class="site-wrap">

         <div class="site-mobile-menu">
             <div class="site-mobile-menu-header">
                 <div class="site-mobile-menu-close mt-3">
                     <span class="icon-close2 js-menu-toggle"></span>
                 </div>
             </div>
             <div class="site-mobile-menu-body"></div>
         </div>

         <div class="site-navbar mt-4">
             <div class="container py-1">
                 <div class="row align-items-center">
                     <div class="col-8 col-md-8 col-lg-4">
                         <h1 class="mb-0"><a href="{{ route('front.index') }}" class="text-white h2 mb-0"><img
                                     src="{{ Storage::url($about->photo ?? '') }}" alt="logo"
                                     class="w-10 h-10 rounded-lg"><strong>{{ $about->title ?? '' }}<span
                                         class="text-danger">.</span></strong></a></h1>
                     </div>
                     <div class="col-4 col-md-4 col-lg-8">
                         <nav class="site-navigation text-right text-md-right" role="navigation">

                             <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#"
                                     class="site-menu-toggle js-menu-toggle text-white"><span
                                         class="icon-menu h3"></span></a></div>

                             <ul class="site-menu js-clone-nav d-none d-lg-block">
                                 <li class="active">
                                     <a href="{{ route('front.index') }}">Home</a>
                                 </li>
                                 <li><a href="{{ route('front.blog.all') }}">Blog</a></li>
                                 <li><a href="{{ route('front.about') }}">About</a></li>
                                 <li><a href="{{ route('front.contact') }}">Contact</a></li>
                                 <li>
                                     <a href="{{ route('front.iklan') }}">
                                         <span
                                             class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded btn-iklan">
                                             Pasang Iklan
                                         </span>
                                     </a>
                                 </li>

                             </ul>
                         </nav>
                     </div>


                 </div>
             </div>
         </div>
     </div>

     <div class="slide-one-item home-slider owl-carousel">

         @php
             $properties = App\Models\Property::where('status_active', 'Active')->where('status_terjual', null)->limit('2')->get();
         @endphp

         @forelse ($properties as $propertie)
             <div class="site-blocks-cover overlay"
                 style="background-image: url('{{ $propertie->thumbnail ? Storage::url($propertie->thumbnail) : asset('images/hero_bg_1.jpg') }}');"
                 data-aos="fade" data-stellar-background-ratio="0.5">

                 <div class="container">
                     <div class="row align-items-center justify-content-center text-center">
                         <div class="col-md-10">
                             @if ($propertie->status_terjual !== null)
                                 @if ($propertie->status_listing === 'For Sale')
                                     <span
                                         class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">
                                         Sold
                                     </span>
                                 @elseif ($propertie->status_listing === 'For Rent')
                                     <span
                                         class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">
                                         Rented
                                     </span>
                                 @else
                                     <span
                                         class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">
                                         Unavailable
                                     </span>
                                 @endif
                             @else
                                 @if ($propertie->status_listing === 'For Rent')
                                     <span
                                         class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">
                                         {{ $propertie->status_listing }}
                                     </span>
                                 @elseif ($propertie->status_listing === 'For Sale')
                                     <span
                                         class="d-inline-block bg-success text-white px-3 mb-3 property-offer-type rounded">
                                         {{ $propertie->status_listing }}
                                     </span>
                                 @else
                                     <span
                                         class="d-inline-block bg-secondary text-white px-3 mb-3 property-offer-type rounded">
                                         {{ $propertie->status_listing }}
                                     </span>
                                 @endif
                             @endif


                             <h1 class="mb-2">{{ $propertie->name }}</h1>
                             <p class="mb-5">
                                 <strong class="h2 text-success font-weight-bold">
                                     Rp {{ number_format($propertie->price, 0, ',', '.') }}
                                 </strong>
                             </p>
                             <p>
                                 <a href="{{ route('front.details', $propertie->slug) }}"
                                     class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">
                                     See Details
                                 </a>
                             </p>
                         </div>
                     </div>
                 </div>
             </div>
         @empty
             <p>No properties found.</p>
         @endforelse


     </div>
