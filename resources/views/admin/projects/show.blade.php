@extends('layouts.master')

@section('title', $project->title)

@section('content')
    <x-admin.breadcrumb :title="$project->title" parent="مدیریت پروژه‌ها" parentUrl="{{ route('admin.projects.index') }}"/>

    @php
        $workTypes = [
            'remote' => ['name' => 'دورکاری', 'icon' => 'ri-global-line', 'class' => 'success'],
            'onsite' => ['name' => 'حضوری', 'icon' => 'ri-building-line', 'class' => 'primary'],
            'hybrid' => ['name' => 'ترکیبی', 'icon' => 'ri-git-merge-line', 'class' => 'info'],
        ];
        $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'icon' => 'ri-question-line', 'class' => 'secondary'];
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $project->title }}</h5>
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از حذف این پروژه اطمینان دارید؟">
                            <i class="ri-delete-bin-line me-1"></i> حذف
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">توضیحات پروژه</h6>
                        <p class="mb-0">{!! nl2br(e($project->description)) !!}</p>
                    </div>

                    @if($project->processes->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">پردازش‌های مورد نیاز</h6>
                            @php
                                $levelLabels = [
                                    'practical' => 'عملی',
                                    'proficient' => 'مسلط',
                                    'advanced' => 'پیشرفته',
                                ];
                            @endphp
                            <div class="row g-2">
                                @foreach($project->processes as $process)
                                    @php
                                        $levelsRaw = $process->pivot?->desired_levels;
                                        $levels = [];
                                        if (is_string($levelsRaw)) {
                                            $decoded = json_decode($levelsRaw, true);
                                            $levels = is_array($decoded) ? $decoded : [];
                                        } elseif (is_array($levelsRaw)) {
                                            $levels = $levelsRaw;
                                        }
                                    @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <div class="border rounded p-2">
                                            <div class="fw-medium text-primary">{{ $process->name }}</div>
                                            <div class="small text-muted mt-1">
                                                سطح(ها):
                                                @if(!empty($levels))
                                                    {{ collect($levels)->map(fn($l) => $levelLabels[$l] ?? $l)->join('، ') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($project->skills->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">مهارت‌های مورد نیاز</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->skills as $skill)
                                    <span class="badge bg-info-subtle text-info">{{ $skill->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($project->files->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">فایل‌های پیوست</h6>
                            <div class="list-group">
                                @foreach($project->files as $file)
                                    <a href="{{ Storage::url($file->path) }}" class="list-group-item list-group-item-action d-flex align-items-center" target="_blank">
                                        <i class="ri-file-line me-2"></i>
                                        {{ $file->original_name }}
                                        <span class="badge bg-secondary ms-auto">{{ number_format($file->size / 1024, 1) }} KB</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">درخواست‌های همکاری</h5>
                </div>
                <div class="card-body">
                    @if($project->requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">هنوز درخواستی برای این پروژه ثبت نشده است.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>متخصص</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($project->requests as $req)
                                    <tr>
                                        <td class="fw-medium">{{ $req->user?->full_name ?? $req->user?->name ?? '-' }}</td>
                                        <td><x-request-status-badge :status="$req->status" /></td>
                                        <td class="text-muted">{{ $req->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">اطلاعات پروژه</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">کارفرما</span>
                            <span class="fw-medium">
                                <a class="text-primary" href="{{ route('admin.users.show', $project->employer) }}">{{ $project->employer?->full_name ?? '-' }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">پروفایل کارفرما</span>
                            <span class="fw-medium">{{ $project->employerProfile?->company_name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">حوزه‌های تخصصی</span>
                            <span class="fw-medium">
                                @if($project->domains->isNotEmpty())
                                    {{ $project->domains->pluck('name')->join('، ') }}
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">نوع اجرا</span>
                            <span class="badge bg-{{ $wt['class'] }}">
                                <i class="{{ $wt['icon'] }} me-1"></i>{{ $wt['name'] }}
                            </span>
                        </li>
                        @if($project->duration_days)
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">مدت زمان</span>
                                <span class="fw-medium">{{ $project->duration_days }} روز</span>
                            </li>
                        @endif
                        @if($project->budget_min || $project->budget_max)
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">بودجه</span>
                                <span class="fw-medium">
                                    @if($project->budget_min && $project->budget_max)
                                        {{ number_format($project->budget_min) }} - {{ number_format($project->budget_max) }} تومان
                                    @elseif($project->budget_min)
                                        از {{ number_format($project->budget_min) }} تومان
                                    @else
                                        تا {{ number_format($project->budget_max) }} تومان
                                    @endif
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">بازدید</span>
                            <span class="fw-medium">{{ $project->view_count }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">تاریخ ثبت</span>
                            <span class="fw-medium">{{ $project->created_at }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
