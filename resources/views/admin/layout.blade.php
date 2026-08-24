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
                            <a class="nav-link" href="#">
                                <span class="nav-link-icon"><i class="ti ti-home"></i></span>
                                <span class="nav-link-title">الرئيسية</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Page wrapper --}}
        <div class="page-wrapper">
            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-xl">
                    <div class="page-title">@yield('page_title', 'لوحة التحكم')</div>
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
