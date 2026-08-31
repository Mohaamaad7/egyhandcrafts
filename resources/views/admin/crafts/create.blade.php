@extends('admin.layout')

@section('title', 'إضافة حرفة جديدة')
@section('page_title', 'إضافة حرفة جديدة')

@push('styles')
    {{-- CKEditor 5 styles are bundled into the ckeditor.js entry via Vite --}}
@endpush

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="{{ route('admin.crafts.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> رجوع
                </a>
            </div>
            <div class="col">
                <h2 class="page-title">إضافة حرفة تراثية جديدة</h2>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-plus me-1"></i> بيانات الحرفة</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.crafts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                    {{-- Title --}}
                    <div class="col-md-8">
                        <label class="form-label required">عنوان الحرفة</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="مثال: ساقية أبو شعرة للسجاد" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div class="col-md-4">
                        <label class="form-label required">الموقع الجغرافي</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location') }}" placeholder="مثال: شبين الكوم - المنوفية" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="form-label required">الوصف المختصر</label>
                        <textarea name="short_description" rows="3"
                                  class="form-control @error('short_description') is-invalid @enderror"
                                  placeholder="وصف مختصر يظهر في بطاقة الحرفة (2-3 أسطر)" required>{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content — CKEditor 5 Target --}}
                    <div class="col-12">
                        <label class="form-label required">المحتوى التفصيلي</label>
                        <textarea name="content" id="content"
                                  class="form-control @error('content') is-invalid @enderror"
                                  placeholder="اكتب المحتوى الكامل للحرفة هنا..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cover Image --}}
                    <div class="col-12">
                        <label class="form-label">صورة الغلاف</label>
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
                        <i class="ti ti-device-floppy me-1"></i> حفظ الحرفة
                    </button>
                    <a href="{{ route('admin.crafts.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Load the compiled CKEditor 5 bundle from Vite --}}
    @vite('resources/js/ckeditor.js')
@endpush
