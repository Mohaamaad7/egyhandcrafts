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
                <form action="{{ route('admin.profile.update') }}" method="POST" autocomplete="off" novalidate>
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
                        <label class="form-label" for="job_title">المسمى الوظيفي / الأكاديمي (اختياري)</label>
                        <input type="text"
                               name="job_title"
                               id="job_title"
                               class="form-control @error('job_title') is-invalid @enderror"
                               value="{{ old('job_title', $user->job_title) }}"
                               placeholder="مثال: باحث توثيق تراثي / مدير المشروع">
                        <small class="form-hint">يظهر هذا المسمى بجوار اسمك في الترويسة وشارات الحساب.</small>
                        @error('job_title')
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
                        <div id="username-live-msg" class="small mt-1" style="display: none;"></div>
                        <small class="form-hint">يُستخدم لتسجيل الدخول كبديل عن البريد الإلكتروني.</small>
                        @error('username')
                            <div class="invalid-feedback server-error d-block">{{ $message }}</div>
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
                <form action="{{ route('admin.profile.password') }}" method="POST" autocomplete="off" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label required" for="current_password">كلمة المرور الحالية</label>
                        <div class="input-group input-group-flat">
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="أدخل كلمة المرور الحالية للتأكيد"
                                   autocomplete="current-password"
                                   required>
                            <span class="input-group-text">
                                <a href="javascript:void(0)" class="link-secondary toggle-password-btn" data-target="current_password" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                    <i class="ti ti-eye fs-2"></i>
                                </a>
                            </span>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback server-error d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required" for="new_password">كلمة المرور الجديدة</label>
                        <div class="input-group input-group-flat">
                            <input type="password"
                                   name="password"
                                   id="new_password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="8 أحرف على الأقل"
                                   autocomplete="new-password"
                                   required>
                            <span class="input-group-text">
                                <a href="javascript:void(0)" class="link-secondary toggle-password-btn" data-target="new_password" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                    <i class="ti ti-eye fs-2"></i>
                                </a>
                            </span>
                        </div>
                        <div id="new-password-live-msg" class="small mt-1" style="display: none;"></div>
                        @error('password')
                            <div class="invalid-feedback server-error d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label required" for="new_password_confirmation">تأكيد كلمة المرور الجديدة</label>
                        <div class="input-group input-group-flat">
                            <input type="password"
                                   name="password_confirmation"
                                   id="new_password_confirmation"
                                   class="form-control"
                                   placeholder="أعد إدخال كلمة المرور الجديدة"
                                   autocomplete="new-password"
                                   required>
                            <span class="input-group-text">
                                <a href="javascript:void(0)" class="link-secondary toggle-password-btn" data-target="new_password_confirmation" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                    <i class="ti ti-eye fs-2"></i>
                                </a>
                            </span>
                        </div>
                        <div id="new-password-confirm-live-msg" class="small mt-1" style="display: none;"></div>
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

    {{-- Third Card (Super Admin Only): Dynamic Admin Route Prefix Configuration --}}
    @if ($user->isSuperAdmin())
        <div class="col-12">
            <div class="card shadow-xs border-0 border-start border-primary border-3">
                <div class="card-header py-3 d-flex align-items-center justify-content-between bg-light-subtle">
                    <div>
                        <h3 class="card-title fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-route text-primary fs-2"></i>
                            <span>مسار لوحة التحكم المخصص (Admin Route Prefix)</span>
                        </h3>
                        <div class="text-secondary small mt-1">تعديل رابط الدخول إلى لوحة الإدارة لتحسين الأمان والحماية ضد محاولات المسح العشوائي.</div>
                    </div>
                    <span class="badge bg-purple-lt fw-bold">خاص بمدير النظام</span>
                </div>

                <div class="card-body p-4">
                    <div class="row align-items-center mb-4 g-3">
                        <div class="col-md-7">
                            <div class="alert alert-info d-flex align-items-start gap-2 mb-0" role="alert">
                                <i class="ti ti-info-circle fs-2 mt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">تنبيه أمني هام:</div>
                                    <div class="small">
                                        عند تغيير مسار لوحة التحكم، سيتم نقل جميع روابط الإدارة فوراً إلى المسار الجديد، وسيعود المسار القديم برمز خطأ <code>404 Not Found</code> لحجب لوحة التحكم عن الزوار غير المصرح لهم.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-3 border text-center">
                                <div class="text-secondary small mb-1">المسار الإداري النشط حالياً:</div>
                                <code class="fs-3 fw-bold text-primary dir-ltr d-block">{{ url($currentAdminPath ?? admin_path()) }}</code>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.settings') }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label required" for="admin_path">مسار لوحة التحكم الجديد (Prefix)</label>
                                <div class="input-group">
                                    <span class="input-group-text dir-ltr bg-light-subtle text-secondary">{{ url('/') }}/</span>
                                    <input type="text"
                                           name="admin_path"
                                           id="admin_path"
                                           class="form-control dir-ltr text-start fw-bold @error('admin_path') is-invalid @enderror"
                                           value="{{ old('admin_path', $currentAdminPath ?? admin_path()) }}"
                                           placeholder="admin"
                                           pattern="[a-zA-Z0-9_\-]+"
                                           minlength="2"
                                           maxlength="50"
                                           required>
                                </div>
                                <small class="form-hint">أحرف إنجليزية وأرقام وشرطات فقط بدون مسافات (مثال: <code>secret-portal</code> أو <code>cp</code>).</small>
                                @error('admin_path')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-device-floppy"></i>
                                    <span>تحديث وتفعيل المسار فوراً</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
