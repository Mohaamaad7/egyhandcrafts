@extends('admin.layout')

@section('title', 'الرئيسية والمؤشرات')
@section('page_title', 'لوحة المؤشرات والقيادة الميدانية')

@section('content')
<div class="row row-deck row-cards mb-4">
    {{-- Welcome Hero Banner --}}
    <div class="col-12">
        <div class="card card-md shadow-xs border-0 bg-primary text-white" style="background: linear-gradient(135deg, #0E1B2D 0%, #1A2F4C 60%, #264268 100%) !important;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark fw-bold px-2 py-1">المنظومة المركزية</span>
                            <span class="text-white-50 small">{{ now()->translatedFormat('l، d F Y') }}</span>
                        </div>
                        <h1 class="h2 text-white fw-bold mb-2">
                            مرحباً بك، {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-white-50 mb-0 fs-3" style="max-width: 650px;">
                            أهلاً بك في منصة الرصد والمتابعة الأكاديمية لمشروع توثيق الحرف التراثية بمحافظة المنوفية. يمكنك متابعة المؤشرات الميدانية، إدارة الورش، ومراجعة المحتوى وتوثيق شهادات الحرفيين.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-start mt-3 mt-md-0">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-md-end">
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-warning fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="ti ti-world fs-2"></i>
                                <span>معاينة البوابة</span>
                            </a>
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-light d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="ti ti-user-cog fs-2"></i>
                                <span>إعدادات الحساب</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dynamic KPI Stat Cards --}}
<div class="row row-cards mb-4">
    {{-- Crafts Count --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-xs border-0 border-top border-3 border-primary h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar avatar-md shadow-xs">
                            <i class="ti ti-tools fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-medium">الحرف التراثية الموثقة</div>
                        <div class="h1 mb-0 fw-bold text-dark">{{ $craftsCount }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="text-secondary small">
                        <i class="ti ti-photo me-1 text-success"></i> {{ $craftsWithCover }} بغلاف موثق
                    </span>
                    <a href="{{ route('admin.crafts.index') }}" class="small text-primary fw-medium text-decoration-none d-inline-flex align-items-center gap-1">
                        <span>إدارة</span>
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Workshops Count --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-xs border-0 border-top border-3 border-teal h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-teal text-white avatar avatar-md shadow-xs">
                            <i class="ti ti-map-pin fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-medium">ورش العمل والمواقع</div>
                        <div class="h1 mb-0 fw-bold text-dark">{{ $workshopsCount }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="text-secondary small">
                        <i class="ti ti-check me-1 text-teal"></i> {{ $activeWorkshopsCount }} نشطة ({{ $totalWorkers }} عامل)
                    </span>
                    <a href="{{ route('admin.workshops.index') }}" class="small text-teal fw-medium text-decoration-none d-inline-flex align-items-center gap-1">
                        <span>إدارة</span>
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stories Count --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-xs border-0 border-top border-3 border-warning h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-warning text-white avatar avatar-md shadow-xs">
                            <i class="ti ti-microphone fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-medium">شهادات وقصص الحرفيين</div>
                        <div class="h1 mb-0 fw-bold text-dark">{{ $storiesCount }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="text-secondary small">
                        🎬 {{ $storiesWithVideo }} فيديو | 🎙️ {{ $storiesWithAudio }} صوت
                    </span>
                    <a href="{{ route('admin.stories.index') }}" class="small text-warning fw-medium text-decoration-none d-inline-flex align-items-center gap-1">
                        <span>إدارة</span>
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Team Count --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-xs border-0 border-top border-3 border-indigo h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-indigo text-white avatar avatar-md shadow-xs">
                            <i class="ti ti-users-group fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-medium">فريق العمل والمسؤولين</div>
                        <div class="h1 mb-0 fw-bold text-dark">{{ $usersCount }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                    <span class="text-secondary small">
                        <i class="ti ti-shield-check text-indigo me-1"></i> صلاحيات إدارية
                    </span>
                    @if (auth()->user()?->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}" class="small text-indigo fw-medium text-decoration-none d-inline-flex align-items-center gap-1">
                            <span>الفريق</span>
                            <i class="ti ti-arrow-left"></i>
                        </a>
                    @else
                        <span class="small text-secondary">مسؤول</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions Bar --}}
<div class="card shadow-xs border-0 mb-4">
    <div class="card-body py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark-lt text-dark fs-4 px-2 py-1">
                    <i class="ti ti-bolt me-1 text-warning"></i> إجراءات سريعة:
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.crafts.create') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-plus"></i>
                    <span>إضافة حرفة</span>
                </a>
                <a href="{{ route('admin.workshops.create') }}" class="btn btn-outline-teal btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-map-pin"></i>
                    <span>إضافة ورشة عمل</span>
                </a>
                <a href="{{ route('admin.stories.create') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-microphone"></i>
                    <span>توثيق قصة حرفي</span>
                </a>
                @if (auth()->user()?->isSuperAdmin())
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-indigo btn-sm d-flex align-items-center gap-1">
                        <i class="ti ti-user-plus"></i>
                        <span>إضافة مسؤول جديد</span>
                    </a>
                @endif
                <a href="{{ route('map.index') }}" target="_blank" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-map"></i>
                    <span>الخريطة المباشرة</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity & Data Overview (2-Column Showcase) --}}
<div class="row row-deck row-cards">
    {{-- Recent Workshops --}}
    <div class="col-lg-7">
        <div class="card shadow-xs border-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-map-pin text-teal"></i>
                    <span>أحدث ورش العمل الميدانية الموثقة</span>
                </h3>
                <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary btn-sm">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover">
                    <thead>
                        <tr>
                            <th>الورشة</th>
                            <th>الحرفة</th>
                            <th>الموقع</th>
                            <th>العمال</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentWorkshops as $workshop)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($workshop->cover_image)
                                            <span class="avatar avatar-xs rounded shadow-xs" style="background-image: url('{{ $workshop->cover_image_url }}')"></span>
                                        @else
                                            <span class="avatar avatar-xs rounded bg-teal-lt text-teal">
                                                <i class="ti ti-building"></i>
                                            </span>
                                        @endif
                                        <div class="fw-bold text-dark">{{ $workshop->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-lt">{{ $workshop->craft?->title ?? $workshop->craft_type ?? 'حرفة تراثية' }}</span>
                                </td>
                                <td class="text-secondary small">{{ $workshop->location }}</td>
                                <td class="text-secondary small">{{ $workshop->workers_count }}</td>
                                <td>
                                    @if ($workshop->is_active)
                                        <span class="badge bg-success-lt">نشطة</span>
                                    @else
                                        <span class="badge bg-secondary-lt">متوقفة</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary">لا توجد ورش مسجلة حتى الآن</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Stories & Crafts Summary --}}
    <div class="col-lg-5">
        <div class="card shadow-xs border-0 mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-microphone text-warning"></i>
                    <span>أحدث شهادات الحرفيين</span>
                </h3>
                <a href="{{ route('admin.stories.index') }}" class="btn btn-outline-secondary btn-sm">عرض الكل</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($recentStories as $story)
                    <div class="list-group-item py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if ($story->photo)
                                    <span class="avatar avatar-md rounded-circle shadow-xs" style="background-image: url('{{ $story->photo_url }}')"></span>
                                @else
                                    <span class="avatar avatar-md rounded-circle bg-warning-lt text-warning fw-bold">
                                        {{ mb_substr($story->craftsman_name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="col text-truncate">
                                <div class="text-dark fw-bold">{{ $story->craftsman_name }}</div>
                                <div class="text-secondary small text-truncate">{{ $story->craftsman_role }}</div>
                            </div>
                            <div class="col-auto d-flex gap-1">
                                @if ($story->has_video)
                                    <span class="badge bg-red-lt" title="فيديو موثق">🎬</span>
                                @endif
                                @if ($story->has_audio)
                                    <span class="badge bg-green-lt" title="تسجيل صوتي">🎙️</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-secondary">
                        لا توجد قصص موثقة بعد
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Documentation Health Card --}}
        <div class="card shadow-xs border-0">
            <div class="card-body p-3">
                <h4 class="card-title fw-bold text-dark mb-2">مؤشر اكتمال التوثيق الميداني</h4>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>نسبة الحرف ذات الصور الموثقة</span>
                        <span class="fw-bold">{{ $craftsCount > 0 ? round(($craftsWithCover / $craftsCount) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: {{ $craftsCount > 0 ? ($craftsWithCover / $craftsCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>نسبة الورش النشطة المسجلة</span>
                        <span class="fw-bold">{{ $workshopsCount > 0 ? round(($activeWorkshopsCount / $workshopsCount) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-teal" style="width: {{ $workshopsCount > 0 ? ($activeWorkshopsCount / $workshopsCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
