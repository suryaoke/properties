@extends('layouts.master')
@section('title', 'Buat Iklan Property')
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
                    <form method="POST" action="{{ route('iklan.store') }}" class="p-5 bg-white border"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <h4 class="text-black mb-4">Informasi Dasar Properti</h4>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="text-black">Nama Properti <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama Properti"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="text-black">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" id="price" name="price"
                                        class="form-control @error('price') is-invalid @enderror" placeholder="0"
                                        value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status_listing" class="text-black">Status Listing <span
                                        class="text-danger">*</span></label>
                                <select name="status_listing" id="status_listing"
                                    class="form-control @error('status_listing') is-invalid @enderror" required>
                                    <option value="" hidden disabled selected>Pilih Status</option>
                                    <option value="For Sale" {{ old('status_listing') == 'For Sale' ? 'selected' : '' }}>For
                                        Sale</option>
                                    <option value="For Rent" {{ old('status_listing') == 'For Rent' ? 'selected' : '' }}>For
                                        Rent</option>
                                </select>
                                @error('status_listing')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 mb-3">
                                <label for="property_type_id" class="text-black">Tipe Properti <span
                                        class="text-danger">*</span></label>
                                <select name="property_type_id" id="property_type_id"
                                    class="form-control @error('property_type_id') is-invalid @enderror" required>
                                    <option value="" hidden disabled selected>Pilih Tipe Properti</option>
                                    @foreach ($propertyTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="text-black">Kategori <span
                                        class="text-danger">*</span></label>
                                <select name="category_id" id="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="" hidden disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city_id" class="text-black">Kota <span class="text-danger">*</span></label>
                                <select name="city_id" id="city_id"
                                    class="form-control @error('city_id') is-invalid @enderror" required>
                                    <option value="" hidden disabled selected>Pilih Kota</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="text-black">Alamat <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" cols="30" rows="3"
                                    class="form-control @error('address') is-invalid @enderror" placeholder="Alamat lengkap properti" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="about" class="text-black">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea name="about" id="about" cols="30" rows="4"
                                    class="form-control @error('about') is-invalid @enderror" placeholder="Deskripsi properti" required>{{ old('about') }}</textarea>
                                @error('about')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="paragraph" class="text-black">Paragraf Tambahan</label>
                                <textarea name="paragraph" id="paragraph" cols="30" rows="3"
                                    class="form-control @error('paragraph') is-invalid @enderror" placeholder="Informasi tambahan (opsional)">{{ old('paragraph') }}</textarea>
                                @error('paragraph')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <h4 class="text-black mb-4 mt-5">Informasi Tambahan</h4>

                        <div class="row form-group">
                            <div class="col-md-4 mb-3">
                                <label for="certificate" class="text-black">Sertifikat</label>
                                <select name="certificate" id="certificate"
                                    class="form-control @error('certificate') is-invalid @enderror">
                                    <option value="" hidden disabled selected>Pilih Sertifikat</option>
                                    <option value="SHM" {{ old('certificate') == 'SHM' ? 'selected' : '' }}>SHM
                                    </option>
                                    <option value="HGB" {{ old('certificate') == 'HGB' ? 'selected' : '' }}>HGB
                                    </option>
                                    <option value="IMB" {{ old('certificate') == 'IMB' ? 'selected' : '' }}>IMB
                                    </option>
                                    <option value="Lainnya" {{ old('certificate') == 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                                @error('certificate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bedrooms" class="text-black">Kamar Tidur</label>
                                <div class="input-group">
                                    <input type="number" id="bedrooms" name="bedrooms" min="0"
                                        class="form-control @error('bedrooms') is-invalid @enderror" placeholder="0"
                                        value="{{ old('bedrooms') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Unit</span>
                                    </div>
                                </div>
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bathrooms" class="text-black">Kamar Mandi</label>
                                <div class="input-group">
                                    <input type="number" id="bathrooms" name="bathrooms" min="0"
                                        class="form-control @error('bathrooms') is-invalid @enderror" placeholder="0"
                                        value="{{ old('bathrooms') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Unit</span>
                                    </div>
                                </div>
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 mb-3">
                                <label for="electric" class="text-black">Daya Listrik</label>
                                <div class="input-group">
                                    <input type="number" id="electric" name="electric" min="0"
                                        class="form-control @error('electric') is-invalid @enderror" placeholder="0"
                                        value="{{ old('electric') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Watt</span>
                                    </div>
                                </div>
                                @error('electric')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="land_area" class="text-black">Luas Tanah</label>
                                <div class="input-group">
                                    <input type="number" id="land_area" name="land_area" min="0" step="0.01"
                                        class="form-control @error('land_area') is-invalid @enderror" placeholder="0"
                                        value="{{ old('land_area') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                </div>
                                @error('land_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="building_area" class="text-black">Luas Bangunan</label>
                                <div class="input-group">
                                    <input type="number" id="building_area" name="building_area" min="0"
                                        step="0.01" class="form-control @error('building_area') is-invalid @enderror"
                                        placeholder="0" value="{{ old('building_area') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                </div>
                                @error('building_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Facilities -->
                        <h4 class="text-black mb-4 mt-5">Fasilitas</h4>
                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    @foreach ($facilities as $facility)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="facilities[]"
                                                    value="{{ $facility->id }}" id="facility_{{ $facility->id }}"
                                                    {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="facility_{{ $facility->id }}">
                                                    {{ $facility->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('facilities')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Map -->
                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="map" class="text-black">Peta (Google Maps Embed)</label>
                                <textarea name="map" id="map" cols="30" rows="3"
                                    class="form-control @error('map') is-invalid @enderror" placeholder="Masukkan kode embed dari Google Maps">{{ old('map') }}</textarea>
                                <small class="form-text text-muted">
                                    Dapatkan kode embed dari Google Maps dengan memilih "Share" > "Embed a map" > Salin kode
                                    (Https://..) nya saja yang disediakan.
                                </small>
                                @error('map')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Images -->
                        <h4 class="text-black mb-4 mt-5">Foto Properti</h4>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="thumbnail" class="text-black">Thumbnail <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="thumbnail" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror"
                                    accept="image/jpeg,image/jpg,image/png,image/gif" required>
                                <small class="form-text text-muted">Maksimal 1MB. Format: JPEG, JPG, PNG, GIF</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12 mb-3">
                                <label for="photos" class="text-black">Foto Tambahan</label>
                                <input type="file" id="photos" name="photos[]" multiple
                                    class="form-control @error('photos.*') is-invalid @enderror"
                                    accept="image/jpeg,image/jpg,image/png,image/gif">
                                <small class="form-text text-muted">Maksimal 2MB per file. Format: JPEG, JPG, PNG, GIF.
                                    Bisa pilih beberapa file sekaligus.</small>
                                @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                </div>

                <!-- Sidebar Contact Info -->
                <div class="col-lg-4">
                    <div class="bg-white widget border rounded">
                        <h3 class="h4 text-black widget-title mb-3">Contact Pengiklan</h3>

                        <div class="form-group">
                            <label for="name_iklan" class="text-black">Nama Pengiklan <span
                                    class="text-danger">*</span></label>
                            <input type="text" placeholder="Nama Pengiklan" id="name_iklan" name="name_iklan"
                                value="{{ old('name_iklan') }}"
                                class="form-control @error('name_iklan') is-invalid @enderror" required>
                            @error('name_iklan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_iklan" class="text-black">No. HP Pengiklan <span
                                    class="text-danger">*</span></label>
                            <input type="tel" placeholder="No. HP Pengiklan" id="phone_iklan" name="phone_iklan"
                                value="{{ old('phone_iklan') }}"
                                class="form-control @error('phone_iklan') is-invalid @enderror" required>
                            @error('phone_iklan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email_iklan" class="text-black">Email Pengiklan <span
                                    class="text-danger">*</span></label>
                            <input type="email" placeholder="Email Pengiklan" id="email_iklan" name="email_iklan"
                                value="{{ old('email_iklan') }}"
                                class="form-control @error('email_iklan') is-invalid @enderror" required>
                            @error('email_iklan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Buat Iklan" />
                        </div>

                        </form>
                    </div>
                </div>
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

        /* Custom styling */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Responsive */
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

            // Preview thumbnail
            document.getElementById('thumbnail').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add image preview functionality here
                        console.log('Thumbnail selected:', file.name);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Format price input
            document.getElementById('price').addEventListener('input', function(e) {
                // Remove any non-digit characters
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });
        });
    </script>

@endsection
