@extends('layouts.master')
@section('title', 'Home')
@section('content')

    <x-nav-property :about="$about" />

    <div class="site-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center">
                    <div class="site-section-title">
                        <h2>Recent Blog</h2>
                    </div>
                    <p> p</p>
                </div>
            </div>
            <div class="row">
                @forelse ($blog as $blogs)
                    <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{ route('front.blog', $blogs->slug) }}">
                            <img src="{{ Storage::url($blogs->photo ?? '') }}" alt="Image" class="img-fluid">
                            <div class="p-4 bg-white">
                                <span
                                    class="d-block text-secondary small text-uppercase">{{ $blogs->created_at->format('d M Y') }}</span>
                                <h2 class="h5 text-black mb-3">{{ $blogs->title }}</h2>
                                <p>{{ $blogs->info }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p>No blog found.</p>
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

    <x-footer-property :about="$about" />



@endsection
