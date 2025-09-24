@extends('layouts.master')
@section('title', 'Detail Property')
@section('content')
    <div id="notification-container" class="notification-container">
        @if (session('success'))
            <div class="alert alert-success notification-alert" id="success-notification">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger notification-alert" id="error-notification">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

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
                                    @if ($property->status_listing === 'For Rent')
                                        <span class="offer-type bg-danger"> {{ $property->status_listing }}</span>
                                    @elseif($property->status_listing === 'For Sale')
                                        <span class="offer-type bg-success">
                                            {{ $property->status_listing }}</span>
                                    @else
                                        <span class="offer-type bg-secondary">
                                            {{ $property->status_listing }}</span>
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
                                <input type="tel" placeholder="Your Phone *" name="phone"
                                    value="{{ old('phone') }}"
                                    class="form-control @error('phone') ring-2 ring-red-500 @enderror" required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1 px-4">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email" name="email" value="{{ old('email') }}"
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
                            class="property-entry h-100 d-block">
                            <div class="property-thumbnail">
                                <div class="offer-type-wrap">
                                    @if ($propertyRelateds->status_listing === 'For Rent')
                                        <span class="offer-type bg-danger"> {{ $propertyRelateds->status_listing }}</span>
                                    @elseif($propertyRelateds->status_listing === 'For Sale')
                                        <span class="offer-type bg-success">
                                            {{ $propertyRelateds->status_listing }}</span>
                                    @else
                                        <span class="offer-type bg-secondary">
                                            {{ $propertyRelateds->status_listing }}</span>
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
    <style>
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 350px;
        }

        .notification-alert {
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            animation: slideInRight 0.3s ease-out;
            position: relative;
            overflow: hidden;
        }

        .notification-alert.alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .notification-alert.alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .notification-alert::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #28a745;
            animation: progressBar 3s linear;
        }

        .notification-alert.alert-danger::before {
            background: #dc3545;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .notification-fade-out {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .notification-container {
                width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }

            .notification-alert {
                font-size: 13px;
                padding: 10px 14px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto hide notifications after 3 seconds
            const notifications = document.querySelectorAll('.notification-alert');

            notifications.forEach(function(notification) {
                // Add click to close functionality
                notification.style.cursor = 'pointer';
                notification.addEventListener('click', function() {
                    hideNotification(this);
                });

                // Auto hide after 3 seconds
                setTimeout(function() {
                    hideNotification(notification);
                }, 3000);
            });

            function hideNotification(element) {
                element.classList.add('notification-fade-out');
                setTimeout(function() {
                    if (element && element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                }, 300);
            }
        });
    </script>

@endsection
