@extends('admin.layout')

@section('title', 'تعديل شهادة: ' . $story->craftsman_name)
@section('page_title', 'تعديل شهادة: ' . $story->craftsman_name)

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
                <h2 class="page-title">تعديل شهادة: {{ $story->craftsman_name }}</h2>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-edit me-1"></i> تعديل بيانات الشهادة</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stories.update', $story) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-12">
                        <label class="form-label required">عنوان الشهادة / القصة</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $story->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craftsman Name --}}
                    <div class="col-md-6">
                        <label class="form-label required">اسم الحرفي الكامل</label>
                        <input type="text" name="craftsman_name" class="form-control @error('craftsman_name') is-invalid @enderror"
                               value="{{ old('craftsman_name', $story->craftsman_name) }}" required>
                        @error('craftsman_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Craftsman Role --}}
                    <div class="col-md-6">
                        <label class="form-label required">صفة الحرفي / لقبه</label>
                        <input type="text" name="craftsman_role" class="form-control @error('craftsman_role') is-invalid @enderror"
                               value="{{ old('craftsman_role', $story->craftsman_role) }}" required>
                        @error('craftsman_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Excerpt --}}
                    <div class="col-12">
                        <label class="form-label">المقتطف / النبذة</label>
                        <textarea name="excerpt" rows="2"
                                  class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $story->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">إذا تُرك فارغاً سيتم اقتطاع أول 25 كلمة من المحتوى تلقائياً.</div>
                    </div>

                    {{-- Content — CKEditor 5 Target --}}
                    <div class="col-12">
                        <label class="form-label required">المحتوى التفصيلي للشهادة</label>
                        <textarea name="content" id="content"
                                  class="form-control @error('content') is-invalid @enderror">{{ old('content', $story->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Photo --}}
                    <div class="col-md-6">
                        <label class="form-label">صورة الحرفي</label>
                        @if($story->photo)
                            <div class="mb-2">
                                <img src="{{ $story->photo_url }}" alt="{{ $story->craftsman_name }}"
                                     class="rounded" style="max-height: 120px; object-fit: cover;">
                                <div class="form-text">الصورة الحالية — ارفع صورة جديدة لاستبدالها.</div>
                            </div>
                        @endif
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
                        @if($story->audio_file)
                            <div class="p-3 mb-2 bg-light rounded border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-amber-lt"><i class="ti ti-headphones me-1"></i> التسجيل الصوتي الحالي</span>
                                    <label class="form-check form-check-inline text-danger mb-0">
                                        <input class="form-check-input" type="checkbox" name="delete_audio" value="1" id="delete_audio">
                                        <span class="form-check-label fw-bold"><i class="ti ti-trash me-1"></i> حذف الملف الصوتي نهائياً</span>
                                    </label>
                                </div>
                                <audio controls class="w-100 mb-1">
                                    <source src="{{ $story->audio_file_url }}" type="audio/mpeg">
                                    المتصفح لا يدعم مشغل الصوتيات.
                                </audio>
                                <div class="form-text text-muted">ارفع ملفاً جديداً أدناه لاستبدال هذا الملف، أو حدد خيار الحذف لإزالته.</div>
                            </div>
                        @endif
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
                        <input type="url" name="youtube_url" id="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url', $story->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">اترك الحقل فارغاً إذا أردت إزالة الفيديو نهائياً من الشهادة.</div>
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($story->youtube_embed_url)
                            <div class="mt-2">
                                <div class="ratio ratio-16x9" style="max-width: 300px;">
                                    <iframe src="{{ $story->youtube_embed_url }}" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Published --}}
                    <div class="col-md-6 d-flex align-items-end">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $story->is_published) ? 'checked' : '' }}>
                            <span class="form-check-label">نشر الشهادة (مرئية للزوار)</span>
                        </label>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> تحديث الشهادة
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
