@props(['project', 'isOwner' => false])

@php
    $work_types = [
        'remote' => ['name' => 'دورکاری', 'icon' => 'ri-global-line'],
        'onsite' => ['name' => 'حضوری', 'icon' => 'ri-building-line'],
        'hybrid' => ['name' => 'ترکیبی', 'icon' => 'ri-git-merge-line'],
    ];
    $work_type = $work_types[$project->work_type] ?? ['name' => 'نامشخص', 'icon' => 'ri-question-mark'];
@endphp

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="flex-grow-1">
                <a href="{{ $isOwner ? route('employer.projects.show', $project) : route('specialist.matched-projects.show', $project) }}">
                    <h5 class="card-title text-primary mb-1" title="{{ $project->title }}">{{ $project->title }}</h5>
                </a>
            </div>
            <div class="flex-shrink-0 d-flex gap-3 text-muted ms-3">
                <div class="d-flex align-items-center" title="زمان انتشار">
                    <i class="ri-calendar-event-line me-1"></i>
                    <span class="d-inline-block">{{ \Morilog\Jalali\Jalalian::fromFormat('Y-m-d H:i:s',$project->created_at)->ago() }}</span>
                </div>
                <div class="d-flex align-items-center" title="نوع همکاری">
                    <i class="{{ $work_type['icon'] }} me-1"></i>
                    <span>{{ $work_type['name'] }}</span>
                </div>
            </div>
        </div>

        <p class="text-muted mb-4">
            {{ Str::limit($project->description, 250) }}
        </p>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center flex-wrap">
                <span class="fw-medium text-muted me-2">مهارت ها:</span>
                @foreach($project->skills as $skill)
                    <span class="badge bg-primary-subtle text-primary me-1 mb-1">{{ $skill->name }}</span>
                @endforeach
            </div>

            <div class="d-flex gap-2">
                <a href="{{ $isOwner ? route('employer.projects.show', $project) : route('specialist.matched-projects.show', $project) }}" class="btn btn-soft-primary btn-sm">
                    <i class="ri-eye-line align-bottom me-1"></i> مشاهده ی پروژه
                </a>
                @if($isOwner)
                    <a href="{{ route('employer.projects.edit', $project) }}" class="btn btn-soft-info btn-sm">
                        <i class="ri-pencil-line align-bottom me-1"></i> ویرایش
                    </a>
                    <form action="{{ route('employer.projects.destroy', $project) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> حذف
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
