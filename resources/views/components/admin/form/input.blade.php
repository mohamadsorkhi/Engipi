@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'help' => '',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    <input type="{{ $type }}"
           class="form-control"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           {{ $attributes }}
    >
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    <div class="invalid-feedback"><span></span></div>
</div>
