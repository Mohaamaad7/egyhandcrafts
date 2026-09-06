<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول الإداري | مشروع توثيق الحرف التراثية</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cairo:wght@500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    {{-- Tabler RTL Core CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.rtl.min.css">
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f6f8fb;
            background-image: radial-gradient(#d5dfeb 0.75px, transparent 0.75px), radial-gradient(#d5dfeb 0.75px, #f6f8fb 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            min-height: 100vh;
        }
        .heading-font {
            font-family: 'Cairo', sans-serif;
        }
        .serif-font {
            font-family: 'Amiri', serif;
        }
        .heritage-card {
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-top: 4px solid #b45309;
            box-shadow: 0 10px 25px -5px rgba(26, 47, 76, 0.08), 0 8px 10px -6px rgba(26, 47, 76, 0.04);
            border-radius: 1rem;
            background: #ffffff;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1A2F4C 0%, #264268 100%);
            border-color: #1A2F4C;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0E1B2D 0%, #1A2F4C 100%);
            border-color: #0E1B2D;
        }
        .form-check-input:checked {
            background-color: #1A2F4C;
            border-color: #1A2F4C;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>
<body class="d-flex flex-column justify-content-center py-4">
    <div class="page page-center">
        <div class="container container-tight py-4">

            {{-- Academic Heritage Header --}}
            <div class="text-center mb-4">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                    <img src="{{ asset('assets/images/university_logo.png') }}" alt="جامعة مدينة السادات" class="h-10 w-auto object-contain" style="max-height: 52px;">
                    <span class="vr bg-secondary opacity-25" style="height: 38px;"></span>
                    <img src="{{ asset('assets/images/colledge_logo.png') }}" alt="كلية السياحة والفنادق" class="h-10 w-auto object-contain" style="max-height: 52px;">
                    <span class="vr bg-secondary opacity-25" style="height: 38px;"></span>
                    <img src="{{ asset('assets/images/project_logo.png') }}" alt="مشروع التوثيق" class="h-10 w-auto object-contain" style="max-height: 52px;">
                </div>
                <h1 class="heading-font text-primary h2 mb-1 fw-bold">بوابة الإدارة والتوثيق الأكاديمي</h1>
                <p class="text-secondary small mb-0 serif-font fs-3">مشروع توثيق الحرف التراثية بمحافظة المنوفية — جامعة مدينة السادات</p>
            </div>

            {{-- Main Login Card --}}
            <div class="card heritage-card">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <h2 class="card-title heading-font h3 mb-0 text-primary">تسجيل الدخول للمنظومة</h2>
                        <span class="badge bg-primary-lt px-2 py-1">لوحة الإدارة</span>
                    </div>

                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="ti ti-alert-circle fs-2 me-2 mt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">تعذر تسجيل الدخول</div>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-check fs-2 me-2"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" autocomplete="off">
                        @csrf

                        {{-- Dual Identity Field: Email OR Username --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="loginInput">
                                اسم المستخدم أو البريد الإلكتروني
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <i class="ti ti-user text-secondary"></i>
                                </span>
                                <input type="text"
                                       name="email"
                                       id="loginInput"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="أدخل اسم المستخدم أو البريد الإلكتروني"
                                       autocomplete="username"
                                       required
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password Field with Show/Hide Toggle --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold" for="passwordInput">
                                كلمة المرور
                            </label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">
                                    <i class="ti ti-lock text-secondary"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       id="passwordInput"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••"
                                       autocomplete="current-password"
                                       required>
                                <span class="input-group-text">
                                    <a href="javascript:void(0)" id="togglePasswordBtn" class="link-secondary" title="إظهار / إخفاء كلمة المرور" tabindex="-1">
                                        <i class="ti ti-eye fs-2" id="passwordIcon"></i>
                                    </a>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Proper RTL Remember Me & Password Recovery Link --}}
                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <label class="form-check m-0 d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" id="rememberCheck" class="form-check-input m-0">
                                <span class="form-check-label user-select-none text-secondary">تذكرني</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small fw-medium">
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fs-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-3">
                            <i class="ti ti-login fs-2"></i>
                            <span>دخول إلى لوحة التحكم</span>
                        </button>
                    </form>

                    {{-- Return to Portal Link --}}
                    <div class="text-center pt-3 border-top">
                        <a href="{{ route('home') }}" class="text-secondary text-decoration-none small d-inline-flex align-items-center gap-1 hover-underline">
                            <i class="ti ti-arrow-narrow-right"></i>
                            <span>العودة إلى البوابة العامة للمشروع</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="text-center mt-3 text-secondary small">
                جامعة مدينة السادات &copy; {{ date('Y') }} — جميع الحقوق محفوظة
            </div>
        </div>
    </div>

    {{-- Tabler Core JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('passwordInput');
            const passwordIcon = document.getElementById('passwordIcon');

            if (toggleBtn && passwordInput && passwordIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    passwordIcon.className = isPassword ? 'ti ti-eye-off fs-2' : 'ti ti-eye fs-2';
                });
            }
        });
    </script>
</body>
</html>
