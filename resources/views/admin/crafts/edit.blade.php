@extends('admin.layout')

@section('title', 'تعديل: ' . $craft->title)
@section('page_title', 'تعديل الحرفة')

@push('styles')
    <style>
        /* ==========================================
           CKEditor — منع التمدد الأفقي في لوحة التحكم
           ========================================== */

        /* منطقة التحرير الرئيسية — حد أقصى للعرض */
        .ck-editor__editable {
            min-height: 380px;
            max-width: 100% !important;
            overflow-x: hidden !important;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* حاوية المحرر لا تتجاوز حاويتها الأب */
        .ck-editor__main {
            overflow-x: auto;
            max-width: 100%;
        }

        /* شريط الأدوات يلتف عند الامتلاء */
        .ck-toolbar {
            flex-wrap: wrap !important;
        }

        /* الجداول داخل المحرر تأخذ عرض كامل مع scroll أفقي */
        .ck-editor__editable table {
            width: 100%;
            max-width: 100%;
        }

        /* الصور داخل المحرر لا تتجاوز عرض الحاوية */
        .ck-editor__editable figure.image,
        .ck-editor__editable figure.image img,
        .ck-editor__editable img {
            max-width: 100% !important;
            height: auto;
        }

        /* الحاوية الكاملة للمحرر */
        .ck.ck-editor {
            max-width: 100% !important;
        }
    </style>
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
                <h2 class="page-title">تعديل: <span class="text-primary">{{ $craft->title }}</span></h2>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-edit me-1"></i> تعديل بيانات الحرفة</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.crafts.update', $craft) }}" method="POST" enctype="multipart/form-data">
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
                    {{-- Title --}}
                    <div class="col-md-8">
                        <label class="form-label required">عنوان الحرفة</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $craft->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div class="col-md-4">
                        <label class="form-label required">الموقع الجغرافي</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $craft->location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="form-label required">الوصف المختصر</label>
                        <textarea name="short_description" rows="3"
                                  class="form-control @error('short_description') is-invalid @enderror"
                                  required>{{ old('short_description', $craft->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content — CKEditor 5 Target --}}
                    <div class="col-12">
                        <label class="form-label required">المحتوى التفصيلي</label>
                        <textarea name="content" id="content"
                                  class="form-control @error('content') is-invalid @enderror"
                                  required>{{ old('content', $craft->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cover Image --}}
                    <div class="col-12">
                        <label class="form-label">صورة الغلاف</label>
                        @if($craft->cover_image)
                            <div class="mb-2">
                                <img src="{{ $craft->cover_image_url }}"
                                     alt="{{ $craft->title }}"
                                     class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-text text-warning mt-1">
                                    <i class="ti ti-info-circle me-1"></i>
                                    رفع صورة جديدة سيستبدل الصورة الحالية.
                                </div>
                            </div>
                        @endif
                        <input type="file" name="cover_image"
                               class="form-control @error('cover_image') is-invalid @enderror"
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
