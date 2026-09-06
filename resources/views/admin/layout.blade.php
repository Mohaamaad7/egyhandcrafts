{{--
    Admin Master Layout — Tabler (Bootstrap 5 based) with True RTL & Academic Heritage Theme.
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') | {{ config('app.name') }}</title>

    {{-- Google Fonts: Cairo & Tajawal --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    {{-- Tabler RTL Core CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.rtl.min.css">
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        /* Typography System */
        body, .btn, .form-control, .form-select, .card-title, .table, .dropdown-menu {
            font-family: 'Tajawal', system-ui, -apple-system, sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand, .page-title, .heading-font {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif;
        }

        /* Prevent layout blowout & horizontal window scroll */
        html, body {
            overflow-x: clip;
            max-width: 100vw;
        }
        .page, .page-wrapper {
            overflow-x: clip;
            max-width: 100%;
        }
        .container-xl, .card, .card-body {
            min-width: 0;
            max-width: 100%;
        }

        /* True Right-Anchored Vertical Sidebar in RTL */
        @media (min-width: 992px) {
            .navbar-vertical {
                position: fixed;
                top: 0;
                bottom: 0;
                right: 0 !important;
                left: auto !important;
                width: 16rem !important;
                border-left: 1px solid rgba(255, 255, 255, 0.08) !important;
                border-right: none !important;
                z-index: 1030;
            }
            .page-wrapper {
                margin-right: 16rem !important;
                margin-left: 0 !important;
            }
        }

        /* Mobile / Tablet RTL Sidebar Behavior (<992px) */
        @media (max-width: 991.98px) {
            .navbar-vertical {
                position: relative;
                width: 100% !important;
                border-left: none !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            }
            .navbar-vertical .container-fluid {
                display: flex;
                flex-direction: row-reverse;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 1rem;
            }
            .navbar-vertical .admin-brand-box {
                padding: 0.25rem 0 !important;
                border-bottom: none !important;
                width: auto !important;
            }
            .offcanvas-lg.offcanvas-start {
                --tblr-offcanvas-width: 17rem;
                box-shadow: -5px 0 25px rgba(0, 0, 0, 0.5) !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            .page-wrapper {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
        }

        /* Sidebar Navigation Active States with Gold Accent */
        .navbar-vertical .nav-item .nav-link {
            border-radius: 6px;
            margin: 2px 10px;
            padding: 8px 12px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
        }
        .navbar-vertical .nav-item .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }
        .navbar-vertical .nav-item .nav-link.active {
            color: #ffffff !important;
            background: rgba(212, 175, 55, 0.16) !important;
            border-right: 3px solid #D4AF37 !important;
            border-left: none !important;
            font-weight: 700;
        }
        .navbar-vertical .nav-item .nav-link.active .nav-link-icon {
            color: #D4AF37 !important;
        }

        /* Academic Brand in Sidebar */
        .admin-brand-box {
            padding: 1rem 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Header visit button */
        .btn-visit-portal {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .btn-visit-portal:hover {
            background-color: #1A2F4C;
            border-color: #1A2F4C;
            color: #ffffff;
        }

        /* Avatar Initials Badge */
        .avatar-initials {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            background: linear-gradient(135deg, #1A2F4C 0%, #264268 100%);
            color: #ffffff;
            border: 2px solid rgba(212, 175, 55, 0.4);
        }

        /* User Profile Dropdown RTL Viewport Overflow Guard */
        .dropdown-menu-end[data-bs-popper] {
            right: auto !important;
            left: 0 !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="page">
        {{-- Right-Anchored Sidebar (RTL) --}}
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark bg-dark" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMenu" aria-controls="adminSidebarMenu" aria-label="تبديل القائمة">
                    <span class="navbar-toggler-icon"></span>
                </button>

                {{-- Academic Project Brand --}}
                <div class="admin-brand-box w-100 text-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('assets/images/project_logo.png') }}" alt="مشروع التوثيق" style="max-height: 40px; width: auto;">
                        <div class="text-end">
                            <div class="fw-bold text-white fs-4 lh-sm">لوحة التوثيق</div>
                            <div class="text-secondary small" style="font-size: 0.72rem;">جامعة مدينة السادات</div>
                        </div>
                    </a>
                </div>

                {{-- Navigation Links (Responsive RTL Offcanvas on Mobile / Static on Desktop) --}}
                <div class="offcanvas-lg offcanvas-start bg-dark" tabindex="-1" id="adminSidebarMenu" aria-labelledby="adminSidebarMenuLabel">
                    {{-- Offcanvas Header (Mobile Only) --}}
                    <div class="offcanvas-header d-lg-none border-bottom border-dark-subtle px-3 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('assets/images/project_logo.png') }}" alt="مشروع التوثيق" style="max-height: 32px; width: auto;">
                            <div class="text-end">
                                <div class="fw-bold text-white fs-4 lh-sm">لوحة التوثيق</div>
                                <div class="text-secondary small" style="font-size: 0.7rem;">جامعة مدينة السادات</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebarMenu" aria-label="إغلاق"></button>
                    </div>

                    <div class="offcanvas-body d-flex flex-column p-0 flex-grow-1">
                        <ul class="navbar-nav pt-lg-3 w-100">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                                    <span class="nav-link-title">الرئيسية والمؤشرات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.crafts.*') ? 'active' : '' }}" href="{{ route('admin.crafts.index') }}">
                                    <span class="nav-link-icon"><i class="ti ti-tools"></i></span>
                                    <span class="nav-link-title">دليل الحرف التراثية</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.workshops.*') ? 'active' : '' }}" href="{{ route('admin.workshops.index') }}">
                                    <span class="nav-link-icon"><i class="ti ti-map-pin"></i></span>
                                    <span class="nav-link-title">ورش الحرف (الخريطة)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.stories.*') ? 'active' : '' }}" href="{{ route('admin.stories.index') }}">
                                    <span class="nav-link-icon"><i class="ti ti-microphone"></i></span>
                                    <span class="nav-link-title">قصص وشهادات الحرفيين</span>
                                </a>
                            </li>
                            @if (auth()->user()?->isSuperAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                        <span class="nav-link-icon"><i class="ti ti-users-group"></i></span>
                                        <span class="nav-link-title">فريق العمل والمسؤولين</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Page wrapper --}}
        <div class="page-wrapper">
            {{-- Top Header --}}
            <header class="navbar navbar-expand-md navbar-light bg-white border-bottom shadow-xs d-print-none sticky-top">
                <div class="container-xl d-flex align-items-center justify-content-between">

                    {{-- Page Title --}}
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="page-title text-primary h3 mb-0 fw-bold">
                            @yield('page_title', 'لوحة التحكم')
                        </h2>
                    </div>

                    {{-- Top Actions: Visit Portal & User Profile Dropdown --}}
                    <div class="d-flex align-items-center gap-3">

                        {{-- Visit Portal Link (WordPress Admin Bar Style) --}}
                        <a href="{{ route('home') }}"
                           target="_blank"
                           class="btn btn-visit-portal btn-sm d-flex align-items-center gap-2 shadow-sm rounded-pill px-3 py-1"
                           title="معاينة البوابة في نافذة جديدة">
                            <i class="ti ti-world fs-2 text-primary"></i>
                            <span class="d-none d-sm-inline fw-medium">معاينة البوابة</span>
                            <i class="ti ti-arrow-up-left small text-secondary"></i>
                        </a>

                        {{-- User Profile Dropdown Component --}}
                        @auth
                            <div class="nav-item dropdown">
                                <a href="javascript:void(0)" class="nav-link d-flex lh-1 text-reset p-0 cursor-pointer" data-bs-toggle="dropdown" aria-label="فتح قائمة المستخدم">
                                    <span class="avatar-initials shadow-xs">
                                        {{ auth()->user()->initials }}
                                    </span>
                                    <div class="d-none d-xl-block pe-2 text-end">
                                        <div class="fw-bold text-dark fs-4">{{ auth()->user()->name }}</div>
                                        <div class="mt-1 small text-secondary">
                                            <span class="badge bg-blue-lt py-0 px-2">{{ auth()->user()->role_label }}</span>
                                        </div>
                                    </div>
                                    <i class="ti ti-chevron-down text-secondary ms-1 fs-3"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-md border-0 rounded-3 text-end" style="min-width: 240px;">
                                    {{-- User Info Header --}}
                                    <div class="dropdown-header text-end bg-light-subtle py-2 px-3 border-bottom">
                                        <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                                        <div class="text-secondary small">{{ auth()->user()->email }}</div>
                                        <div class="text-secondary small dir-ltr text-end">@<span>{{ auth()->user()->username ?? 'admin' }}</span></div>
                                    </div>

                                    {{-- Navigation Items --}}
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.profile.edit') }}">
                                        <i class="ti ti-user-cog text-primary fs-2"></i>
                                        <span>الملف الشخصي وكلمة المرور</span>
                                    </a>

                                    @if (auth()->user()->isSuperAdmin())
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.users.index') }}">
                                            <i class="ti ti-users-group text-secondary fs-2"></i>
                                            <span>إدارة فريق المسؤولين</span>
                                        </a>
                                    @endif

                                    <div class="dropdown-divider my-1"></div>

                                    {{-- Secure CSRF Logout Action --}}
                                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 w-100 bg-transparent border-0 text-end cursor-pointer">
                                            <i class="ti ti-logout fs-2 text-danger"></i>
                                            <span class="fw-bold">تسجيل الخروج</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Main Page Body --}}
            <div class="page-body py-4">
                <div class="container-xl">
                    {{-- Global Flash Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible shadow-xs mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-check fs-2 me-2"></i>
                                <div class="fw-medium">{{ session('success') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></a>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible shadow-xs mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-circle fs-2 me-2"></i>
                                <div class="fw-medium">{{ session('error') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></a>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            {{-- Admin Footer --}}
            <footer class="footer footer-transparent d-print-none py-3 border-top bg-white text-secondary small">
                <div class="container-xl d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        مشروع توثيق الحرف التراثية بمحافظة المنوفية — كلية السياحة والفنادق، جامعة مدينة السادات &copy; {{ date('Y') }}
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span>الإصدار 2.0 (Laravel 13 + Tabler)</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Tabler Core JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js" defer></script>
    @stack('scripts')
</body>
</html>
