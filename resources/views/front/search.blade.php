@extends('layouts.master')
@section('title', 'Search Property')
@section('content')
    <x-nav-property :about="$about" />

    <div class="site-section site-section-sm bg-light">
        <div class="container">

            <div class="row mb-5">
                @forelse ($property as $properties)
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
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No properties found</h4>
                            <p class="text-muted">Try adjusting your search filters</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Results Info --}}
            @if ($property->hasPages())
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            Showing {{ $property->firstItem() }} to {{ $property->lastItem() }}
                            of {{ $property->total() }} results
                        </p>
                    </div>
                </div>
            @endif

            {{-- Custom Pagination --}}
            @if ($property->hasPages())
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="site-pagination">
                            @php
                                $currentPage = $property->currentPage();
                                $lastPage = $property->lastPage();
                                $showRange = 2; // Jumlah halaman yang ditampilkan di kiri dan kanan halaman aktif
                            @endphp

                            {{-- Previous Page --}}
                            @if (!$property->onFirstPage())
                                <a href="{{ $property->previousPageUrl() }}">&laquo;</a>
                            @endif

                            {{-- First Page --}}
                            @if ($currentPage > $showRange + 2)
                                <a href="{{ $property->url(1) }}">1</a>
                                @if ($currentPage > $showRange + 3)
                                    <span>...</span>
                                @endif
                            @endif

                            {{-- Pages around current page --}}
                            @for ($i = max(1, $currentPage - $showRange); $i <= min($lastPage, $currentPage + $showRange); $i++)
                                @if ($i == $currentPage)
                                    <a href="#" class="active">{{ $i }}</a>
                                @else
                                    <a href="{{ $property->url($i) }}">{{ $i }}</a>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if ($currentPage < $lastPage - $showRange - 1)
                                @if ($currentPage < $lastPage - $showRange - 2)
                                    <span>...</span>
                                @endif
                                <a href="{{ $property->url($lastPage) }}">{{ $lastPage }}</a>
                            @endif

                            {{-- Next Page --}}
                            @if ($property->hasMorePages())
                                <a href="{{ $property->nextPageUrl() }}">&raquo;</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <x-footer-property :about="$about" />
@endsection
