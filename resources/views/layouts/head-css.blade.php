<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>

@vite([
    'resources/scss/bootstrap.scss',
    'resources/scss/icons.scss',
    'resources/scss/app.scss',
])

<link rel="stylesheet" href="{{ URL::asset('build/css/mgh/mgh.css') }}">

<!-- Engipi Blueprint design package -->
<link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/remixicon.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/vazirmatn.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/engipi/css/blueprint.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/engipi/css/app-shell.css') }}">

@yield('css')
