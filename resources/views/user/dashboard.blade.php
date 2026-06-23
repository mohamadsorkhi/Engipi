@extends('layouts.master')

@section('title', 'داشبورد')

@section('content')
    {{-- Welcome Banner --}}
    <div class="bp-welcome mb-4">
        <div class="grid-bg bp-grid"></div>
        <div style="position:absolute;top:-30px;left:-30px;width:140px;height:140px;border-radius:50%;background:rgba(31,111,235,.18);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-40px;right:60px;width:180px;height:180px;border-radius:50%;background:rgba(0,184,169,.14);pointer-events:none;"></div>
        <div class="bp-welcome-body ep-welcome-body">
            <div>
                <h4 class="mb-1">سلام، {{ Auth::user()->name }}! <span style="color:var(--bp-blue-l);">👋</span></h4>
                <p class="mb-0">خلاصه وضعیت حساب کاربری شما</p>
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
    <div class="bp-sgrid mb-4">
        @if($employerProfile)
        <div class="bp-statcard" style="--ac: var(--bp-blue);">
            <div class="bp-srow">
                <span class="bp-sl">پروژه‌های من</span>
                <div class="bp-stile" style="background:var(--bp-tint-blue);color:var(--bp-blue);"><i class="ri-briefcase-line"></i></div>
            </div>
            <div class="bp-sn">{{ $myProjectsCount }}</div>
            <a href="{{ route('user.projects.index') }}" class="bp-slink">مشاهده همه ←</a>
        </div>

        <div class="bp-statcard" style="--ac: var(--bp-c-sky);">
            <div class="bp-srow">
                <span class="bp-sl">درخواست‌های دریافتی</span>
                <div class="bp-stile" style="background:var(--bp-tint-sky);color:var(--bp-c-sky);"><i class="ri-inbox-line"></i></div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bp-sn">{{ $receivedRequestsCount }}</div>
                @if($pendingRequestsCount > 0)
                    <span class="badge" style="background:var(--bp-tint-amber);color:var(--bp-c-amber);font-size:0.7rem;">{{ $pendingRequestsCount }} در انتظار</span>
                @endif
            </div>
            <a href="{{ route('user.requests.received') }}" class="bp-slink">مشاهده همه ←</a>
        </div>
        @endif

        @if($specialistProfile)
        <div class="bp-statcard" style="--ac: var(--bp-teal);">
            <div class="bp-srow">
                <span class="bp-sl">پروژه‌های پیشنهادی</span>
                <div class="bp-stile" style="background:var(--bp-tint-teal);color:var(--bp-teal);"><i class="ri-lightbulb-flash-line"></i></div>
            </div>
            <div class="bp-sn">{{ $matchedProjectsCount }}</div>
            <a href="{{ route('user.matched-projects.index') }}" class="bp-slink">مشاهده همه ←</a>
        </div>

        <div class="bp-statcard" style="--ac: var(--bp-c-amber);">
            <div class="bp-srow">
                <span class="bp-sl">درخواست‌های ارسالی</span>
                <div class="bp-stile" style="background:var(--bp-tint-amber);color:var(--bp-c-amber);"><i class="ri-send-plane-2-line"></i></div>
            </div>
            <div class="bp-sn">{{ $sentRequestsCount }}</div>
            <a href="{{ route('user.requests.sent') }}" class="bp-slink">مشاهده همه ←</a>
        </div>
        @endif
    </div>

    <div class="bp-twocol">
        <!-- My Projects -->
        @if($employerProfile)
        <div class="bp-panel2">
            <div class="bp-ph">
                <h5 class="mb-0">آخرین پروژه‌های من</h5>
                <a href="{{ route('user.projects.index') }}" class="btn btn-soft-primary btn-sm">مشاهده همه</a>
            </div>
            <div class="bp-pb">
                @if($myProjects->isEmpty())
                    <div class="alert alert-info text-center mb-0">
                        <i class="ri-information-line me-2"></i>
                        هنوز پروژه‌ای ثبت نکرده‌اید.
                        <a href="{{ route('user.projects.create') }}" class="alert-link">ثبت پروژه جدید</a>
                    </div>
                @else
                    @foreach($myProjects as $project)
                        <div class="bp-lrow">
                            <a href="{{ route('user.projects.show', $project) }}" class="bp-lt">
                                {{ Str::limit($project->title, 40) }}
                            </a>
                            <span class="bp-lm">{{ $project->created_at->format('Y/m/d') }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif

        <!-- Matched Projects -->
        @if($specialistProfile)
        <div class="bp-panel2">
            <div class="bp-ph">
                <h5 class="mb-0">پروژه‌های پیشنهادی</h5>
                <a href="{{ route('user.matched-projects.index') }}" class="btn btn-soft-success btn-sm">مشاهده همه</a>
            </div>
            <div class="bp-pb">
                @if($recentMatchedProjects->isEmpty())
                    <div class="alert alert-info text-center mb-0">
                        <i class="ri-information-line me-2"></i>
                        در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                    </div>
                @else
                    @foreach($recentMatchedProjects as $project)
                        <div class="bp-lrow">
                            <a href="{{ route('user.matched-projects.show', $project) }}" class="bp-lt">
                                {{ Str::limit($project->title, 40) }}
                            </a>
                            <span class="badge bg-primary-subtle text-primary">{{ $project->domain->name ?? '-' }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
    </div>

    <style>
    /* ── Welcome banner, matching .welcome on the blueprint reference ──────── */
    .bp-welcome {
        position: relative;
        overflow: hidden;
        background: var(--bp-navy);
        border-radius: var(--bp-r-lg);
        min-height: 110px;
    }
    .bp-welcome-body {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 22px 26px;
        flex-wrap: wrap;
    }
    .bp-welcome-body h4 { font-size: 1.25rem; font-weight: 700; color: #fff; }
    .bp-welcome-body p  { color: rgba(220,232,245,0.65); font-size: 0.9rem; }

    /* ── Stat cards, matching .statcard on the blueprint reference ─────────── */
    .bp-sgrid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    @media (max-width: 1000px) { .bp-sgrid { grid-template-columns: repeat(2, 1fr); } }
    .bp-statcard {
        background: #fff;
        border: 1px solid var(--bp-border);
        border-top: 3px solid var(--ac);
        border-radius: 0 0 var(--bp-r-lg) var(--bp-r-lg);
        padding: 18px 20px;
        transition: transform .25s var(--bp-ease), box-shadow .25s;
    }
    .bp-statcard:hover { transform: translateY(-3px); box-shadow: var(--bp-sh-md); }
    .bp-srow { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .bp-sl { font-size: .76rem; color: var(--bp-muted); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; }
    .bp-stile { width: 38px; height: 38px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
    .bp-sn { font-size: 1.9rem; font-weight: 900; color: var(--bp-ink); font-feature-settings: "tnum"; margin-bottom: 4px; }
    .bp-slink { font-size: .8rem; color: var(--bp-muted); text-decoration: none; }
    .bp-slink:hover { color: var(--bp-blue); }

    /* ── Two-column panels, matching .panel2/.lrow on the blueprint reference ── */
    .bp-twocol { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 900px) { .bp-twocol { grid-template-columns: 1fr; } }
    .bp-panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
    .bp-ph h5 { font-size: 1rem; font-weight: 700; }
    .bp-pb { padding: 8px 20px; }
    .bp-lrow { display: flex; align-items: center; justify-content: space-between; padding: 13px 0; border-bottom: 1px solid var(--bp-hair); }
    .bp-lrow:last-child { border-bottom: 0; }
    .bp-lt { font-weight: 600; font-size: .92rem; color: var(--bp-ink); text-decoration: none; }
    .bp-lt:hover { color: var(--bp-blue); }
    .bp-lm { font-size: .8rem; color: var(--bp-muted); font-family: ui-monospace, monospace; }

    @media (max-width: 767.98px) {
        .bp-welcome-body { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
        .bp-welcome-body .d-flex.gap-2 { width: 100%; }
        .bp-welcome-body .d-flex.gap-2 .btn { flex: 1; }
    }
    </style>
@endsection
