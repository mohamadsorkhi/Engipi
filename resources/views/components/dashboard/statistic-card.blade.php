@props(['label', 'value', 'icon', 'description' => null, 'tone' => 'teal'])
<article {{ $attributes->class('sp-stat sp-stat--'.$tone) }}>
    <div class="sp-stat__head"><span>{{ $label }}</span><i class="{{ $icon }}" aria-hidden="true"></i></div>
    <strong>{{ $value }}</strong>
    @if($description)<small>{{ $description }}</small>@endif
</article>