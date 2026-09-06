@extends('admin.layout')

@section('title', 'تعديل بيانات المسؤول')
@section('page_title', 'تعديل بيانات المسؤول')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-xs border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm rounded-circle bg-primary-lt text-primary fw-bold">
                        {{ $user->initials }}
                    </span>
                    <div>
                        <h3 class="card-title fw-bold text-primary mb-0">تعديل حساب: {{ $user->name }}</h3>
                        <div class="text-secondary small"><code>{{ '@' . ($user->username ?? '—') }}</code></div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <div class="card-body p-4">
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <i class="ti ti-alert-circle fs-2 me-2 mt-1"></i>
                            <div>
                                <div class="fw-bold mb-1">يرجى تصحيح الأخطاء التالية:</div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></a>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST" autocomplete="off" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required" for="name">الاسم بالكامل</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="job_title">المسمى الوظيفي / الأكاديمي (اختياري)</label>
                            <input type="text"
                                   name="job_title"
                                   id="job_title"
                                   class="form-control @error('job_title') is-invalid @enderror"
                                   value="{{ old('job_title', $user->job_title) }}"
                                   placeholder="مثال: باحث توثيق ميداني / مسؤول إعلامي">
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required" for="username">اسم المستخدم (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text"
                                       name="username"
                                       id="username"
                                       class="form-control dir-ltr text-end @error('username') is-invalid @enderror"
                                       value="{{ old('username', $user->username) }}"
                                       required>
                            </div>
                            <div id="username-live-msg" class="small mt-1" style="display: none;"></div>
                            @error('username')
                                <div class="invalid-feedback server-error d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required" for="email">البريد الإلكتروني</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control dir-ltr text-end @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label required" for="role">الصلاحية والدور في النظام</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>مسؤول نظام (Admin) — إدارة الحرف والورش والقصص</option>
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>مدير النظام (Super Admin) — صلاحية كاملة تشمل إدارة المستخدمين وإعدادات المسار</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Admin Password Override / Reset Section --}}
                    <div class="bg-light p-3 rounded-2 border mb-4">
                        <h4 class="text-dark fw-bold mb-2 d-flex align-items-center gap-1">
                            <i class="ti ti-key text-warning"></i>
                            <span>إعادة تعيين كلمة المرور لهذا الحساب</span>
                        </h4>
                        <p class="text-secondary small mb-3">اترك الحقول فارغة إذا كنت لا ترغب في تغيير كلمة المرور الحالية.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="password">كلمة المرور الجديدة</label>
                                <div class="input-group input-group-flat">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="اتركها فارغة لعدم التغيير"
                                           autocomplete="new-password">
                                    <span class="input-group-text">
                                        <a href="javascript:void(0)" class="link-secondary toggle-password-btn" data-target="password" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                            <i class="ti ti-eye fs-2"></i>
                                        </a>
                                    </span>
                                </div>
                                <div id="password-live-msg" class="small mt-1" style="display: none;"></div>
                                @error('password')
                                    <div class="invalid-feedback server-error d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                                <div class="input-group input-group-flat">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control"
                                           placeholder="أعد كتابة كلمة المرور الجديدة"
                                           autocomplete="new-password">
                                    <span class="input-group-text">
                                        <a href="javascript:void(0)" class="link-secondary toggle-password-btn" data-target="password_confirmation" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                            <i class="ti ti-eye fs-2"></i>
                                        </a>
                                    </span>
                                </div>
                                <div id="password-confirm-live-msg" class="small mt-1" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light">إلغاء</a>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="ti ti-check"></i>
                            <span>حفظ التعديلات</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
