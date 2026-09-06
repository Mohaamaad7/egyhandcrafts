@extends('admin.layout')

@section('title', 'الملف الشخصي وكلمة المرور')
@section('page_title', 'الملف الشخصي وإعدادات الحساب')

@section('content')
<div class="row g-4">
    {{-- Left/First Card: Personal Profile Information --}}
    <div class="col-lg-6">
        <div class="card shadow-xs border-0 h-100">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h3 class="card-title fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-user text-primary"></i>
                    <span>البيانات الأساسية للحساب</span>
                </h3>
                <span class="badge bg-primary-lt">{{ $user->role_label }}</span>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.profile.update') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 text-center pb-3 border-bottom">
                        <span class="avatar avatar-xl rounded-circle bg-primary text-white fs-1 fw-bold shadow-xs mb-2">
                            {{ $user->initials }}
                        </span>
                        <div class="fw-bold text-dark fs-3">{{ $user->name }}</div>
                        <div class="text-secondary small dir-ltr"><code>{{ '@' . ($user->username ?? 'admin') }}</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required" for="name">الاسم الكامل</label>
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

                    <div class="mb-3">
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
                        <small class="form-hint">يُستخدم لتسجيل الدخول كبديل عن البريد الإلكتروني.</small>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
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

                    <div class="d-flex justify-content-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="ti ti-check"></i>
                            <span>تحديث البيانات الشخصية</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Right/Second Card: Security & Password Update --}}
    <div class="col-lg-6">
        <div class="card shadow-xs border-0 h-100">
            <div class="card-header py-3">
                <h3 class="card-title fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-shield-lock text-warning"></i>
                    <span>الأمان وتغيير كلمة المرور</span>
                </h3>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.profile.password') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label required" for="current_password">كلمة المرور الحالية</label>
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="أدخل كلمة المرور الحالية للتأكيد"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required" for="new_password">كلمة المرور الجديدة</label>
                        <input type="password"
                               name="password"
                               id="new_password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="8 أحرف على الأقل"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label required" for="new_password_confirmation">تأكيد كلمة المرور الجديدة</label>
                        <input type="password"
                               name="password_confirmation"
                               id="new_password_confirmation"
                               class="form-control"
                               placeholder="أعد إدخال كلمة المرور الجديدة"
                               required>
                    </div>

                    <div class="d-flex justify-content-end pt-3 border-top">
                        <button type="submit" class="btn btn-warning d-inline-flex align-items-center gap-1 text-dark fw-bold">
                            <i class="ti ti-key"></i>
                            <span>تحديث كلمة المرور</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
