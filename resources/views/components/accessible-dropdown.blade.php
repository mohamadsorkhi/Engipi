@props([
    'id',
    'placeholder',
    'disabled' => false,
])

<div
    id="{{ $id }}-dropdown"
    class="eng-dropdown bp-wizard-control{{ $disabled ? ' is-disabled' : '' }}"
    data-eng-dropdown
    dir="rtl"
>
    <button
        type="button"
        id="{{ $id }}-trigger"
        class="eng-dropdown__trigger"
        role="combobox"
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="{{ $id }}-menu"
        aria-disabled="{{ $disabled ? 'true' : 'false' }}"
        {{ $disabled ? 'disabled' : '' }}
    >
        <span class="eng-dropdown__label" data-dropdown-label>{{ $placeholder }}</span>
        <i class="ri-arrow-down-s-line eng-dropdown__chevron" aria-hidden="true"></i>
    </button>
    <div class="eng-dropdown__panel" data-dropdown-panel>
        <ul id="{{ $id }}-menu" class="eng-dropdown__menu" role="listbox" tabindex="-1"></ul>
    </div>
</div>