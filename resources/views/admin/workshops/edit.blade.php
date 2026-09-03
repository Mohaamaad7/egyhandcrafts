@extends('admin.layout')

@section('title', 'تعديل ورشة: ' . $workshop->name)
@section('page_title', 'تعديل بيانات الورشة')

@push('styles')
    {{-- Leaflet CSS for coordinate picker --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #coordinateMap {
            height: 350px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            cursor: crosshair;
        }
        /* CKEditor overflow protection */
        .col-12, .card-body { min-width: 0 !important; max-width: 100% !important; }
        .ck.ck-editor { width: 100% !important; max-width: 100% !important; min-width: 0 !important; }
        .ck-toolbar { flex-wrap: wrap !important; max-width: 100% !important; }
        .ck-editor__main { max-width: 100% !important; min-width: 0 !important; overflow-x: auto !important; }
        .ck.ck-content.ck-editor__editable { min-height: 250px; max-width: 100% !important; word-break: break-word; overflow-wrap: break-word; }
    </style>
@endpush

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> رجوع
                </a>
            </div>
            <div class="col">
                <h2 class="page-title">تعديل: {{ $workshop->name }}</h2>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-edit me-1"></i> تعديل بيانات الورشة</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.workshops.update', $workshop) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <a class="btn-close" data-bs-dismiss="alert"></a>
                    </div>
                @endif

                <div class="row g-3">
                    {{-- Workshop Name --}}
                    <div class="col-md-6">
                        <label class="form-label required">اسم الورشة</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $workshop->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craft Association --}}
                    <div class="col-md-3">
                        <label class="form-label">الحرفة (من الدليل)</label>
                        <select name="craft_id" class="form-select @error('craft_id') is-invalid @enderror" id="craftSelect">
                            <option value="">-- بدون ربط --</option>
                            @foreach($crafts as $craft)
                                <option value="{{ $craft->id }}" {{ old('craft_id', $workshop->craft_id) == $craft->id ? 'selected' : '' }}>
                                    {{ $craft->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('craft_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craft Type --}}
                    <div class="col-md-3">
                        <label class="form-label required">نوع الحرفة (التسمية)</label>
                        <input type="text" name="craft_type" class="form-control @error('craft_type') is-invalid @enderror"
                               value="{{ old('craft_type', $workshop->craft_type) }}" required>
                        @error('craft_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div class="col-md-4">
                        <label class="form-label required">المكان</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $workshop->location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Owner --}}
                    <div class="col-md-4">
                        <label class="form-label required">مالك الورشة</label>
                        <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror"
                               value="{{ old('owner', $workshop->owner) }}" required>
                        @error('owner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Workers Count --}}
                    <div class="col-md-2">
                        <label class="form-label required">عدد العمالة</label>
                        <input type="text" name="workers_count" class="form-control @error('workers_count') is-invalid @enderror"
                               value="{{ old('workers_count', $workshop->workers_count) }}" required>
                        @error('workers_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-2">
                        <label class="form-label required">الهاتف</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $workshop->phone) }}" dir="ltr" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Coordinates --}}
                    <div class="col-md-3">
                        <label class="form-label required">خط العرض (Latitude)</label>
                        <input type="number" name="latitude" id="latInput" step="0.0000001"
                               class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude', $workshop->latitude) }}" dir="ltr" required>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label required">خط الطول (Longitude)</label>
                        <input type="number" name="longitude" id="lngInput" step="0.0000001"
                               class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude', $workshop->longitude) }}" dir="ltr" required>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Active Status --}}
                    <div class="col-md-6 d-flex align-items-end">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $workshop->is_active) ? 'checked' : '' }}>
                            <span class="form-check-label">ورشة نشطة (تظهر على الخريطة)</span>
                        </label>
                    </div>

                    {{-- Leaflet Coordinate Picker Map --}}
                    <div class="col-12">
                        <label class="form-label">تحديد الموقع على الخريطة <span class="text-muted">(اضغط على الخريطة لتحديث الإحداثيات)</span></label>
                        <div id="coordinateMap"></div>
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="form-label">الوصف المختصر</label>
                        <textarea name="short_description" rows="3"
                                  class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $workshop->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content — CKEditor 5 Target --}}
                    <div class="col-12">
                        <label class="form-label">المحتوى التفصيلي (ملف الورشة)</label>
                        <textarea name="content" id="content"
                                  class="form-control @error('content') is-invalid @enderror">{{ old('content', $workshop->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cover Image --}}
                    <div class="col-12">
                        <label class="form-label">صورة الغلاف</label>
                        @if($workshop->cover_image)
                            <div class="mb-2">
                                <img src="{{ $workshop->cover_image_url }}" alt="صورة الغلاف الحالية"
                                     class="rounded border" style="max-height: 120px;">
                                <div class="form-text text-warning">رفع صورة جديدة سيستبدل الصورة الحالية.</div>
                            </div>
                        @endif
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">الحجم الأقصى 2 ميجابايت. الصيغ المقبولة: JPG, PNG, WEBP.</div>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Leaflet JS for coordinate picker --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latInput = document.getElementById('latInput');
            var lngInput = document.getElementById('lngInput');

            var defaultLat = parseFloat(latInput.value) || 30.382;
            var defaultLng = parseFloat(lngInput.value) || 30.893;

            var map = L.map('coordinateMap').setView([defaultLat, defaultLng], 16);

            L.tileLayer('https://mt1.google.com/vt/lyrs=m&hl=ar&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                attribution: '© Google Maps'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                latInput.value = e.latlng.lat.toFixed(7);
                lngInput.value = e.latlng.lng.toFixed(7);
            });

            marker.on('dragend', function (e) {
                var pos = marker.getLatLng();
                latInput.value = pos.lat.toFixed(7);
                lngInput.value = pos.lng.toFixed(7);
            });

            function updateMarkerFromInputs() {
                var lat = parseFloat(latInput.value);
                var lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.panTo([lat, lng]);
                }
            }
            latInput.addEventListener('change', updateMarkerFromInputs);
            lngInput.addEventListener('change', updateMarkerFromInputs);
        });
    </script>

    {{-- CKEditor 5 --}}
    @vite('resources/js/ckeditor.js')
@endpush
