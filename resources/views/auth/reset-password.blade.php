<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعيين كلمة المرور الجديدة | مشروع توثيق الحرف التراثية</title>

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
    </style>
</head>
<body class="d-flex flex-column justify-content-center py-4">
    <div class="page page-center">
        <div class="container container-tight py-4">

            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                    <img src="{{ asset('assets/images/university_logo.png') }}" alt="جامعة مدينة السادات" class="h-10 w-auto object-contain" style="max-height: 48px;">
                    <span class="vr bg-secondary opacity-25" style="height: 32px;"></span>
                    <img src="{{ asset('assets/images/project_logo.png') }}" alt="مشروع التوثيق" class="h-10 w-auto object-contain" style="max-height: 48px;">
                </div>
                <h1 class="heading-font text-primary h2 mb-1 fw-bold">تعيين كلمة المرور الجديدة</h1>
                <p class="text-secondary small mb-0 serif-font fs-3">بوابة إدارة مشروع توثيق الحرف التراثية</p>
            </div>

            {{-- Card --}}
            <div class="card heritage-card">
                <div class="card-body p-4 p-md-5">

                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="ti ti-alert-circle fs-2 me-2 mt-1"></i>
                                <div>
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

                    <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold" for="email">البريد الإلكتروني</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $request->email) }}"
                                   required
                                   readonly>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" for="password">كلمة المرور الجديدة</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="8 أحرف على الأقل"
                                   required
                                   autofocus>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="أعد كتابة كلمة المرور"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-3">
                            <i class="ti ti-key fs-2"></i>
                            <span>تحديث كلمة المرور والدخول</span>
                        </button>
                    </form>

                    <div class="text-center pt-2 border-top">
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none small d-inline-flex align-items-center gap-1">
                            <i class="ti ti-arrow-narrow-right"></i>
                            <span>العودة إلى صفحة تسجيل الدخول</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 text-secondary small">
                جامعة مدينة السادات &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</body>
</html>
