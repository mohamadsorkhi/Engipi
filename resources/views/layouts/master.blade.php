<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('velzon.direction', 'rtl') }}"
    data-layout="vertical"
    data-topbar="light"
    data-sidebar="light"
    data-sidebar-size="{{ config('velzon.data_sidebar_size', 'lg') }}"
    data-sidebar-image="none"
    data-preloader="disable"
    data-layout-style="default"
    data-layout-width="fluid"
    data-layout-position="fixed"
    data-bs-theme="light"
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
           EngPis Custom Theme — Light + Teal
        ═══════════════════════════════════════════════════ */
        * { font-family: 'Vazirmatn', sans-serif !important; }

        :root {
            --ep-bg:          #f8f9fa;
            --ep-bg-2:        #f1f5f9;
            --ep-sidebar:     #ffffff;
            --ep-topbar:      #ffffff;
            --ep-card:        #ffffff;
            --ep-card-2:      #f8fafc;
            --ep-accent:      #00d4aa;
            --ep-accent-2:    #00b090;
            --ep-accent-glow: rgba(0,212,170,0.12);
            --ep-border:      #e9ecef;
            --ep-border-2:    #dee2e6;
            --ep-text:        #1e293b;
            --ep-muted:       #64748b;
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
            box-shadow: 0 1px 10px rgba(0,0,0,0.06) !important;
        }
        .hamburger-icon span { background: #64748b !important; }
        .navbar-header .btn-ghost-secondary {
            color: #64748b !important;
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
            background: #ffffff !important;
            border: 1px solid var(--ep-border) !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10) !important;
        }
        .dropdown-header { color: var(--ep-muted) !important; border-bottom: 1px solid var(--ep-border) !important; }
        .dropdown-item { color: var(--ep-text) !important; }
        .dropdown-item:hover { background: var(--ep-accent-glow) !important; color: var(--ep-accent) !important; }
        .dropdown-divider { border-color: var(--ep-border) !important; }

        /* Topbar action buttons */
        .btn-primary {
            background: var(--ep-accent) !important;
            border-color: var(--ep-accent) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--ep-accent-2) !important;
            border-color: var(--ep-accent-2) !important;
            box-shadow: 0 6px 20px rgba(0,212,170,0.28) !important;
            color: #ffffff !important;
        }
        .btn-success {
            background: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .btn-success:hover {
            background: #047857 !important;
            border-color: #047857 !important;
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
        .btn-soft-primary:hover { background: rgba(0,212,170,0.20) !important; color: var(--ep-accent) !important; }
        .btn-soft-success {
            background: rgba(5,150,105,0.10) !important;
            color: #059669 !important;
            border: none !important;
        }
        .btn-soft-success:hover { background: rgba(5,150,105,0.18) !important; color: #047857 !important; }
        .btn-sm { font-size: 0.78rem !important; }

        /* ─── Sidebar ───────────────────────────────────── */
        .app-menu, .navbar-menu {
            background: var(--ep-sidebar) !important;
        }
        [dir="rtl"] .app-menu { border-left: 1px solid var(--ep-border) !important; }
        [dir="ltr"] .app-menu { border-right: 1px solid var(--ep-border) !important; }

        /* ─── RTL layout positioning ─────────────────────── */
        /* Velzon structural CSS uses LTR physical properties; flip them for RTL */
        @media (min-width: 768px) {
            [dir="rtl"] .navbar-menu {
                left: auto !important;
                right: 0 !important;
            }
            [dir="rtl"] #page-topbar {
                left: 0 !important;
                right: 250px !important;
            }
            [dir="rtl"] .main-content {
                margin-left: 0 !important;
                margin-right: 250px !important;
            }
            [dir="rtl"] .footer {
                left: 0 !important;
                right: 250px !important;
            }
        }

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
            color: #64748b !important;
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
            color: #94a3b8 !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.12em !important;
            text-transform: uppercase !important;
        }

        /* Sidebar background overlay */
        .sidebar-background { display: none !important; }

        #vertical-hover { color: #94a3b8 !important; }
        #vertical-hover:hover { color: var(--ep-accent) !important; }

        /* ─── Cards ─────────────────────────────────────── */
        .card {
            background: var(--ep-card) !important;
            border: 1px solid var(--ep-border) !important;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06) !important;
            border-radius: 14px !important;
        }
        .card:hover { border-color: rgba(0,212,170,0.25) !important; }
        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--ep-border) !important;
            padding: 1rem 1.25rem !important;
        }
        .card-title { color: var(--ep-text) !important; font-weight: 600 !important; }
        .card-text { color: var(--ep-muted) !important; }
        .card-body { color: var(--ep-text) !important; }
        .card-animate:hover { box-shadow: 0 8px 24px rgba(0,212,170,0.14) !important; transform: translateY(-2px); }
        .card.border-dashed {
            border-style: dashed !important;
            border-color: rgba(0,212,170,0.30) !important;
        }

        /* ─── Stat numbers ──────────────────────────────── */
        .fs-22.fw-semibold { color: var(--ep-accent) !important; }
        .ff-secondary { font-weight: 700 !important; }

        /* Stat icon avatars */
        .avatar-sm .avatar-title { border-radius: 10px !important; }
        .bg-primary-subtle { background: rgba(0,212,170,0.10) !important; }
        .bg-success-subtle { background: rgba(5,150,105,0.10) !important; }
        .bg-info-subtle    { background: rgba(3,169,244,0.10) !important; }
        .bg-warning-subtle { background: rgba(245,158,11,0.10) !important; }
        .text-primary { color: var(--ep-accent) !important; }
        .text-success { color: #059669 !important; }
        .text-info    { color: #0ea5e9 !important; }
        .text-warning { color: #d97706 !important; }

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
            background: #ffffff !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-text) !important;
            border-radius: 8px !important;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff !important;
            border-color: var(--ep-accent) !important;
            box-shadow: 0 0 0 3px rgba(0,212,170,0.12) !important;
            color: var(--ep-text) !important;
        }
        .form-control::placeholder { color: #94a3b8 !important; }
        .form-select option {
            background: #ffffff !important;
            color: var(--ep-text) !important;
        }
        .form-label { color: var(--ep-text) !important; font-weight: 500 !important; }
        .form-text  { color: var(--ep-muted) !important; }
        .input-group-text {
            background: #f8fafc !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-muted) !important;
        }

        /* ─── Badges ────────────────────────────────────── */
        .badge.bg-warning         { background: rgba(245,158,11,0.15) !important; color: #d97706 !important; }
        .badge.bg-primary-subtle  { background: rgba(0,212,170,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle  { background: rgba(5,150,105,0.10) !important; color: #059669 !important; }
        .badge.bg-primary         { background: var(--ep-accent) !important; color: #ffffff !important; }
        .badge.bg-success         { background: #059669 !important; }

        /* ─── Alerts ────────────────────────────────────── */
        .alert-info {
            background: rgba(3,169,244,0.07) !important;
            border-color: rgba(3,169,244,0.20) !important;
            color: #0284c7 !important;
        }
        .alert-link { color: var(--ep-accent) !important; }

        /* ─── Breadcrumb ────────────────────────────────── */
        .page-title-box h4 { color: var(--ep-text) !important; }
        .breadcrumb-item, .breadcrumb-item a { color: var(--ep-muted) !important; }
        .breadcrumb-item.active { color: var(--ep-text) !important; }
        .breadcrumb-item a:hover { color: var(--ep-accent) !important; }
        .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8 !important; }

        /* ─── Text utils ────────────────────────────────── */
        .text-muted { color: var(--ep-muted) !important; }
        h1, h2, h3, h4, h5, h6 { color: var(--ep-text) !important; }
        a { color: var(--ep-accent) !important; }
        a:hover { color: var(--ep-accent-2) !important; }
        .text-decoration-underline { color: var(--ep-accent) !important; }
        .fw-medium { font-weight: 500 !important; }

        /* ─── Footer ────────────────────────────────────── */
        .footer {
            background: #ffffff !important;
            border-top: 1px solid var(--ep-border) !important;
            color: var(--ep-muted) !important;
        }
        .footer a { color: var(--ep-muted) !important; }
        .footer a:hover { color: var(--ep-accent) !important; }

        /* ─── Modal ─────────────────────────────────────── */
        .modal-content {
            background: #ffffff !important;
            border: 1px solid var(--ep-border) !important;
        }
        .modal-header {
            border-bottom: 1px solid var(--ep-border) !important;
            color: var(--ep-text) !important;
        }
        .modal-footer { border-top: 1px solid var(--ep-border) !important; }
        .btn-close { filter: none !important; }

        /* ─── Pagination ────────────────────────────────── */
        .page-link {
            background: #ffffff !important;
            border-color: var(--ep-border) !important;
            color: var(--ep-text) !important;
        }
        .page-link:hover { background: var(--ep-accent-glow) !important; color: var(--ep-accent) !important; }
        .page-item.active .page-link { background: var(--ep-accent) !important; border-color: var(--ep-accent) !important; color: #ffffff !important; }
        .page-item.disabled .page-link { background: #f8fafc !important; color: #94a3b8 !important; }

        /* ─── Scrollbar ─────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: rgba(0,212,170,0.30); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--ep-accent); }

        /* ─── Simplebar ─────────────────────────────────── */
        .simplebar-scrollbar::before { background: rgba(0,212,170,0.35) !important; }

        /* ─── Vertical overlay (mobile) ─────────────────── */
        .vertical-overlay { background: rgba(0,0,0,0.40) !important; }

        /* ─── Role badge in sidebar ─────────────────────── */
        .badge.bg-primary-subtle.text-primary { background: rgba(0,212,170,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle.text-success { background: rgba(5,150,105,0.10) !important; color: #059669 !important; }

        /* ─── Switch/Toggle ─────────────────────────────── */
        .form-check-input:checked {
            background-color: var(--ep-accent) !important;
            border-color: var(--ep-accent) !important;
        }

        /* ═══ RESPONSIVE ═══════════════════════════════════════════════════
           Mobile  < 768px
        ═══════════════════════════════════════════════════════════════════ */
        @media (max-width: 767.98px) {
            .page-content {
                padding-top: 72px !important;
                padding-right: 0.625rem !important;
                padding-left:  0.625rem !important;
            }
            .card          { border-radius: 10px !important; }
            .card-body     { padding: 0.875rem !important; }
            .card-header   { padding: 0.75rem 0.875rem !important; }
            .card-animate:hover { transform: none !important; }
            .table { font-size: 0.82rem !important; }
            .table > :not(caption) > * > * { padding: 0.45rem 0.4rem !important; }
            .btn    { font-size: 0.82rem !important; }
            .btn-sm { padding: 0.25rem 0.6rem !important; font-size: 0.78rem !important; }
            .page-title-box h4 { font-size: 1rem !important; }
            .breadcrumb        { font-size: 0.78rem !important; }
            .footer .row        { text-align: center; }
            .footer .text-sm-end { text-align: center !important; display: block !important; }
            .ff-secondary.fs-25 { font-size: 1.5rem !important; }
            .ep-form-actions {
                flex-direction: column-reverse !important;
                align-items: stretch !important;
            }
            .ep-form-actions .btn { width: 100% !important; }
            .ep-welcome-body {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }
            .ep-welcome-body .d-flex.gap-2 { width: 100%; }
            .ep-welcome-body .d-flex.gap-2 .btn { flex: 1; }
        }

        @media (max-width: 575.98px) {
            .ep-topbar-icons { display: none !important; }
        }

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

    {{-- Customizer disabled: EngPis uses a fixed light theme --}}

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
