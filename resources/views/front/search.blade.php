@extends('layouts.master')
@section('title', 'Search Property')
@section('content')
       <x-nav-property :about="$about" />

        <div class="mt-[164px] flex flex-col gap-[6px] text-center items-center">
            <h1 class="font-bold text-4xl leading-[54px]">{{ $category->name }} in {{ $city->name }} City</h1>
            <div class="flex items-center gap-[6px]">
                <img src="assets/images/icons/building-3.svg" class="size-6 flex shrink-0" alt="icon">
                <p class="font-semibold">Available {{ $property->count() }} {{ $type->name }} Properties</p>
            </div>
        </div>
        <main class="grid grid-cols-3 w-full max-w-[1280px] px-[75px] gap-[30px] mx-auto my-[50px]">
            @forelse ($property as $item)
                <a href="{{ route('front.details', $item->slug) }}" class="card">
                    <div class="flex flex-col rounded-[30px] ring-1 ring-tedja-border p-[10px] pb-5 gap-3 bg-white hover:ring-2 hover:ring-tedja-blue transition-all duration-300">
                        <div class="thumbnail-container relative w-full h-[240px] rounded-[30px] overflow-hidden">
                            <img src="{{ Storage::url($item->thumbnail) }}" class="w-full h-full object-cover" alt="thumbnail">
                            <button class="absolute top-5 right-5">
                                <img src="assets/images/icons/heart-white-fill.svg" class="size-[50px] flex shrink-0" alt="icon whishlist">
                            </button>
                        </div>
                        <div class="flex flex-col gap-[18px] px-[10px]">
                            <div class="flex flex-col gap-[6px]">
                                <h3 class="font-bold text-lg">{{ $item->name }}</h3>
                                <div class="flex items-center gap-[6px]">
                                    <img src="assets/images/icons/location.svg" class="size-5 flex shrink-0" alt="icon">
                                    <p class="font-semibold text-sm">{{ $item->city->name }}</p>
                                </div>
                            </div>
                            <hr class="border-tedja-border">
                            <div class="grid grid-cols-2 gap-y-[18px] gap-x-3">
                                <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                                    <img src="assets/images/icons/slider-vertical.svg" class="size-5 flex shrink-0" alt="icon">
                                    <p class="font-semibold text-sm">8 Bedroom</p>
                                </div>
                                <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                                    <img src="assets/images/icons/slider-horizontal.svg" class="size-5 flex shrink-0" alt="icon">
                                    <p class="font-semibold text-sm">2 Bathroom</p>
                                </div>
                                <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                                    <img src="assets/images/icons/note-favorite.svg" class="size-5 flex shrink-0" alt="icon">
                                    <p class="font-semibold text-sm">SHGB</p>
                                </div>
                                <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                                    <img src="assets/images/icons/maximize-3.svg" class="size-5 flex shrink-0" alt="icon">
                                    <p class="font-semibold text-sm">320 M²</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                Beluem ada properti sesuai kriteria pencarian Anda.
            @endforelse

        </main>
@endsection
