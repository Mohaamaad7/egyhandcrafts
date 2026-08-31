@extends('admin.layout')

@section('title', 'دليل الحرف')
@section('page_title', 'إدارة الحرف التراثية')

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
                    <i class="ti ti-tools me-2 text-primary"></i> دليل الحرف التراثية
                </h2>
                <div class="text-muted mt-1">إجمالي: {{ $crafts->total() }} حرفة</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.crafts.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    إضافة حرفة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">قائمة الحرف</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الموقع</th>
                            <th>تاريخ الإضافة</th>
                            <th class="w-1">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crafts as $craft)
                        <tr>
                            <td class="text-muted">{{ $craft->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($craft->cover_image)
                                        <span class="avatar me-2" style="background-image: url({{ $craft->cover_image_url }})"></span>
                                    @else
                                        <span class="avatar me-2 bg-primary-lt"><i class="ti ti-tools"></i></span>
                                    @endif
                                    <div>
                                        <div class="font-weight-medium">{{ $craft->title }}</div>
                                        <div class="text-muted small">{{ Str::limit($craft->short_description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-blue-lt">{{ $craft->location }}</span>
                            </td>
                            <td class="text-muted">{{ $craft->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.crafts.edit', $craft) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.crafts.destroy', $craft) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الحرفة؟')">
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
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                                لا توجد حرف مضافة بعد.
                                <a href="{{ route('admin.crafts.create') }}" class="d-block mt-2">أضف أول حرفة الآن</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($crafts->hasPages())
        <div class="card-footer d-flex align-items-center">
            {{ $crafts->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
