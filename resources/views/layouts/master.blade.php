<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('velzon.direction', 'rtl') }}"
    data-layout="vertical"
    data-topbar="dark"
    data-sidebar="dark"
    data-sidebar-size="{{ config('velzon.data_sidebar_size', 'lg') }}"
    data-sidebar-image="none"
    data-preloader="disable"
    data-layout-style="default"
    data-layout-width="fluid"
    data-layout-position="fixed"
    data-bs-theme="dark"
>

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | EngPis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="مارکت‌پلیس تخصصی پروژه‌های مهندسی" name="description" />
    <meta content="EngPis" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    <!-- Vazirmatn Font -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @include('layouts.head-css')
    <style>
        /* ═══════════════════════════════════════════════════
           EngPis Custom Theme — Dark Navy + Teal
        ═══════════════════════════════════════════════════ */
        * { font-family: 'Vazirmatn', sans-serif !important; }

        :root {
            --ep-bg:         #0b1d33;
            --ep-bg-2:       #0f2340;
            --ep-sidebar:    #091628;
            --ep-topbar:     #0f2340;
            --ep-card:       #132b47;
            --ep-card-2:     #1a3356;
            --ep-accent:     #00d4aa;
            --ep-accent-2:   #00b090;
            --ep-accent-glow: rgba(0,212,170,0.18);
            --ep-border:     rgba(0,212,170,0.1);
            --ep-border-2:   rgba(255,255,255,0.07);
            --ep-text:       #dce8f5;
            --ep-muted:      rgba(220,232,245,0.5);
        }

        /* ─── Body & Layout ─────────────────────────────── */
        body { background: var(--ep-bg) !important; color: var(--ep-text) !important; }
        #layout-wrapper { background: var(--ep-bg) !important; }
        .main-content { background: var(--ep-bg) !important; }
        .page-content { background: var(--ep-bg) !important; padding-top: 90px !important; }

        /* ─── Topbar ────────────────────────────────────── */
        #page-topbar {
            background: var(--ep-topbar) !important;
            border-bottom: 1px solid var(--ep-border) !important;
            box-shadow: 0 2px 24px rgba(0,0,0,0.3) !important;
        }
        .hamburger-icon span { background: rgba(220,232,245,0.7) !important; }
        .navbar-header .btn-ghost-secondary {
            color: rgba(220,232,245,0.65) !important;
        }
        .navbar-header .btn-ghost-secondary:hover {
            background: var(--ep-accent-glow) !important;
            color: var(--ep-accent) !important;
        }
        .user-name-text { color: var(--ep-text) !important; font-weight: 600 !important; }
        .user-name-sub-text { color: var(--ep-muted) !important; }
        .topbar-user .btn { color: var(--ep-text) !important; }
        .avatar-title.bg-primary-subtle {
            background: var(--ep-accent-glow) !important;
            color: var(--ep-accent) !important;
            font-weight: 700 !important;
        }

        /* Topbar dropdown */
        .dropdown-menu {
            background: var(--ep-card-2) !important;
            border: 1px solid var(--ep-border) !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.35) !important;
        }
        .dropdown-header { color: var(--ep-muted) !important; border-bottom: 1px solid var(--ep-border) !important; }
        .dropdown-item { color: var(--ep-text) !important; }
        .dropdown-item:hover { background: var(--ep-accent-glow) !important; color: var(--ep-accent) !important; }
        .dropdown-divider { border-color: var(--ep-border) !important; }

        /* Topbar action buttons */
        .btn-primary {
            background: var(--ep-accent) !important;
            border-color: var(--ep-accent) !important;
            color: #091628 !important;
            font-weight: 700 !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--ep-accent-2) !important;
            border-color: var(--ep-accent-2) !important;
            box-shadow: 0 6px 20px rgba(0,212,170,0.3) !important;
            color: #091628 !important;
        }
        .btn-success {
            background: #1a7a52 !important;
            border-color: #1a7a52 !important;
            color: #d0faf0 !important;
            font-weight: 700 !important;
        }
        .btn-success:hover {
            background: #148a5a !important;
            border-color: #148a5a !important;
        }
        .btn-outline-secondary {
            border-color: var(--ep-border-2) !important;
            color: var(--ep-muted) !important;
        }
        .btn-outline-secondary:hover {
            background: var(--ep-accent-glow) !important;
            border-color: var(--ep-accent) !important;
            color: var(--ep-accent) !important;
        }
        .btn-soft-primary {
            background: var(--ep-accent-glow) !important;
            color: var(--ep-accent) !important;
            border: none !important;
        }
        .btn-soft-primary:hover { background: rgba(0,212,170,0.25) !important; color: var(--ep-accent) !important; }
        .btn-soft-success {
            background: rgba(26,122,82,0.15) !important;
            color: #5ddfb0 !important;
            border: none !important;
        }
        .btn-soft-success:hover { background: rgba(26,122,82,0.25) !important; color: #5ddfb0 !important; }
        .btn-sm { font-size: 0.78rem !important; }

        /* ─── Sidebar ───────────────────────────────────── */
        .app-menu, .navbar-menu {
            background: linear-gradient(180deg, var(--ep-sidebar) 0%, #060e1c 100%) !important;
        }
        [dir="rtl"] .app-menu { border-left: 1px solid var(--ep-border) !important; }
        [dir="ltr"] .app-menu { border-right: 1px solid var(--ep-border) !important; }

        .navbar-brand-box {
            background: transparent !important;
            border-bottom: 1px solid var(--ep-border) !important;
        }

        /* Hide old logo images */
        .navbar-brand-box .logo img { display: none !important; }
        .navbar-brand-box .logo-sm img { display: none !important; }
        .navbar-brand-box .logo-lg img { display: none !important; }

        /* Sidebar menu items */
        .navbar-menu .navbar-nav .nav-link {
            color: rgba(220,232,245,0.6) !important;
            border-radius: 8px !important;
            margin: 1px 8px !important;
            transition: all 0.2s ease !important;
        }
        .navbar-menu .navbar-nav .nav-link:hover {
            color: var(--ep-accent) !important;
            background: var(--ep-accent-glow) !important;
        }
        .navbar-menu .navbar-nav .nav-link.active {
            color: var(--ep-accent) !important;
            background: var(--ep-accent-glow) !important;
            font-weight: 600 !important;
        }
        .navbar-menu .navbar-nav .nav-link i { color: inherit !important; }

        .menu-title { padding: 16px 20px 6px !important; }
        .menu-title span {
            color: rgba(220,232,245,0.28) !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.12em !important;
            text-transform: uppercase !important;
        }

        /* Sidebar background overlay */
        .sidebar-background { display: none !important; }

        #vertical-hover { color: rgba(220,232,245,0.4) !important; }
        #vertical-hover:hover { color: var(--ep-accent) !important; }

        /* ─── Cards ─────────────────────────────────────── */
        .card {
            background: var(--ep-card) !important;
            border: 1px solid var(--ep-border) !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2) !important;
            border-radius: 14px !important;
        }
        .card:hover { border-color: rgba(0,212,170,0.18) !important; }
        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--ep-border) !important;
            padding: 1rem 1.25rem !important;
        }
        .card-title { color: var(--ep-text) !important; font-weight: 600 !important; }
        .card-text { color: var(--ep-muted) !important; }
        .card-body { color: var(--ep-text) !important; }
        .card-animate:hover { box-shadow: 0 8px 32px rgba(0,212,170,0.12) !important; transform: translateY(-2px); }
        .card.border-dashed {
            border-style: dashed !important;
            border-color: rgba(0,212,170,0.22) !important;
        }

        /* ─── Stat numbers ──────────────────────────────── */
        .fs-22.fw-semibold { color: var(--ep-accent) !important; }
        .ff-secondary { font-weight: 700 !important; }

        /* Stat icon avatars */
        .avatar-sm .avatar-title {
            border-radius: 10px !important;
        }
        .bg-primary-subtle { background: rgba(0,212,170,0.1) !important; }
        .bg-success-subtle { background: rgba(26,122,82,0.15) !important; }
        .bg-info-subtle    { background: rgba(3,169,244,0.1) !important; }
        .bg-warning-subtle { background: rgba(255,190,0,0.1) !important; }
        .text-primary { color: var(--ep-accent) !important; }
        .text-success { color: #5ddfb0 !important; }
        .text-info    { color: #60c8f5 !important; }
        .text-warning { color: #ffd43b !important; }

        /* ─── Tables ────────────────────────────────────── */
        .table { color: var(--ep-text) !important; }
        .table > :not(caption) > * > * {
            background: transparent !important;
            border-bottom-color: var(--ep-border) !important;
            color: var(--ep-text) !important;
        }
        .table thead th {
            color: var(--ep-muted) !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
        }

        /* ─── Forms ─────────────────────────────────────── */
        .form-control, .form-select {
            background: rgba(255,255,255,0.04) !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-text) !important;
            border-radius: 8px !important;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.06) !important;
            border-color: var(--ep-accent) !important;
            box-shadow: 0 0 0 3px rgba(0,212,170,0.12) !important;
            color: var(--ep-text) !important;
        }
        .form-control::placeholder { color: var(--ep-muted) !important; }
        .form-label { color: var(--ep-text) !important; font-weight: 500 !important; }
        .form-text  { color: var(--ep-muted) !important; }
        .input-group-text {
            background: rgba(255,255,255,0.04) !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-muted) !important;
        }

        /* ─── Badges ────────────────────────────────────── */
        .badge.bg-warning         { background: rgba(255,190,0,0.18) !important; color: #ffd43b !important; }
        .badge.bg-primary-subtle  { background: rgba(0,212,170,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle  { background: rgba(26,122,82,0.15) !important; color: #5ddfb0 !important; }
        .badge.bg-primary         { background: var(--ep-accent) !important; color: #091628 !important; }
        .badge.bg-success         { background: #1a7a52 !important; }

        /* ─── Alerts ────────────────────────────────────── */
        .alert-info {
            background: rgba(3,169,244,0.08) !important;
            border-color: rgba(3,169,244,0.18) !important;
            color: #7dd3fc !important;
        }
        .alert-link { color: var(--ep-accent) !important; }

        /* ─── Breadcrumb ────────────────────────────────── */
        .page-title-box h4 { color: var(--ep-text) !important; }
        .breadcrumb-item, .breadcrumb-item a { color: var(--ep-muted) !important; }
        .breadcrumb-item.active { color: var(--ep-text) !important; }
        .breadcrumb-item a:hover { color: var(--ep-accent) !important; }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--ep-muted) !important; }

        /* ─── Text utils ────────────────────────────────── */
        .text-muted   { color: var(--ep-muted) !important; }
        h1, h2, h3, h4, h5, h6 { color: var(--ep-text) !important; }
        a             { color: var(--ep-accent) !important; }
        a:hover       { color: #00f5c5 !important; }
        .text-decoration-underline { color: var(--ep-accent) !important; }
        .fw-medium    { font-weight: 500 !important; }

        /* ─── Footer ────────────────────────────────────── */
        .footer {
            background: var(--ep-sidebar) !important;
            border-top: 1px solid var(--ep-border) !important;
            color: var(--ep-muted) !important;
        }
        .footer a { color: var(--ep-muted) !important; }
        .footer a:hover { color: var(--ep-accent) !important; }

        /* ─── Modal ─────────────────────────────────────── */
        .modal-content {
            background: var(--ep-card-2) !important;
            border: 1px solid var(--ep-border) !important;
        }
        .modal-header {
            border-bottom: 1px solid var(--ep-border) !important;
            color: var(--ep-text) !important;
        }
        .modal-footer { border-top: 1px solid var(--ep-border) !important; }
        .btn-close { filter: invert(1) brightness(0.6) !important; }

        /* ─── Pagination ────────────────────────────────── */
        .page-link {
            background: var(--ep-card) !important;
            border-color: var(--ep-border) !important;
            color: var(--ep-text) !important;
        }
        .page-link:hover { background: var(--ep-accent-glow) !important; color: var(--ep-accent) !important; }
        .page-item.active .page-link { background: var(--ep-accent) !important; border-color: var(--ep-accent) !important; color: #091628 !important; }
        .page-item.disabled .page-link { background: var(--ep-card) !important; color: var(--ep-muted) !important; }

        /* ─── Scrollbar ─────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--ep-bg); }
        ::-webkit-scrollbar-thumb { background: rgba(0,212,170,0.25); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--ep-accent); }

        /* ─── Simplebar ─────────────────────────────────── */
        .simplebar-scrollbar::before { background: rgba(0,212,170,0.3) !important; }

        /* ─── Vertical overlay (mobile) ─────────────────── */
        .vertical-overlay { background: rgba(0,0,0,0.55) !important; }

        /* ─── Role badge in sidebar ─────────────────────── */
        .badge.bg-primary-subtle.text-primary { background: rgba(0,212,170,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle.text-success { background: rgba(26,122,82,0.15) !important; color: #5ddfb0 !important; }

        /* ─── Switch/Toggle ─────────────────────────────── */
        .form-check-input:checked {
            background-color: var(--ep-accent) !important;
            border-color: var(--ep-accent) !important;
        }

        /* ═══ RESPONSIVE ═══════════════════════════════════════════════════
           Mobile  < 768px
        ═══════════════════════════════════════════════════════════════════ */
        @media (max-width: 767.98px) {
            /* Page content – less top padding, tighter sides */
            .page-content {
                padding-top: 72px !important;
                padding-right: 0.625rem !important;
                padding-left:  0.625rem !important;
            }

            /* Cards */
            .card          { border-radius: 10px !important; }
            .card-body     { padding: 0.875rem !important; }
            .card-header   { padding: 0.75rem 0.875rem !important; }
            .card-animate:hover { transform: none !important; }

            /* Tables */
            .table { font-size: 0.82rem !important; }
            .table > :not(caption) > * > * { padding: 0.45rem 0.4rem !important; }

            /* Buttons */
            .btn    { font-size: 0.82rem !important; }
            .btn-sm { padding: 0.25rem 0.6rem !important; font-size: 0.78rem !important; }

            /* Page title */
            .page-title-box h4 { font-size: 1rem !important; }
            .breadcrumb        { font-size: 0.78rem !important; }

            /* Footer: center on mobile */
            .footer .row        { text-align: center; }
            .footer .text-sm-end { text-align: center !important; display: block !important; }

            /* Stat numbers – slightly smaller */
            .ff-secondary.fs-25 { font-size: 1.5rem !important; }

            /* Form actions row – stack and full-width */
            .ep-form-actions {
                flex-direction: column-reverse !important;
                align-items: stretch !important;
            }
            .ep-form-actions .btn { width: 100% !important; }

            /* Welcome banner – stack on mobile */
            .ep-welcome-body {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }
            .ep-welcome-body .d-flex.gap-2 { width: 100%; }
            .ep-welcome-body .d-flex.gap-2 .btn { flex: 1; }
        }

        /* ═══ RESPONSIVE ═══════════════════════════════════════════════════
           Hide secondary topbar icons on phones (< 576px)
        ═══════════════════════════════════════════════════════════════════ */
        @media (max-width: 575.98px) {
            .ep-topbar-icons { display: none !important; }
        }

        /* ═══ RESPONSIVE ═══════════════════════════════════════════════════
           Tablet  768px – 1024px
        ═══════════════════════════════════════════════════════════════════ */
        @media (min-width: 768px) and (max-width: 1024px) {
            .page-content {
                padding-top: 80px !important;
                padding-right: 1rem !important;
                padding-left:  1rem !important;
            }
            .card-body   { padding: 1rem !important; }
            .card-header { padding: 0.875rem 1rem !important; }
        }
    </style>

    @stack('styles')
</head>

@section('body')
    @include('layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    {{-- Customizer disabled: EngPis uses a fixed dark theme --}}

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
