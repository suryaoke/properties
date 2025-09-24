@extends('layouts.master')
@section('title', 'Home')
@section('content')

    <x-nav-property :about="$about" />

    {{-- // blog // --}}
    <div class="site-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Recent Blog</h2>
                    </div>
                    <p>p</p>
                </div>
            </div>

            <div class="row">
                @forelse ($blog as $blogs)
                    @php
                        $shortDescription = Illuminate\Support\Str::words($blogs->info, 15, '...');
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{ route('front.blog', $blogs->slug) }}">
                            <img src="{{ Storage::url($blogs->photo ?? '') }}" alt="Image" class="img-fluid">
                            <div class="p-4 bg-white">
                                <span class="d-block text-secondary small text-uppercase">
                                    {{ $blogs->created_at->format('d M Y') }}
                                </span>
                                <h2 class="h5 text-black mb-3">{{ $blogs->title }}</h2>
                                @if (Str::wordCount($blogs->info) > 15)
                                    <p>{{ $shortDescription }} <a
                                            href="{{ route('front.blog', $blogs->slug) }}">Selengkapnya</a></p>
                                @else
                                    <p>{{ $blogs->info }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No blog found</h4>
                            <p class="text-muted">No active blog available at the moment</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Results Info --}}
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

            {{-- Custom Pagination --}}
            @if ($blog->hasPages())
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="site-pagination">
                            @php
                                $currentPage = $blog->currentPage();
                                $lastPage = $blog->lastPage();
                                $showRange = 2;
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


    <x-footer-property :about="$about" />



@endsection
