@props([
    'name',
    'label',
    'checked' => false,
])

<div class="form-check form-switch form-switch-lg mb-3">
    <input class="form-check-input"
           type="checkbox"
           role="switch"
           id="{{ $name }}"
           name="{{ $name }}"
           value="1"
           @if(old($name, $checked)) checked @endif
            {{ $attributes }}
    >
    <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
    <div class="invalid-feedback"><span></span></div>
</div>
