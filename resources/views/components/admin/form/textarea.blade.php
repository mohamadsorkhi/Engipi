@props([
    'name',
    'label',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'rows' => 3,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    <textarea class="form-control"
              id="{{ $name }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if($required) required @endif
              {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    <div class="invalid-feedback"><span></span></div>
</div>
