@extends('layouts.master')

@section('title', 'پروژه‌های پیشنهادی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌های پیشنهادی</h5>
                    <a href="{{ route('user.skills.index') }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-settings-3-line me-1"></i> مدیریت مهارت‌ها
                    </a>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                            <br>
                            <a href="{{ route('user.skills.index') }}" class="alert-link">مهارت‌های خود را به‌روز کنید</a>
                        </div>
                    @else
                        @php
                            $workTypes = [
                                'remote' => ['name' => 'دورکاری', 'class' => 'bg-success'],
                                'onsite' => ['name' => 'حضوری', 'class' => 'bg-primary'],
                                'hybrid' => ['name' => 'ترکیبی', 'class' => 'bg-info'],
                            ];
                        @endphp
                        
                        <div class="row g-4">
                            @foreach($projects as $project)
                                <div class="col-lg-6">
                                    <div class="card border mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <a href="{{ route('user.matched-projects.show', $project) }}" class="fs-16 fw-medium text-primary">
                                                        {{ $project->title }}
                                                    </a>
                                                    <div class="text-muted small mt-1">
                                                        <i class="ri-user-line me-1"></i>{{ $project->employer->name ?? '-' }}
                                                    </div>
                                                </div>
                                                @php $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'class' => 'bg-secondary']; @endphp
                                                <span class="badge {{ $wt['class'] }}">{{ $wt['name'] }}</span>
                                            </div>
                                            
                                            <p class="text-muted mb-3">{{ Str::limit($project->description, 120) }}</p>
                                            
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                <span class="badge bg-primary-subtle text-primary">{{ $project->domain->name ?? '-' }}</span>
                                                @foreach($project->processes->take(3) as $process)
                                                    <span class="badge bg-info-subtle text-info">{{ $process->name }}</span>
                                                @endforeach
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>{{ $project->created_at }}
                                                </small>
                                                <a href="{{ route('user.matched-projects.show', $project) }}" class="btn btn-soft-primary btn-sm">
                                                    مشاهده و ارسال درخواست
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
