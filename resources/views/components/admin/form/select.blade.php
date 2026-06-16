@props([
    'name',
    'label',
    'options',
    'selected' => '',
    'required' => false,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    <select class="form-select"
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes }}
    >
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @if(old($name, $selected) == $value) selected @endif>{{ $text }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback"><span></span></div>
</div>
