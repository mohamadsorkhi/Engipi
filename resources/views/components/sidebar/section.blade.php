@props(['label'])

<section class="engipi-nav-section" aria-label="{{ $label }}">
    <h2>{{ $label }}</h2>
    <ul>{{ $slot }}</ul>
</section>
