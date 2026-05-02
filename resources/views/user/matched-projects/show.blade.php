@extends('layouts.master')

@section('title', $project->title)

@section('content')
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
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $project->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">توضیحات پروژه</h6>
                        <p class="mb-0">{!! nl2br(e($project->description)) !!}</p>
                    </div>

                    @if($project->processes->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">پردازش‌های مورد نیاز</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->processes as $process)
                                    <span class="badge bg-primary-subtle text-primary">{{ $process->name }}</span>
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

            <!-- Send Request Section -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">ارسال درخواست همکاری</h6>
                </div>
                <div class="card-body">
                    @if($sentRequest)
                        <div class="alert alert-{{ $sentRequest->status === 'accepted' ? 'success' : ($sentRequest->status === 'rejected' ? 'danger' : 'warning') }} mb-0">
                            <div class="d-flex align-items-center">
                                <i class="ri-{{ $sentRequest->status === 'accepted' ? 'check-double' : ($sentRequest->status === 'rejected' ? 'close-circle' : 'time') }}-line fs-4 me-2"></i>
                                <div>
                                    @if($sentRequest->status === 'pending')
                                        <strong>درخواست شما در انتظار بررسی است.</strong>
                                        <p class="mb-0 small">کارفرما به زودی درخواست شما را بررسی خواهد کرد.</p>
                                    @elseif($sentRequest->status === 'accepted')
                                        <strong>درخواست شما پذیرفته شد!</strong>
                                        <p class="mb-0 small">می‌توانید با کارفرما تماس بگیرید.</p>
                                    @else
                                        <strong>متاسفانه درخواست شما رد شد.</strong>
                                        <p class="mb-0 small">می‌توانید پروژه‌های دیگر را بررسی کنید.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <form id="requestForm" action="{{ route('user.requests.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">پیام برای کارفرما (اختیاری)</label>
                                <textarea class="form-control" id="message" name="message" rows="4" 
                                    placeholder="توضیح دهید چرا برای این پروژه مناسب هستید..." maxlength="1000" minlength="10"></textarea>
                                <div class="form-text">حداکثر ۱۰۰۰ کاراکتر (اگر پیام وارد می‌کنید، حداقل ۱۰ کاراکتر باشد)</div>
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            
                            <button type="submit" class="btn btn-success ajax-submit">
                                <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                <i class="ri-send-plane-line me-1"></i> ارسال درخواست همکاری
                            </button>
                        </form>
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
                            <span class="fw-medium">{{ $project->employer->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">حوزه تخصصی</span>
                            <span class="fw-medium">{{ $project->domain->name ?? '-' }}</span>
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
                            <span class="text-muted">تاریخ ثبت</span>
                            <span class="fw-medium">{{ $project->created_at }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('user.matched-projects.index') }}" class="btn btn-light w-100">
                <i class="ri-arrow-right-line me-1"></i> بازگشت به لیست پروژه‌ها
            </a>
        </div>
    </div>
@endsection
