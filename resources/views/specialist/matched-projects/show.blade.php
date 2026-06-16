@extends('layouts.master')

@section('title', $project->title)

@section('content')
    <x-admin.breadcrumb title="{{ Str::limit($project->title, 30) }}" parent="پروژه‌های منطبق" parentUrl="{{ route('specialist.matched-projects.index') }}" />

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

                    @if($project->domain)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">حوزه تخصصی</h6>
                            <span class="badge bg-primary-subtle text-primary fs-6">{{ $project->domain->name }}</span>
                        </div>
                    @endif

                    @if($project->processes->isNotEmpty())
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">پردازش‌های مورد نیاز</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->processes as $process)
                                    <span class="badge bg-info-subtle text-info">{{ $process->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($project->skills->isNotEmpty())
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">مهارت‌های مورد نیاز</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($project->skills as $skill)
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $skill->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($project->files->isNotEmpty())
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">فایل‌های پیوست</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($project->files as $file)
                                    <li class="mb-1">
                                        <a href="{{ Storage::url($file->path) }}" target="_blank" class="link-primary">
                                            <i class="ri-attachment-line me-1"></i>
                                            {{ $file->original_name }}
                                        </a>
                                        <small class="text-muted">({{ number_format($file->size / 1024, 1) }} KB)</small>
                                    </li>
                                @endforeach
                            </ul>
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
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-user-line text-muted fs-5"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0">کارفرما</p>
                                    <h6 class="mb-0">{{ $project->employer->name ?? '-' }}</h6>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-building-line text-muted fs-5"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0">نوع همکاری</p>
                                    <h6 class="mb-0">
                                        @switch($project->work_type)
                                            @case('remote') دورکاری @break
                                            @case('onsite') حضوری @break
                                            @case('hybrid') ترکیبی @break
                                        @endswitch
                                    </h6>
                                </div>
                            </div>
                        </li>
                        @if($project->duration_days)
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-time-line text-muted fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="text-muted mb-0">مدت زمان</p>
                                        <h6 class="mb-0">{{ $project->duration_days }} روز</h6>
                                    </div>
                                </div>
                            </li>
                        @endif
                        @if($project->budget_min || $project->budget_max)
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-money-dollar-circle-line text-muted fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="text-muted mb-0">بودجه</p>
                                        <h6 class="mb-0">
                                            @if($project->budget_min && $project->budget_max)
                                                {{ number_format($project->budget_min) }} - {{ number_format($project->budget_max) }} تومان
                                            @elseif($project->budget_min)
                                                از {{ number_format($project->budget_min) }} تومان
                                            @else
                                                تا {{ number_format($project->budget_max) }} تومان
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-calendar-line text-muted fs-5"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0">تاریخ ثبت</p>
                                    <h6 class="mb-0">{{ $project->jalali_created_at }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @php
                        $existingRequest = Auth::user()->requests()->where('project_id', $project->id)->first();
                    @endphp

                    @if($existingRequest)
                        <div class="alert alert-{{ $existingRequest->status === 'accepted' ? 'success' : ($existingRequest->status === 'rejected' ? 'danger' : 'info') }} mb-0">
                            <i class="ri-information-line me-1"></i>
                            @switch($existingRequest->status)
                                @case('pending')
                                    درخواست شما در انتظار بررسی است.
                                    @break
                                @case('accepted')
                                    درخواست شما پذیرفته شده است.
                                    @break
                                @case('rejected')
                                    درخواست شما رد شده است.
                                    @break
                            @endswitch
                        </div>
                    @else
                        <form action="{{ route('specialist.requests.store') }}" method="POST" id="request-form">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="mb-3">
                                <label for="message" class="form-label">پیام (اختیاری)</label>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="توضیحات یا پیام خود را بنویسید..." minlength="10"></textarea>
                                <div class="form-text">اگر پیام وارد می‌کنید، حداقل ۱۰ کاراکتر باشد</div>
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 ajax-submit">
                                <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="flex-grow-1">ارسال درخواست همکاری</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
