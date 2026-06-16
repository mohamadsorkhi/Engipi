@props(['request'])

@php
    $statusClasses = [
        'pending' => 'warning',
        'accepted' => 'success',
        'rejected' => 'danger',
    ];
    $statusTexts = [
        'pending' => 'در انتظار بررسی',
        'accepted' => 'پذیرفته شده',
        'rejected' => 'رد شده',
    ];
@endphp

<tr>
    <td>{{ $request->project->title }}</td>
    <td>{{ $request->project->employer->name }}</td>
    <td>{{ Str::limit($request->message, 50) }}</td>
    <td>
        <span class="badge bg-{{ $statusClasses[$request->status] ?? 'secondary' }}-subtle text-{{ $statusClasses[$request->status] ?? 'secondary' }}">
            {{ $statusTexts[$request->status] ?? $request->status }}
        </span>
    </td>
    <td>{{ $request->created_at->format('Y/m/d') }}</td>
</tr>
