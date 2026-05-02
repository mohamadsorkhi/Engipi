<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('velzon.direction', 'rtl') }}"
    data-layout="{{ config('velzon.data_layout', 'vertical') }}"
    data-topbar="{{ config('velzon.data_topbar', 'light') }}"
    data-sidebar="{{ config('velzon.data_sidebar', 'dark') }}"
    data-sidebar-size="{{ config('velzon.data_sidebar_size', 'lg') }}"
    data-sidebar-image="{{ config('velzon.data_sidebar_image', 'none') }}"
    data-preloader="{{ config('velzon.data_preloader', 'disable') }}"
    data-layout-style="{{ config('velzon.data_layout_style', 'default') }}"
    data-layout-width="{{ config('velzon.data_layout_width', 'fluid') }}"
    data-layout-position="{{ config('velzon.data_layout_position', 'fixed') }}"
    data-bs-theme="{{ config('velzon.data_bs_theme', 'light') }}"
>

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    @include('layouts.head-css')
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

    @if(config('velzon.customizer', true))
        @include('layouts.customizer')
    @endif

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
