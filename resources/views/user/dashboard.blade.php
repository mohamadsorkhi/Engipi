@extends('layouts.master')

@section('title', 'داشبورد')

@section('content')
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #0f2340 0%, #1a3a6a 60%, #0f4d3a 100%) !important; min-height: 110px;">
                <div class="card-body ep-welcome-body d-flex align-items-center justify-content-between gap-3 py-4">
                    <div>
                        <h4 class="mb-1" style="font-size:1.25rem; font-weight:700; color:white;">
                            سلام، {{ Auth::user()->name }}! <span style="color:#00d4aa;">👋</span>
                        </h4>
                        <p class="mb-0" style="color:rgba(220,232,245,0.65); font-size:0.9rem;">خلاصه وضعیت حساب کاربری شما</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($employerProfile)
                            <a href="{{ route('user.projects.create') }}" class="btn btn-primary btn-sm px-3">
                                <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه
                            </a>
                        @endif
                        @if($specialistProfile)
                            <a href="{{ route('user.skills.index') }}" class="btn btn-success btn-sm px-3">
                                <i class="ri-star-line align-bottom me-1"></i> مهارت‌ها
                            </a>
                        @endif
                    </div>
                </div>
                {{-- decorative circles --}}
                <div style="position:absolute;top:-30px;left:-30px;width:140px;height:140px;border-radius:50%;background:rgba(0,212,170,0.06);pointer-events:none;"></div>
                <div style="position:absolute;bottom:-40px;right:60px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>
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
    <div class="row g-3 mb-4">
        @if($employerProfile)
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #00d4aa !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">پروژه‌های من</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-briefcase-line" style="color:#00d4aa;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#00d4aa;">{{ $myProjectsCount }}</h3>
                    <a href="{{ route('user.projects.index') }}" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #60c8f5 !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">درخواست‌های دریافتی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(3,169,244,0.12);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-inbox-line" style="color:#60c8f5;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="mb-0" style="font-size:2rem;font-weight:800;color:#60c8f5;">{{ $receivedRequestsCount }}</h3>
                        @if($pendingRequestsCount > 0)
                            <span class="badge" style="background:rgba(255,190,0,0.18);color:#ffd43b;font-size:0.7rem;">{{ $pendingRequestsCount }} در انتظار</span>
                        @endif
                    </div>
                    <a href="{{ route('user.requests.received') }}" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>
        @endif

        @if($specialistProfile)
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #5ddfb0 !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">پروژه‌های پیشنهادی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(26,122,82,0.15);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-lightbulb-flash-line" style="color:#5ddfb0;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#5ddfb0;">{{ $matchedProjectsCount }}</h3>
                    <a href="{{ route('user.matched-projects.index') }}" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #ffd43b !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">درخواست‌های ارسالی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,190,0,0.1);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-send-plane-2-line" style="color:#ffd43b;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#ffd43b;">{{ $sentRequestsCount }}</h3>
                    <a href="{{ route('user.requests.sent') }}" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
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
