@component('mail::message')
{{-- Header with Logo --}}
<div style="background-color: #001f3f; margin: -32px -32px 30px -32px; padding: 30px 0; text-align: center; border-radius: 3px 3px 0 0;">
<a href="{{ url('/') }}">
<img src="{{ URL::asset('build/images/logo-light.png') }}" alt="{{ config('app.name') }} Logo" style="width: 150px;">
</a>
</div>

{{-- Intro Content --}}
<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
<h1 style="text-align: right; font-size: 18px; font-weight: bold; margin-top: 0; color: #3d4852;">
@lang('سلام!')
</h1>
@foreach ($introLines as $line)
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096;">
{{ $line }}
</p>
@endforeach
</div>

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = $color ?? 'primary';
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Content --}}
<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
@foreach ($outroLines as $line)
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096;">
{{ $line }}
</p>
@endforeach
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096; margin-top: 20px;">
@lang('با احترام')،<br>
{{ config('app.name') }}
</p>
</div>

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
<p style="text-align: right; font-size: 12px; color: #718096;">
@lang(
    "اگر در کلیک روی دکمه «:actionText» مشکل دارید، URL زیر را کپی و در مرورگر وب خود جای‌گذاری کنید:",
    [
        'actionText' => $actionText,
    ]
)
<br>
<span class="break-all">
<a href="{{ $actionUrl }}" style="color: #3869d4;">{{ $actionUrl }}</a>
</span>
</p>
</div>
@endcomponent
@endisset
@endcomponent
