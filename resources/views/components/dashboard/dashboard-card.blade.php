@props(['title' => null, 'subtitle' => null])
<section {{ $attributes->class('sp-card') }}>
    @if($title || isset($action))
        <header class="sp-card__header">
            <div>@if($title)<h2>{{ $title }}</h2>@endif @if($subtitle)<p>{{ $subtitle }}</p>@endif</div>
            @isset($action)<div class="sp-card__action">{{ $action }}</div>@endisset
        </header>
    @endif
    <div class="sp-card__body">{{ $slot }}</div>
</section>