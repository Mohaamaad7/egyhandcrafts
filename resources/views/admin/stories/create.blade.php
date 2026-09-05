@extends('admin.layout')

@section('title', 'إضافة شهادة حرفي جديدة')
@section('page_title', 'إضافة شهادة حرفي جديدة')

@push('styles')
    <style>
        /* ==========================================
           CKEditor Responsive & Overflow Protection
           ========================================== */
        .col-12, .card-body {
            min-width: 0 !important;
            max-width: 100% !important;
        }

        .ck.ck-editor {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
        }

        .ck-toolbar {
            flex-wrap: wrap !important;
            max-width: 100% !important;
        }

        .ck-editor__main {
            max-width: 100% !important;
            min-width: 0 !important;
            overflow-x: auto !important;
        }

        .ck.ck-content.ck-editor__editable {
            min-height: 380px;
            max-width: 100% !important;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .ck-content figure.table {
            max-width: 100% !important;
            overflow-x: auto !important;
            margin: 1em 0;
        }

        .ck-content figure.table table {
            max-width: 100% !important;
        }

        .ck-content figure.image {
            max-width: 100% !important;
        }

        .ck-content figure.image img,
        .ck-content img {
            max-width: 100% !important;
            height: auto !important;
        }

        .ck-content figure.image.image-style-side {
            max-width: 50% !important;
        }
    </style>
@endpush

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="{{ route('admin.stories.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> رجوع
                </a>
            </div>
            <div class="col">
                <h2 class="page-title">إضافة شهادة حرفي جديدة</h2>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-plus me-1"></i> بيانات الشهادة</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-12">
                        <label class="form-label required">عنوان الشهادة / القصة</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="مثال: رحلة الحاج محمود أبو قوطة مع التطعيم بالصدف" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craftsman Name --}}
                    <div class="col-md-6">
                        <label class="form-label required">اسم الحرفي الكامل</label>
                        <input type="text" name="craftsman_name" class="form-control @error('craftsman_name') is-invalid @enderror"
                               value="{{ old('craftsman_name') }}" placeholder="مثال: الحاج محمود أبو قوطة" required>
                        @error('craftsman_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craftsman Role --}}
                    <div class="col-md-6">
                        <label class="form-label required">صفة الحرفي / لقبه</label>
                        <input type="text" name="craftsman_role" class="form-control @error('craftsman_role') is-invalid @enderror"
                               value="{{ old('craftsman_role') }}" placeholder="مثال: نقيب حرفيي الصدف ورائد الصنعة" required>
                        @error('craftsman_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Excerpt --}}
                    <div class="col-12">
                        <label class="form-label">المقتطف / النبذة</label>
                        <textarea name="excerpt" rows="2"
                                  class="form-control @error('excerpt') is-invalid @enderror"
                                  placeholder="اختياري — إذا تُرك فارغاً سيتم توليده تلقائياً من المحتوى">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">إذا تُرك فارغاً سيتم اقتطاع أول 25 كلمة من المحتوى تلقائياً.</div>
                    </div>

                    {{-- Content — CKEditor 5 Target --}}
                    <div class="col-12">
                        <label class="form-label required">المحتوى التفصيلي للشهادة</label>
                        <textarea name="content" id="content"
                                  class="form-control @error('content') is-invalid @enderror"
                                  placeholder="اكتب القصة أو الشهادة الكاملة هنا...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Photo --}}
                    <div class="col-md-6">
                        <label class="form-label">صورة الحرفي</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">الحجم الأقصى 5 ميجابايت. الصيغ: JPG, PNG, WEBP.</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Audio File --}}
                    <div class="col-md-6">
                        <label class="form-label">تسجيل صوتي ميداني</label>
                        <input type="file" name="audio_file" class="form-control @error('audio_file') is-invalid @enderror"
                               accept="audio/mpeg,audio/wav,audio/mp4,audio/aac,audio/ogg,.mp3,.wav,.m4a,.aac,.ogg">
                        <div class="form-text">الحجم الأقصى 50 ميجابايت. الصيغ: MP3, WAV, M4A, AAC, OGG.</div>
                        @error('audio_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- YouTube URL --}}
                    <div class="col-md-6">
                        <label class="form-label">رابط فيديو يوتيوب</label>
                        <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Published --}}
                    <div class="col-md-6 d-flex align-items-end">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                            <span class="form-check-label">نشر الشهادة (مرئية للزوار)</span>
                        </label>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> حفظ الشهادة
                    </button>
                    <a href="{{ route('admin.stories.index') }}" class="btn btn-outline-secondary">إلغاء</a>
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
