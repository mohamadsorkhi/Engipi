@props(['title', 'subtitle' => null, 'icon' => 'ri-layout-grid-line'])
<section {{ $attributes->class('engi-panel') }}>
    <header class="engi-panel__header">
        <div class="engi-panel__title">
            <i class="{{ $icon }}" aria-hidden="true"></i>
            <div><h2>{{ $title }}</h2>@if($subtitle)<p>{{ $subtitle }}</p>@endif</div>
        </div>
        @isset($action)<div>{{ $action }}</div>@endisset
    </header>
    <div class="engi-panel__body">{{ $slot }}</div>
</section>
