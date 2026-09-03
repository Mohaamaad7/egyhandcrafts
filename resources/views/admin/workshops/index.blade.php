@extends('admin.layout')

@section('title', 'ورش الحرف التراثية')
@section('page_title', 'إدارة ورش الحرف (الخريطة)')

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
                    <i class="ti ti-map-pin me-2 text-primary"></i> ورش الحرف التراثية
                </h2>
                <div class="text-muted mt-1">إجمالي: {{ $workshops->total() }} ورشة</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.workshops.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة ورشة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">قائمة الورش</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الورشة</th>
                            <th>الحرفة</th>
                            <th>المكان</th>
                            <th>المالك</th>
                            <th>العمالة</th>
                            <th>الحالة</th>
                            <th class="w-1">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workshops as $workshop)
                        <tr>
                            <td class="text-muted">{{ $workshop->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($workshop->cover_image)
                                        <span class="avatar me-2" style="background-image: url({{ $workshop->cover_image_url }})"></span>
                                    @else
                                        <span class="avatar me-2 bg-cyan-lt"><i class="ti ti-map-pin"></i></span>
                                    @endif
                                    <div>
                                        <div class="font-weight-medium">{{ $workshop->name }}</div>
                                        <div class="text-muted small">{{ $workshop->owner }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-teal-lt">{{ $workshop->craft_type }}</span>
                            </td>
                            <td>
                                <span class="badge bg-blue-lt">{{ $workshop->location }}</span>
                            </td>
                            <td class="text-muted">{{ $workshop->owner }}</td>
                            <td class="text-muted">{{ $workshop->workers_count }}</td>
                            <td>
                                @if($workshop->is_active)
                                    <span class="badge bg-success-lt">نشطة</span>
                                @else
                                    <span class="badge bg-secondary-lt">مخفية</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.workshops.edit', $workshop) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.workshops.destroy', $workshop) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الورشة؟')">
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
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                                لا توجد ورش مضافة بعد.
                                <a href="{{ route('admin.workshops.create') }}" class="d-block mt-2">أضف أول ورشة الآن</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($workshops->hasPages())
        <div class="card-footer d-flex align-items-center">
            {{ $workshops->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
