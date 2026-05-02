@props(['status'])

@php
    $statuses = [
        'pending' => ['text' => 'در انتظار بررسی', 'class' => 'bg-warning-subtle text-warning'],
        'accepted' => ['text' => 'پذیرفته شده', 'class' => 'bg-success-subtle text-success'],
        'rejected' => ['text' => 'رد شده', 'class' => 'bg-danger-subtle text-danger'],
    ];
    $statusInfo = $statuses[$status] ?? ['text' => 'نامشخص', 'class' => 'bg-secondary-subtle text-secondary'];
@endphp

<span class="badge fs-13 {{ $statusInfo['class'] }}">
    <i class="ri-checkbox-blank-circle-fill align-middle me-1"></i> {{ $statusInfo['text'] }}
</span>
