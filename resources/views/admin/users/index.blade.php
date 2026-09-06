@extends('admin.layout')

@section('title', 'إدارة فريق المسؤولين')
@section('page_title', 'فريق العمل والمسؤولين')

@section('content')
<div class="row g-3 mb-4 align-items-center justify-content-between">
    <div class="col-auto">
        <h2 class="page-title text-primary h3 mb-0">حسابات الإدارة والمشرفين</h2>
        <div class="text-secondary small mt-1">إدارة الصلاحيات وحسابات الدخول لفريق التوثيق الميداني</div>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-xs">
            <i class="ti ti-user-plus fs-2"></i>
            <span>إضافة مسؤول جديد</span>
        </a>
    </div>
</div>

<div class="card shadow-xs border-0">
    <div class="card-header py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center w-100">
            <div class="col-md-5">
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-search text-secondary"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="ابحث بالاسم أو اسم المستخدم أو البريد...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select form-select-sm">
                    <option value="">جميع الصلاحيات والأدوار</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>مدير النظام (Super Admin)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مسؤول توثيق (Admin)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">تصفية</button>
                @if (request()->hasAny(['q', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" title="إعادة تعيين"><i class="ti ti-x"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover">
            <thead>
                <tr>
                    <th>المسؤول</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الصلاحية</th>
                    <th>تاريخ الإضافة</th>
                    <th class="w-1 text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm rounded-circle bg-primary-lt text-primary fw-bold shadow-xs">
                                    {{ $user->initials }}
                                </span>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    @if ($user->id === auth()->id())
                                        <span class="badge bg-green-lt py-0 px-1" style="font-size: 0.7rem;">حسابك الحالي</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-secondary dir-ltr text-end">
                            <code>{{ '@' . ($user->username ?? '—') }}</code>
                        </td>
                        <td class="text-secondary small">{{ $user->email }}</td>
                        <td>
                            @if ($user->isSuperAdmin())
                                <span class="badge bg-purple-lt fw-bold">
                                    <i class="ti ti-shield-lock me-1"></i> مدير النظام
                                </span>
                            @else
                                <span class="badge bg-blue-lt">
                                    <i class="ti ti-user me-1"></i> مسؤول توثيق
                                </span>
                            @endif
                        </td>
                        <td class="text-secondary small">{{ $user->created_at?->translatedFormat('d F Y') ?? '—' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary" title="تعديل الحساب وإعادة تعيين كلمة المرور">
                                    <i class="ti ti-edit"></i>
                                </a>

                                @if ($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}" title="حذف المسؤول">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            </div>

                            {{-- Delete Confirmation Modal --}}
                            @if ($user->id !== auth()->id())
                                <div class="modal fade text-start" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body text-center py-4">
                                                <i class="ti ti-alert-triangle text-danger display-4 mb-2"></i>
                                                <h3>تأكيد حذف الحساب</h3>
                                                <div class="text-secondary small mb-3">
                                                    هل أنت متأكد من رغبتك في حذف حساب المسؤول <strong>{{ $user->name }}</strong>؟ لا يمكن التراجع عن هذا الإجراء.
                                                </div>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">تأكيد الحذف</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="ti ti-users-minus display-5 text-muted mb-2"></i>
                            <div>لم يتم العثور على أي مسؤولين مطابقين لمعايير البحث.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="card-footer d-flex align-items-center justify-content-between">
            <div class="text-secondary small">
                عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من إجمالي {{ $users->total() }} مسؤول
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
