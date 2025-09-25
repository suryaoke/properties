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
                            <div>
                                <a href="{{ route('front.index', array_merge(request()->except('status'), [])) }}"
                                    class="view-list px-3 border-right {{ !request('status') ? 'active' : '' }}">
                                    All
                                </a>
                                <a href="{{ route('front.index', array_merge(request()->except('status'), ['status' => 'Rent'])) }}"
                                    class="view-list px-3 border-right {{ request('status') === 'Rent' ? 'active' : '' }}">
                                    Rent
                                </a>
                                <a href="{{ route('front.index', array_merge(request()->except('status'), ['status' => 'Sale'])) }}"
                                    class="view-list px-3 {{ request('status') === 'Sale' ? 'active' : '' }}">
                                    Sale
                                </a>
                            </div>
                        </div>
                        <div class="ml-auto d-flex align-items-center">
                            <div class="select-wrap">
                                <span class="icon icon-arrow_drop_down"></span>
                                <select class="form-control form-control-sm d-block rounded-0" id="sortSelect">
                                    <option value="">Sort by</option>
                                    <option value="price_asc" {{ request('sort_by') === 'price_asc' ? 'selected' : '' }}>
                                        Harga Terendah ke Tertinggi
                                    </option>
                                    <option value="price_desc" {{ request('sort_by') === 'price_desc' ? 'selected' : '' }}>
                                        Harga Tertinggi ke Terendah
                                    </option>
                                    {{-- <option value="latest" {{ request('sort_by') === 'latest' ? 'selected' : '' }}>
                                        Latest
                                    </option> --}}
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
                                        <span class="offer-type bg-danger">{{ $properties->status_listing }}</span>
                                    @elseif($properties->status_listing === 'For Sale')
                                        <span class="offer-type bg-success">{{ $properties->status_listing }}</span>
                                    @else
                                        <span class="offer-type bg-secondary">{{ $properties->status_listing }}</span>
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
                                        <span class="property-specs">{{ $properties->bathrooms ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Land Area</span>
                                        <span class="property-specs">{{ $properties->land_area ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Certificate</span>
                                        <span class="property-specs">{{ $properties->certificate ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No properties found</h4>
                            <p class="text-muted">No active properties available at the moment</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Results Info --}}
            @if ($propertie->hasPages())
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            Showing {{ $propertie->firstItem() }} to {{ $propertie->lastItem() }}
                            of {{ $propertie->total() }} properties
                        </p>
                    </div>
                </div>
            @endif

            {{-- Custom Pagination --}}
            @if ($propertie->hasPages())
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="site-pagination">
                            @php
                                $currentPage = $propertie->currentPage();
                                $lastPage = $propertie->lastPage();
                                $showRange = 2; // Jumlah halaman yang ditampilkan di kiri dan kanan halaman aktif
                            @endphp

                            {{-- Previous Page --}}
                            @if (!$propertie->onFirstPage())
                                <a href="{{ $propertie->previousPageUrl() }}">&laquo;</a>
                            @endif

                            {{-- First Page --}}
                            @if ($currentPage > $showRange + 2)
                                <a href="{{ $propertie->url(1) }}">1</a>
                                @if ($currentPage > $showRange + 3)
                                    <span>...</span>
                                @endif
                            @endif

                            {{-- Pages around current page --}}
                            @for ($i = max(1, $currentPage - $showRange); $i <= min($lastPage, $currentPage + $showRange); $i++)
                                @if ($i == $currentPage)
                                    <a href="#" class="active">{{ $i }}</a>
                                @else
                                    <a href="{{ $propertie->url($i) }}">{{ $i }}</a>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if ($currentPage < $lastPage - $showRange - 1)
                                @if ($currentPage < $lastPage - $showRange - 2)
                                    <span>...</span>
                                @endif
                                <a href="{{ $propertie->url($lastPage) }}">{{ $lastPage }}</a>
                            @endif

                            {{-- Next Page --}}
                            @if ($propertie->hasMorePages())
                                <a href="{{ $propertie->nextPageUrl() }}">&raquo;</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Why Choose Us?</h2>
                    </div>
                    <p> {{ $about->keterangan_why }} </p>
                </div>
            </div>

             <div class="row">
                @forelse ($why as $whys)
                    <div class="col-md-6 col-lg-4 service text-center">
                        <span class="icon">
                            <img src="{{ Storage::url($whys->photo ?? '') }}" 
                                alt="logo"
                                style="width:60px; height:60px; object-fit:contain;">
                        </span>
                        <h2 class="service-heading"> {{$whys->title}} </h2>
                        <p> {{$whys->info}} </p>
                    </div>
                @empty
                    <p>No Data</p>
                @endforelse
            </div>

        </div>
    </div>

    {{-- blog --}}

    <div class="site-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Recent Blog</h2>
                    </div>
                    <p> {{ $about->keterangan_blog }} </p>
                </div>
            </div>
            <div class="row">

                @forelse ($blog as $blogs)
                    @php

                        $shortDescription = Illuminate\Support\Str::words($blogs->info, 15, '...');
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">

                        <img src="{{ Storage::url($blogs->photo ?? '') }}" alt="Image" class="img-fluid">
                        <div class="p-4 bg-white ">
                            <span
                                class="d-block text-secondary small text-uppercase">{{ $blogs->created_at->format('d M Y') }}</span>
                            <h2 class="h5 text-black mb-3">{{ $blogs->title }}</h2>
                            @if (Str::wordCount($blogs->info) > 15)
                                <p>{{ $shortDescription }}</p>
                                <a href="{{ route('front.blog', $blogs->slug) }}" class="text-center">
                                    <p><span class="read-more">Read More</span></p>
                                </a>
                            @else
                                <p>{{ $blogs->info }}</p>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No blog found</h4>
                            <p class="text-muted">No blog posts available at the moment</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Blog Results Info --}}
            @if ($blog->hasPages())
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            Showing {{ $blog->firstItem() }} to {{ $blog->lastItem() }}
                            of {{ $blog->total() }} blog posts
                        </p>
                    </div>
                </div>
            @endif

            {{-- Blog Pagination --}}
            @if ($blog->hasPages())
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="site-pagination">
                            @php
                                $currentPage = $blog->currentPage();
                                $lastPage = $blog->lastPage();
                                $showRange = 2; // Jumlah halaman yang ditampilkan di kiri dan kanan halaman aktif
                            @endphp

                            {{-- Previous Page --}}
                            @if (!$blog->onFirstPage())
                                <a href="{{ $blog->previousPageUrl() }}">&laquo;</a>
                            @endif

                            {{-- First Page --}}
                            @if ($currentPage > $showRange + 2)
                                <a href="{{ $blog->url(1) }}">1</a>
                                @if ($currentPage > $showRange + 3)
                                    <span>...</span>
                                @endif
                            @endif

                            {{-- Pages around current page --}}
                            @for ($i = max(1, $currentPage - $showRange); $i <= min($lastPage, $currentPage + $showRange); $i++)
                                @if ($i == $currentPage)
                                    <a href="#" class="active">{{ $i }}</a>
                                @else
                                    <a href="{{ $blog->url($i) }}">{{ $i }}</a>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if ($currentPage < $lastPage - $showRange - 1)
                                @if ($currentPage < $lastPage - $showRange - 2)
                                    <span>...</span>
                                @endif
                                <a href="{{ $blog->url($lastPage) }}">{{ $lastPage }}</a>
                            @endif

                            {{-- Next Page --}}
                            @if ($blog->hasMorePages())
                                <a href="{{ $blog->nextPageUrl() }}">&raquo;</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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
                                <p>{{ $agens->visi }}</p>
                                <p>
                                    <a href="{{ $agens->fb }}" class="text-black p-2"><span
                                            class="icon-facebook"></span></a>
                                    <a href="{{ $agens->twitter }}" class="text-black p-2"><span
                                            class="icon-twitter"></span></a>
                                    <a href="{{ $agens->linkedin }}" class="text-black p-2"><span
                                            class="icon-linkedin"></span></a>
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterLinks = document.querySelectorAll('.view-list');

            filterLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all links
                    filterLinks.forEach(l => l.classList.remove('active'));

                    // Add active class to clicked link
                    this.classList.add('active');

                    // Get filter value
                    const url = new URL(this.href);
                    const status = url.searchParams.get('status');

                    // Update URL without reload
                    const newUrl = status ?
                        `${window.location.pathname}?status=${status}` :
                        window.location.pathname;

                    window.history.pushState({}, '', newUrl);

                    // Here you can add AJAX call to load filtered properties
                    // filterProperties(status);
                });
            });
        });
        // Force navigation - letakkan di akhir body sebelum </body>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const filterLinks = document.querySelectorAll('a[href*="status="], a.view-list');
                filterLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.stopImmediatePropagation();
                        window.location.href = this.href;
                    }, true);
                });
            }, 1000); // Delay untuk memastikan script lain sudah jalan
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortSelect = document.getElementById('sortSelect');

            // Handle sort dropdown change
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const currentParams = new URLSearchParams(window.location.search);

                // Remove existing sort_by parameter
                currentParams.delete('sort_by');

                // Add new sort_by parameter if value is not empty
                if (sortValue && sortValue !== '') {
                    currentParams.set('sort_by', sortValue);
                }

                // Build new URL
                const newUrl = window.location.pathname + '?' + currentParams.toString();

                // Navigate to new URL
                window.location.href = newUrl.replace('?&', '?').replace(/\?$/, '');
            });
        });
    </script>
@endsection
