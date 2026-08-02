@props(['label', 'value', 'icon' => 'ri-bar-chart-line', 'tone' => '', 'hint' => null])
<article {{ $attributes->class(['engi-stat', 'engi-stat--'.$tone => $tone]) }}>
    <div class="engi-stat__top">
        <span class="engi-stat__label">{{ $label }}</span>
        <span class="engi-stat__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
    </div>
    <strong class="engi-stat__value">{{ $value }}</strong>
    @if($hint)<span class="engi-stat__hint">{{ $hint }}</span>@endif
</article>
