@extends('admin.layout')

@section('title', 'قصص وشهادات الحرفيين')
@section('page_title', 'إدارة قصص وشهادات الحرفيين')

@section('content')
<div class="container-xl">

    {{-- Success / Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div><i class="ti ti-circle-check me-2"></i></div>
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
        </div>
    @endif

    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-microphone me-2 text-primary"></i> قصص وشهادات الحرفيين
                </h2>
                <div class="text-muted mt-1">إجمالي: {{ $stories->total() }} شهادة موثقة</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.stories.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة شهادة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">قائمة الشهادات والقصص</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الحرفي</th>
                            <th>العنوان</th>
                            <th>الوسائط</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                            <th class="w-1">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stories as $story)
                        <tr>
                            <td class="text-muted">{{ $story->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($story->photo)
                                        <span class="avatar me-2" style="background-image: url({{ $story->photo_url }})"></span>
                                    @else
                                        <span class="avatar me-2 bg-primary-lt"><i class="ti ti-user"></i></span>
                                    @endif
                                    <div>
                                        <div class="font-weight-medium">{{ $story->craftsman_name }}</div>
                                        <div class="text-muted small">{{ $story->craftsman_role }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ Str::limit($story->title, 50) }}</div>
                            </td>
                            <td>
                                @if($story->has_video)
                                    <span class="badge bg-red-lt me-1" title="توثيق مرئي">🎬 فيديو</span>
                                @endif
                                @if($story->has_audio)
                                    <span class="badge bg-yellow-lt me-1" title="تسجيل صوتي">🎙️ صوتي</span>
                                @endif
                                @if(!$story->has_video && !$story->has_audio)
                                    <span class="badge bg-azure-lt" title="نص فقط">📝 نص</span>
                                @endif
                            </td>
                            <td>
                                @if($story->is_published)
                                    <span class="badge bg-success-lt">منشور</span>
                                @else
                                    <span class="badge bg-secondary-lt">مسودة</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $story->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('stories.show', $story->slug) }}" class="btn btn-sm btn-outline-info" target="_blank" title="معاينة">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.stories.edit', $story) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.stories.destroy', $story) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الشهادة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i> حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                                لا توجد شهادات أو قصص مضافة بعد.
                                <a href="{{ route('admin.stories.create') }}" class="d-block mt-2">أضف أول شهادة الآن</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($stories->hasPages())
        <div class="card-footer d-flex align-items-center">
            {{ $stories->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
