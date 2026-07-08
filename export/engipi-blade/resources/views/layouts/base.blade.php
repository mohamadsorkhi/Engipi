{{-- Engipi Blueprint — base layout (RTL). Self-hosted fonts + Remix Icon. --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Engipi')</title>
  <link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/vazirmatn.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/engipi/css/blueprint.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/engipi/css/app-shell.css') }}">
  @stack('head')
</head>
<body>
  @yield('content')
  @stack('scripts')
</body>
</html>
