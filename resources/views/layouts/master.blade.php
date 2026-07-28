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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layouts.social-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    @include('layouts.head-css')
    <style>
        /* ═══════════════════════════════════════════════════
           Engipi Blueprint Theme — Navy + Blue
        ═══════════════════════════════════════════════════ */
        * { font-family: var(--bp-font, 'Vazirmatn', sans-serif) !important; }

        :root {
            --ep-bg:          var(--bp-surface);
            --ep-bg-2:        var(--bp-hair);
            --ep-sidebar:     #ffffff;
            --ep-topbar:      #ffffff;
            --ep-card:        #ffffff;
            --ep-card-2:      var(--bp-surface);
            --ep-accent:      var(--bp-blue);
            --ep-accent-2:    var(--bp-blue-d);
            --ep-accent-glow: var(--bp-tint-blue);
            --ep-border:      var(--bp-hair);
            --ep-border-2:    var(--bp-border);
            --ep-text:        var(--bp-text);
            --ep-muted:       var(--bp-muted);
        }

        /* ─── Body & Layout ─────────────────────────────── */
        body { background: var(--ep-bg) !important; color: var(--ep-text) !important; }
        #layout-wrapper { background: var(--ep-bg) !important; }
        .main-content { background: var(--ep-bg) !important; }
        /* Engineering blueprint grid watermark, matching .bp-grid on the landing page */
        .page-content {
            background-color: var(--ep-bg) !important;
            background-image:
                linear-gradient(rgba(31,111,235,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(31,111,235,.05) 1px, transparent 1px) !important;
            background-size: 28px 28px !important;
            padding-top: 90px !important;
        }

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
            box-shadow: 0 6px 20px rgba(31,111,235,0.28) !important;
            color: #ffffff !important;
        }
        .btn-success {
            background: #2E9E5B !important;
            border-color: #2E9E5B !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .btn-success:hover {
            background: #247f49 !important;
            border-color: #247f49 !important;
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
        .btn-soft-primary:hover { background: rgba(31,111,235,0.20) !important; color: var(--ep-accent) !important; }
        .btn-soft-success {
            background: rgba(46,158,91,0.10) !important;
            color: #2E9E5B !important;
            border: none !important;
        }
        .btn-soft-success:hover { background: rgba(46,158,91,0.18) !important; color: #247f49 !important; }
        .btn-sm { font-size: 0.78rem !important; }

        /* ─── Sidebar ───────────────────────────────────── */
        .app-menu, .navbar-menu {
            background: var(--ep-sidebar) !important;
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

        /* ─── Cards (matching .bp-card on the landing page) ─────────────── */
        .card {
            background: var(--ep-card) !important;
            border: 1px solid var(--bp-border) !important;
            box-shadow: var(--bp-sh-sm) !important;
            border-radius: var(--bp-r-lg) !important;
            transition: transform .25s var(--bp-ease), box-shadow .25s var(--bp-ease), border-color .25s !important;
        }
        .card:hover { border-color: var(--bp-blue) !important; }
        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--ep-border) !important;
            padding: 1rem 1.25rem !important;
        }
        .card-title { color: var(--ep-text) !important; font-weight: 600 !important; }
        .card-text { color: var(--ep-muted) !important; }
        .card-body { color: var(--ep-text) !important; }
        .card-animate:hover { box-shadow: var(--bp-sh-md) !important; transform: translateY(-4px); }
        .card.border-dashed {
            border-style: dashed !important;
            border-color: rgba(31,111,235,0.30) !important;
        }

        /* ─── Stat numbers ──────────────────────────────── */
        .fs-22.fw-semibold { color: var(--ep-accent) !important; }
        .ff-secondary { font-weight: 700 !important; }

        /* Stat icon avatars */
        .avatar-sm .avatar-title { border-radius: 10px !important; }
        .bg-primary-subtle { background: rgba(31,111,235,0.10) !important; }
        .bg-success-subtle { background: rgba(46,158,91,0.10) !important; }
        .bg-info-subtle    { background: rgba(15,169,219,0.10) !important; }
        .bg-warning-subtle { background: rgba(224,147,11,0.10) !important; }
        .text-primary { color: var(--ep-accent) !important; }
        .text-success { color: #2E9E5B !important; }
        .text-info    { color: #0FA9DB !important; }
        .text-warning { color: #E0930B !important; }

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

        /* ─── Forms (matching .inp/.sel/.ta on the landing page) ─────────── */
        .form-control, .form-select {
            background: #ffffff !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-text) !important;
            border-radius: var(--bp-r) !important;
            padding: 0.6rem 0.85rem !important;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff !important;
            border-color: var(--ep-accent) !important;
            box-shadow: 0 0 0 3px var(--bp-tint-blue) !important;
            color: var(--ep-text) !important;
        }
        .form-control::placeholder { color: #94a3b8 !important; }
        .form-select option {
            background: #ffffff !important;
            color: var(--ep-text) !important;
        }
        .form-label { color: var(--bp-ink) !important; font-weight: 700 !important; font-size: 0.88rem !important; }
        .form-text  { color: var(--ep-muted) !important; font-size: 0.76rem !important; }
        .input-group-text {
            background: #f8fafc !important;
            border-color: var(--ep-border-2) !important;
            color: var(--ep-muted) !important;
        }
        .border-primary { border-color: var(--bp-blue) !important; }

        /* ─── Badges ────────────────────────────────────── */
        .badge.bg-warning         { background: rgba(224,147,11,0.15) !important; color: #E0930B !important; }
        .badge.bg-primary-subtle  { background: rgba(31,111,235,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle  { background: rgba(46,158,91,0.10) !important; color: #2E9E5B !important; }
        .badge.bg-primary         { background: var(--ep-accent) !important; color: #ffffff !important; }
        .badge.bg-success         { background: #2E9E5B !important; }

        /* ─── Alerts ────────────────────────────────────── */
        .alert-info {
            background: rgba(15,169,219,0.07) !important;
            border-color: rgba(15,169,219,0.20) !important;
            color: #0c87b0 !important;
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
        ::-webkit-scrollbar-thumb { background: rgba(31,111,235,0.30); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--ep-accent); }

        /* ─── Simplebar ─────────────────────────────────── */
        .simplebar-scrollbar::before { background: rgba(31,111,235,0.35) !important; }

        /* ─── Vertical overlay (mobile) ─────────────────── */
        .vertical-overlay { background: rgba(0,0,0,0.40) !important; }

        /* ─── RTL Layout Fix ────────────────────────────── */
        [dir="rtl"] .navbar-menu {
            right: 0 !important;
            left: auto !important;
        }
        @media (min-width: 768px) {
            [dir="rtl"] .main-content {
                margin-left: 0 !important;
                margin-right: var(--vz-vertical-menu-width) !important;
            }
            [dir="rtl"] #page-topbar {
                left: 0 !important;
                right: var(--vz-vertical-menu-width) !important;
            }
        }

        /* ─── RTL Mobile Sidebar ─────────────────────────── */
        /* The LTR hide trick (margin-left:-100%) does nothing on a
           position:fixed; right:0 element. Use translateX instead. */
        @media (max-width: 767.98px) {
            [dir="rtl"] .app-menu {
                margin-left: 0 !important;
                transform: translateX(100%);
            }
            [dir="rtl"] .vertical-sidebar-enable .app-menu {
                transform: translateX(0) !important;
            }
        }

        /* ─── Role badge in sidebar ─────────────────────── */
        .badge.bg-primary-subtle.text-primary { background: rgba(31,111,235,0.12) !important; color: var(--ep-accent) !important; }
        .badge.bg-success-subtle.text-success { background: rgba(46,158,91,0.10) !important; color: #2E9E5B !important; }

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
            .card          { border-radius: var(--bp-r-lg) !important; }
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

    {{-- Customizer disabled: Engipi uses a fixed light theme --}}

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
