@extends('layouts.master')
@section('title', 'Detail Property')
@section('content')

    <style>
        /* Efek redup untuk property yang sudah terjual */
        .property-sold {
            opacity: 0.6;
            position: relative;
        }

        .property-sold::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.1);
            pointer-events: none;
            border-radius: inherit;
        }

        .property-sold:hover {
            opacity: 0.7;
        }

        /* Alternatif: Efek grayscale */
        .property-sold img {
            filter: grayscale(50%);
        }

        .property-sold:hover img {
            filter: grayscale(30%);
        }
    </style>
    <x-nav-property :about="$about" />

    <div class="site-section site-section-sm">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div>
                        <div class="slide-one-item home-slider owl-carousel">
                            <div>
                                <img src="{{ Storage::url($property->thumbnail) }}" alt="Image" class="img-fluid" />
                            </div>
                            @foreach ($property->photos as $photo)
                                <div>
                                    <img src="{{ Storage::url($photo->photo) }}" alt="Image" class="img-fluid" />
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="bg-white property-body border-bottom border-left border-right">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="offer-type-wrap">
                                   @if ($property->status_terjual !== null)
                                        @if ($property->status_listing === 'For Sale')
                                            <span class="offer-type bg-danger">Sold</span>
                                        @elseif ($property->status_listing === 'For Rent')
                                            <span class="offer-type bg-danger">Rented</span>
                                        @else
                                            <span class="offer-type bg-danger">Unavailable</span>
                                        @endif
                                    @else
                                        @if ($property->status_listing === 'For Rent')
                                            <span class="offer-type bg-danger">{{ $property->status_listing }}</span>
                                        @elseif ($property->status_listing === 'For Sale')
                                            <span class="offer-type bg-success">{{ $property->status_listing }}</span>
                                        @else
                                            <span class="offer-type bg-secondary">{{ $property->status_listing }}</span>
                                        @endif
                                    @endif
                                </div>
                                <strong class="h3 mb-3">
                                    {{ $property->name }}</strong> <br>
                                <strong class="text-success h3 mb-3">Rp
                                    {{ number_format($property->price, 0, ',', '.') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <ul class="property-specs-wrap mb-3 mb-lg-0 float-lg-right">
                                    <li>
                                        <span class="property-specs">Bedroom</span>
                                        <span class="property-specs-number">{{ $property->bedrooms ?? '0' }}
                                            <sup>+</sup></span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Bathroom</span>
                                        <span class="property-specs-number">{{ $property->bathrooms ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Land Area</span>
                                        <span class="property-specs-number">{{ $property->land_area ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Certificate</span>
                                        <span class="property-specs-number"> {{ $property->certificate ?? '-' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Location</span>
                                        <span class="property-icon icon-room"> {{ $property->city->name ?? '-' }}
                                            City</span>

                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="row mb-5">
                            @foreach ($property->facilities as $facility)
                                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">

                                    <span class="d-inline-block text-black mb-0 caption-text"> <img
                                            src="{{ Storage::url($facility->facility->photo) }}"
                                            class="size-8 flex shrink-0" alt="icon"></span>
                                    <strong class="d-block">{{ $facility->facility->name }}</strong>
                                </div>
                            @endforeach
                        </div>
                        <h2 class="h4 text-black">More Info</h2>
                        <p class="leading-8">{{ $property->about }}</p>

                        <div class="row no-gutters mt-5">
                            <div class="col-12">
                                <h2 class="h4 text-black mb-3">Gallery</h2>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <a href="{{ Storage::url($property->thumbnail) }}" class="image-popup gal-item"><img
                                        src="{{ Storage::url($property->thumbnail) }}" alt="Image"
                                        class="img-fluid" /></a>
                            </div>
                            @foreach ($property->photos as $photo)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <a href="{{ Storage::url($photo->photo) }}" class="image-popup gal-item"><img
                                            src="{{ Storage::url($photo->photo) }}" alt="Image" class="img-fluid" /></a>
                                </div>
                            @endforeach

                        </div>


                        <div class="row no-gutters mt-5">
                            <div class="col-12">
                                <h2 class="h4 text-black mb-3">Strategic Location</h2>
                            </div>
                            <div class="col-12">
                                <div class="map-container" style="height: 300px; width: 100%; position: relative;">
                                    <iframe class="w-100 h-100 border-0" frameborder="0"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                        src="{{ $property->map }}" allowfullscreen>
                                    </iframe>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-white widget border rounded">
                        <h3 class="h4 text-black widget-title mb-3">Contact Agent</h3>
                        <img src="{{ Storage::url($agen->photo) }}" style="width: 50px; height: 50px;" alt="photo">
                        <p class="leading-8">{{ $agen->name }} <br> 📞 {{ $agen->phone }} </p>
                        <form method="POST" action="{{ route('front.customer.store') }}" class="form-contact-agent">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                            <input type="hidden" name="agen_phone" value="{{ $agen->phone }}">
                            <div class="form-group">
                                <input type="text" placeholder="Your Name *" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') ring-2 ring-red-500 @enderror" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1 px-4">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="tel" placeholder="Your Phone *" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone') ring-2 ring-red-500 @enderror" required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1 px-4">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email Address*" required name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') ring-2 ring-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1 px-4">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input value="{{ $property->name }}" readonly class="form-control bg-gray-100" />
                            </div>
                            <div class="form-group">
                                <textarea name="message" placeholder="Message*" rows="4" required
                                    class="form-control resize-none @error('message') ring-2 ring-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1 px-4">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="submit" id="phone" class="btn btn-primary" value="Send Message" />
                            </div>
                        </form>
                    </div>

                    <div class="bg-white widget border rounded">
                        <h3 class="h4 text-black widget-title mb-3">Paragraph</h3>
                        <p>
                            {{ $property->paragraph }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section site-section-sm bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="site-section-title mb-5">
                        <h2>Related Properties</h2>
                    </div>
                </div>
            </div>


            <div class="row mb-5">
                @forelse ($propertyRelated as $propertyRelateds)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="{{ route('front.details', $propertyRelateds->slug) }}"
                            class="property-entry h-100 d-block {{ $propertyRelateds->status_terjual !== null ? 'property-sold' : '' }}">
                            <div class="property-thumbnail">
                                <div class="offer-type-wrap">
                                     @if ($propertyRelateds->status_terjual !== null)
                                        @if ($propertyRelateds->status_listing === 'For Sale')
                                            <span class="offer-type bg-danger">Sold</span>
                                        @elseif ($propertyRelateds->status_listing === 'For Rent')
                                            <span class="offer-type bg-danger">Rented</span>
                                        @else
                                            <span class="offer-type bg-danger">Unavailable</span>
                                        @endif
                                    @else
                                        @if ($propertyRelateds->status_listing === 'For Rent')
                                            <span class="offer-type bg-danger">{{ $propertyRelateds->status_listing }}</span>
                                        @elseif ($propertyRelateds->status_listing === 'For Sale')
                                            <span class="offer-type bg-success">{{ $propertyRelateds->status_listing }}</span>
                                        @else
                                            <span class="offer-type bg-secondary">{{ $propertyRelateds->status_listing }}</span>
                                        @endif
                                    @endif
                                </div>
                                <img src="{{ Storage::url($propertyRelateds->thumbnail ?? '') }}" alt="Image"
                                    class="img-fluid">
                            </div>
                            <div class="p-4 property-body">
                                <span class="property-favorite"><i class="icon-heart-o"></i></span>
                                <h2 class="property-title">{{ $propertyRelateds->name }}</h2>
                                <span class="property-location d-block mb-3">
                                    <span class="property-icon icon-room"></span>
                                    {{ $propertyRelateds->city->name }}
                                </span>
                                <strong class="property-price text-primary mb-3 d-block text-success">
                                    Rp {{ number_format($propertyRelateds->price, 0, ',', '.') }}
                                </strong>
                                <ul class="property-specs-wrap mb-3 mb-lg-0">
                                    <li>
                                        <span class="property-specs">Bedroom</span>
                                        <span class="property-specs">{{ $propertyRelateds->bedrooms ?? '0' }}</span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Bathroom</span>
                                        <span class="property-specs"> {{ $propertyRelateds->bathrooms ?? '0' }} </span>
                                    </li>

                                    <li>
                                        <span class="property-specs">Land Are</span>
                                        <span class="property-specs"> {{ $propertyRelateds->land_area ?? '0' }} </span>
                                    </li>
                                    <li>
                                        <span class="property-specs">Certificate</span>
                                        <span class="property-specs"> {{ $propertyRelateds->certificate ?? '-' }} </span>
                                    </li>
                                </ul>
                            </div>
                        </a>
                    </div>

                @empty
                    <p>No propertie found.</p>
                @endforelse

            </div>
        </div>



    </div>

    <x-footer-property :about="$about" />

    <!-- Modal HTML -->
    <div id="successModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon">
                    <svg width="50" height="50" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#10B981" />
                        <path d="M16 30L26 40L44 22" stroke="white" stroke-width="4" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="modal-title">Berhasil!</h3>
                <p class="modal-message">Pesan berhasil dikirim, kami akan menghubungi kembali untuk konfirmasi</p>
                <button id="modalOkBtn" class="modal-btn">OK</button>
            </div>
        </div>
    </div>

    <!-- CSS untuk Modal -->
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        .modal-body {
            padding: 30px 25px;
            text-align: center;
        }

        .modal-icon {
            margin: 0 auto 15px;
            width: 50px;
            height: 50px;
        }

        .modal-icon svg {
            width: 50px;
            height: 50px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 10px 0;
        }

        .modal-message {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
            margin: 0 0 20px 0;
        }

        .modal-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 35px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 180px;
        }

        .modal-btn:hover {
            background-color: #1d4ed8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.form-contact-agent');
            const modal = document.getElementById('successModal');
            const okBtn = document.getElementById('modalOkBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Ambil data form
                const formData = new FormData(form);

                // Kirim data menggunakan fetch API
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tampilkan modal
                            modal.style.display = 'flex';
                        } else {
                            // Handle error jika diperlukan
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        // Jika terjadi error, tetap tampilkan modal (opsional)
                        modal.style.display = 'flex';
                    });
            });

            // Event listener untuk tombol OK
            okBtn.addEventListener('click', function() {
                // Refresh halaman
                window.location.reload();
            });

            // Tutup modal jika klik di luar modal content
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
