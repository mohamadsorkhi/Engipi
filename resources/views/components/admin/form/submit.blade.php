@props([
    'text' => 'ذخیره',
])

<button type="submit" class="btn btn-primary ajax-submit">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    {{ $text }}
</button>
