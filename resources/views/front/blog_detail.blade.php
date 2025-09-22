@extends('layouts.master')
@section('title', 'Blog')
@section('content')

    <x-nav-property :about="$about" />
    <div class="site-section site-section-sm">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div>
                        <div class="slide-one-item home-slider owl-carousel">
                            <div>
                                <img src="{{ Storage::url($blog->photo) }}" alt="Image" class="img-fluid" />
                            </div>

                        </div>
                    </div>
                    <div class="bg-white property-body border-bottom border-left border-right">
                        <div class="row mb-2">
                            <div class="col-md-6">

                                <strong class="h3 mb-3">
                                    {{ $blog->title }}</strong>
                            </div>

                        </div>
                        <p class="leading-8">{{ $blog->created_at->format('d M Y') }}</p>
                        <h2 class="h4 text-black">More Info</h2>
                        <p class="leading-8">{{ $blog->info }}</p>




                    </div>
                </div>

            </div>
        </div>
    </div>



    <x-footer-property :about="$about" />


@endsection
