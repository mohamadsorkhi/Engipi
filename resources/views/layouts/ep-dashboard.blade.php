<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'داشبورد | EngPis')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ep-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ep-dashboard.css') }}">
    @yield('styles')
</head>
<body>
<div class="app">
    @include('partials.ep-sidebar')
    <div class="main">
        @include('partials.ep-topbar')
        @yield('content')
    </div>
</div>
@yield('scripts')
</body>
</html>
