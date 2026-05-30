<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>

@vite([
    'resources/scss/bootstrap.scss',
    'resources/scss/icons.scss',
    'resources/scss/app.scss',
])

<link rel="stylesheet" href="{{ URL::asset('build/css/mgh/mgh.css') }}">

@yield('css')
