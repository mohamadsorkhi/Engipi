@extends('layouts.master')

@section('title', 'داشبورد')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">سلام، {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-0">خلاصه وضعیت حساب کاربری شما</p>
                </div>
                <div class="mt-3 mt-lg-0 d-flex gap-2">
                    @if($employerProfile)
                        <a href="{{ route('user.projects.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه جدید
                        </a>
                    @endif
                    @if($specialistProfile)
                        <a href="{{ route('user.skills.index') }}" class="btn btn-success">
                            <i class="ri-star-line align-bottom me-1"></i> مدیریت مهارت‌ها
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
        $hasEmployer = !is_null($employerProfile);
        $hasSpecialist = !is_null($specialistProfile);
    @endphp

    @if(!$hasEmployer || !$hasSpecialist)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">تکمیل حساب کاربری</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            برای دسترسی به امکانات، ابتدا پروفایل‌های مورد نیاز را ایجاد کنید.
                        </p>

                        <div class="row g-3">
                            @if(!$hasEmployer)
                                <div class="col-lg-6">
                                    <form action="{{ route('profiles.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="profile_type" value="employer">
                                        <div class="card border border-dashed mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                            <i class="ri-briefcase-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">پروفایل کارفرما</h6>
                                                        <p class="text-muted small mb-0">برای ثبت پروژه و مدیریت درخواست‌ها</p>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">نام شرکت (اختیاری)</label>
                                                    <input type="text" name="company_name" class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-primary ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل کارفرما
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if(!$hasSpecialist)
                                <div class="col-lg-6">
                                    <form action="{{ route('profiles.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="profile_type" value="specialist">
                                        <div class="card border border-dashed mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                                            <i class="ri-user-star-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">پروفایل متخصص</h6>
                                                        <p class="text-muted small mb-0">برای ثبت مهارت‌ها و ارسال درخواست</p>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">عنوان تخصصی</label>
                                                    <input type="text" name="headline" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">بیوگرافی (اختیاری)</label>
                                                    <textarea name="bio" class="form-control" rows="3"></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-success ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل متخصص
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('profile.select') }}" class="btn btn-outline-secondary">
                                مدیریت پروفایل‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row">
        <!-- Employer Stats -->
        @if($employerProfile)
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">پروژه‌های من</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $myProjectsCount }}</h4>
                            <a href="{{ route('user.projects.index') }}" class="text-decoration-underline">مشاهده همه</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="ri-briefcase-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($employerProfile)
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">درخواست‌های دریافتی</p>
                        </div>
                        @if($pendingRequestsCount > 0)
                            <span class="badge bg-warning">{{ $pendingRequestsCount }} در انتظار</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $receivedRequestsCount }}</h4>
                            <a href="{{ route('user.requests.received') }}" class="text-decoration-underline">مشاهده همه</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="ri-inbox-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Specialist Stats -->
        @if($specialistProfile)
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">پروژه‌های پیشنهادی</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $matchedProjectsCount }}</h4>
                            <a href="{{ route('user.matched-projects.index') }}" class="text-decoration-underline">مشاهده همه</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-lightbulb-flash-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">درخواست‌های ارسالی</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $sentRequestsCount }}</h4>
                            <a href="{{ route('user.requests.sent') }}" class="text-decoration-underline">مشاهده همه</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="ri-send-plane-2-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row">
        <!-- My Projects -->
        @if($employerProfile)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">آخرین پروژه‌های من</h5>
                    <a href="{{ route('user.projects.index') }}" class="btn btn-soft-primary btn-sm">مشاهده همه</a>
                </div>
                <div class="card-body">
                    @if($myProjects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز پروژه‌ای ثبت نکرده‌اید.
                            <a href="{{ route('user.projects.create') }}" class="alert-link">ثبت پروژه جدید</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <tbody>
                                    @foreach($myProjects as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('user.projects.show', $project) }}" class="fw-medium text-primary">
                                                    {{ Str::limit($project->title, 40) }}
                                                </a>
                                            </td>
                                            <td class="text-muted">{{ $project->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Matched Projects -->
        @if($specialistProfile)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌های پیشنهادی</h5>
                    <a href="{{ route('user.matched-projects.index') }}" class="btn btn-soft-success btn-sm">مشاهده همه</a>
                </div>
                <div class="card-body">
                    @if($recentMatchedProjects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <tbody>
                                    @foreach($recentMatchedProjects as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('user.matched-projects.show', $project) }}" class="fw-medium text-success">
                                                    {{ Str::limit($project->title, 40) }}
                                                </a>
                                            </td>
                                            <td><span class="badge bg-primary-subtle text-primary">{{ $project->domain->name ?? '-' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
