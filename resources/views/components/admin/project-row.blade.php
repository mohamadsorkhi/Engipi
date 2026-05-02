@props(['project'])

@php
    $workTypes = [
        'remote' => 'دورکاری',
        'onsite' => 'حضوری',
        'hybrid' => 'ترکیبی',
    ];
@endphp

<tr>
    <td>{{ $project->id }}</td>
    <td>{{ $project->title }}</td>
    <td>{{ $project->employer->name }}</td>
    <td>{{ $workTypes[$project->work_type] ?? $project->work_type }}</td>
    <td>{{ $project->skills->count() }}</td>
    <td>{{ $project->created_at->format('Y/m/d') }}</td>
</tr>
