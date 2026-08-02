@props(['title', 'description', 'icon', 'tone' => 'teal', 'href' => null])
<article class="sp-activity sp-activity--{{ $tone }}">
    <span class="sp-activity__icon"><i class="{{ $icon }}" aria-hidden="true"></i></span>
    <div><strong>{{ $title }}</strong><p>{{ $description }}</p></div>
    @if($href)<a href="{{ $href }}" aria-label="{{ $title }}"><i class="ri-arrow-left-s-line"></i></a>@endif
</article>