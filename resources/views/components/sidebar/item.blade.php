@props(['route', 'label', 'icon', 'active' => false, 'badge' => null])

<li>
    <a href="{{ route($route) }}"
       class="engipi-nav-item {{ $active ? 'is-active' : '' }}"
       data-tooltip="{{ $label }}"
       @if($active) aria-current="page" @endif>
        <span class="engipi-nav-icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
        <span class="engipi-nav-label">{{ $label }}</span>
        @if(!is_null($badge))
            <span class="engipi-nav-badge">{{ $badge }}</span>
        @endif
    </a>
</li>
