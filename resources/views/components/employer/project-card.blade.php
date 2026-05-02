@props(['project'])

@php
    $workTypes = [
        'remote' => ['name' => 'دورکاری', 'class' => 'info'],
        'onsite' => ['name' => 'حضوری', 'class' => 'warning'],
        'hybrid' => ['name' => 'ترکیبی', 'class' => 'primary'],
    ];
    $workType = $workTypes[$project->work_type] ?? ['name' => $project->work_type, 'class' => 'secondary'];
@endphp

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <h5 class="card-title mb-1">{{ $project->title }}</h5>
            <span class="badge bg-{{ $workType['class'] }}-subtle text-{{ $workType['class'] }}">{{ $workType['name'] }}</span>
        </div>
        <p class="text-muted mb-3">ایجاد شده در: {{ $project->created_at->format('Y/m/d') }}</p>

        <p class="card-text text-muted" style="min-height: 60px;">
            {{ Str::limit($project->description, 120) }}
        </p>

        <div class="mb-3">
            <h6 class="fs-13">مهارت‌های مورد نیاز:</h6>
            <div class="d-flex flex-wrap gap-2">
                @forelse($project->skills as $skill)
                    <span class="badge bg-secondary-subtle text-secondary">{{ $skill->name }}</span>
                @empty
                    <span class="text-muted fs-12">هیچ مهارتی مشخص نشده است.</span>
                @endforelse
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('employer.projects.edit', $project) }}" class="btn btn-info btn-sm">ویرایش</a>
            <form action="{{ route('employer.projects.destroy', $project) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm ajax-submit" data-confirm="آیا از حذف این پروژه مطمئن هستید؟">حذف</button>
            </form>
        </div>
    </div>
</div>
