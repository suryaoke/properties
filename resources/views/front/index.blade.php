@extends('layouts.master')
@section('title', 'Home')
@section('content')

    <x-nav-property :about="$about" />

    <div class="site-section site-section-sm pb-0">
        <div class="container">
            <div class="row">
                <form action="{{ route('front.search') }}" class="form-search col-md-12" style="margin-top: -100px;">
                    <div class="row  align-items-end">
                        <div class="col-md-3">
                            <label for="list-types">Location</label>
                            <div class="select-wrap">
                                <span class="icon icon-arrow_drop_down"></span>
                                <select name="city" id="list-types" class="form-control d-block rounded-0" required>
                                    <option value="" hidden disabled selected>Choose your location</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="offer-types">Category</label>
                            <div class="select-wrap">
                                <span class="icon icon-arrow_drop_down"></span>
                                <select name="category" id="offer-types" class="form-control d-block rounded-0" required>
                                    <option value="" hidden disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="select-city">Type Property</label>
                            <div class="select-wrap">
                                <span class="icon icon-arrow_drop_down"></span>
                                <select name="type" id="select-city" class="form-control d-block rounded-0" required>
                                    <option value="" hidden disabled selected>Select property</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-success text-white btn-block rounded-0" value="Search">
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
                        <div class="mr-auto">
                            <a href="index.html" class="icon-view view-module active"><span
                                    class="icon-view_module"></span></a>
                            <a href="view-list.html" class="icon-view view-list"><span class="icon-view_list"></span></a>

                        </div>
                        <div class="ml-auto d-flex align-items-center">
                            <div>
                                <a href="#" class="view-list px-3 border-right active">All</a>
                                <a href="#" class="view-list px-3 border-right">Rent</a>
                                <a href="#" class="view-list px-3">Sale</a>
                            </div>


                            <div class="select-wrap">
                                <span class="icon icon-arrow_drop_down"></span>
                                <select class="form-control form-control-sm d-block rounded-0">
                                    <option value="">Sort by</option>
                                    <option value="">Price Ascending</option>
                                    <option value="">Price Descending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- // properti // --}}
    <div class="site-section site-section-sm bg-light">
        <div class="container">

            <div class="row mb-5">

                @forelse ($propertie as $properties)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="{{ route('front.details', $properties->slug) }}" class="property-entry h-100 d-block">
                            <div class="property-thumbnail">
                                <div class="offer-type-wrap">
                                    @if ($properties->status_listing === 'For Rent')
                                        <span class="offer-type bg-danger"> {{ $properties->status_listing }}</span>
                                    @elseif($properties->status_listing === 'For Sale')
                                        <span class="offer-type bg-success"> {{ $properties->status_listing }}</span>
                                    @else
                                        <span class="offer-type bg-secondary"> {{ $properties->status_listing }}</span>
                                    @endif
                                </div>
                                <img src="{{ Storage::url($properties->thumbnail ?? '') }}" alt="Image"
                                    class="img-fluid">
                            </div>
                            <div class="p-4 property-body">
                                <span class="property-favorite"><i class="icon-heart-o"></i></span>
                                <h2 class="property-title">{{ $properties->name }}</h2>
                                <span class="property-location d-block mb-3">
                                    <span class="property-icon icon-room"></span>
                                    {{ $properties->city->name }}
                                </span>
                                <strong class="property-price text-primary mb-3 d-block text-success">
                                    Rp {{ number_format($properties->price, 0, ',', '.') }}
                                </strong>
                                <ul class="property-specs-wrap mb-3 mb-lg-0">
                                    <li>
                                        <span class="property-specs">Bedroom</span>
                                        <span class="property-specs">{{ $properties->bedrooms ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Bathroom</span>
                                        <span class="property-specs"> {{ $properties->bathrooms ?? '0' }} </span>
                                    </li>

                                    <li>
                                        <span class="property-specs">Land Are</span>
                                        <span class="property-specs"> {{ $properties->land_area ?? '0' }} </span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Certificate</span>
                                        <span class="property-specs"> {{ $properties->certificate ?? '-' }} </span>
                                    </li>
                                </ul>
                            </div>
                        </a>
                    </div>
                @empty
                    <p>No propertie found.</p>
                @endforelse

            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="site-pagination">
                        <a href="#" class="active">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">4</a>
                        <a href="#">5</a>
                        <span>...</span>
                        <a href="#">10</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Why Choose Us?</h2>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto
                        error corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque,
                        deleniti cupiditate officia.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <a href="#" class="service text-center">
                        <span class="icon flaticon-house"></span>
                        <h2 class="service-heading">Research Subburbs</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex
                            odio molestia.</p>
                        <p><span class="read-more">Read More</span></p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#" class="service text-center">
                        <span class="icon flaticon-sold"></span>
                        <h2 class="service-heading">Sold Houses</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex
                            odio molestia.</p>
                        <p><span class="read-more">Read More</span></p>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#" class="service text-center">
                        <span class="icon flaticon-camera"></span>
                        <h2 class="service-heading">Security Priority</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex
                            odio molestia.</p>
                        <p><span class="read-more">Read More</span></p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Recent Blog</h2>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto
                        error corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque,
                        deleniti cupiditate officia.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">
                    <a href="#"><img src="{{ asset('images/img_4.jpg') }}" alt="Image" class="img-fluid"></a>
                    <div class="p-4 bg-white">
                        <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                        <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam
                            quae sunt.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="200">
                    <a href="#"><img src="{{ asset('images/img_2.jpg') }}" alt="Image" class="img-fluid"></a>
                    <div class="p-4 bg-white">
                        <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                        <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam
                            quae sunt.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="300">
                    <a href="#"><img src="{{ asset('images/img_3.jpg') }}" alt="Image" class="img-fluid"></a>
                    <div class="p-4 bg-white">
                        <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
                        <h2 class="h5 text-black mb-3"><a href="#">Art Gossip by Mike Charles</a></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam
                            quae sunt.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- // Agent // --}}
    <div class="site-section">
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <div class="col-md-7">
                    <div class="site-section-title text-center">
                        <h2>Our Agents</h2>
                        <p> {{ $about->deskripsi_agen }} </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($agen as $agens)
                    <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
                        <div class="team-member">

                            <img src="{{ Storage::url($agens->photo ?? '') }}" alt="Image"
                                class="img-fluid rounded mb-4">

                            <div class="text">

                                <h2 class="mb-2 font-weight-light text-black h4">{{ $agens->name }}</h2>
                                <span class="d-block mb-3 text-white-opacity-05"> {{ $agens->phone }} </span>
                                <p>{{$agens->visi}}</p>
                                <p>
                                    <a href="{{ $agens->fb }}" class="text-black p-2"><span class="icon-facebook"></span></a>
                                    <a href="{{ $agens->twitter }}" class="text-black p-2"><span class="icon-twitter"></span></a>
                                    <a href="{{ $agens->linkedin }}" class="text-black p-2"><span class="icon-linkedin"></span></a>
                                </p>
                            </div>

                        </div>
                    </div>
                @empty
                    <p>No agen.</p>
                @endforelse

            </div>
        </div>
    </div>

    <x-footer-property :about="$about" />



@endsection
