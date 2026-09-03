{{--
    Admin Master Layout — Tabler (Bootstrap 5 based).
    NOTE: AdminLTE is NOT used. Tabler assets are loaded via CDN as a placeholder;
    a proper npm/Vite build will replace the CDN references later.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') | {{ config('app.name') }}</title>

    {{-- Tabler Core CSS (placeholder CDN — TODO: pin version + bundle via Vite) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
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
    </style>

    @stack('styles')
</head>
<body>
    <div class="page">
        {{-- Sidebar --}}
        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminSidebarMenu" aria-controls="adminSidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                    <span>لوحة التحكم</span>
                </h1>
                <div class="collapse navbar-collapse" id="adminSidebarMenu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <span class="nav-link-icon"><i class="ti ti-home"></i></span>
                                <span class="nav-link-title">الرئيسية</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.crafts.*') ? 'active' : '' }}" href="{{ route('admin.crafts.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-tools"></i></span>
                                <span class="nav-link-title">دليل الحرف</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.workshops.*') ? 'active' : '' }}" href="{{ route('admin.workshops.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-map-pin"></i></span>
                                <span class="nav-link-title">ورش الحرف (الخريطة)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Page wrapper --}}
        <div class="page-wrapper">
            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-xl d-flex align-items-center">
                    <div class="page-title">@yield('page_title', 'لوحة التحكم')</div>
                    <div class="ms-auto">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-logout me-1"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    {{-- Tabler Core JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js" defer></script>
    @stack('scripts')
</body>
</html>
