@extends('layouts.ep-dashboard')

@section('title', 'داشبورد | EngPis')

@php
    $activePage = 'dashboard';
    $pageTitle  = 'داشبورد';
    $user = auth()->user();
    $userName = $user?->name ?? $user?->mobile ?? 'کاربر';
@endphp

@section('content')
<div class="content">

    {{-- Welcome banner --}}
    <div class="welcome">
        <div class="c1"></div>
        <div class="c2"></div>
        <div>
            <h4>سلام، {{ $userName }}!</h4>
            <p>خلاصه وضعیت حساب کاربری شما</p>
        </div>
        <div class="welcome-actions">
            @php
                try { $createProjectUrl = route('user.projects.create'); } catch (\Exception $e) { $createProjectUrl = '#'; }
                try { $skillsUrl = route('user.skills.index'); } catch (\Exception $e) { $skillsUrl = '#'; }
            @endphp
            <a href="{{ $createProjectUrl }}" class="btn btn-accent">
                <i class="ri-add-line"></i>ثبت پروژه
            </a>
            <a href="{{ $skillsUrl }}" class="btn" style="background:rgba(255,255,255,.14); color:#fff;">
                <i class="ri-star-line"></i>مهارت‌ها
            </a>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="stat-grid">
        @php
            try { $projectsUrl  = route('user.projects.index'); } catch (\Exception $e) { $projectsUrl  = '#'; }
            try { $requestsUrl  = route('user.requests.received'); } catch (\Exception $e) { $requestsUrl  = '#'; }
            try { $matchedUrl   = route('user.matched-projects.index'); } catch (\Exception $e) { $matchedUrl   = '#'; }
            try { $sentUrl      = route('user.requests.sent'); } catch (\Exception $e) { $sentUrl      = '#'; }
        @endphp
        <div class="statcard" style="--acc:#0ab39c;">
            <div class="row">
                <span class="lbl">پروژه‌های من</span>
                <div class="tile" style="background:var(--ep-tint-accent); color:#0ab39c;"><i class="ri-briefcase-line"></i></div>
            </div>
            <p class="num" style="color:#0ab39c;">{{ $myProjectsCount ?? '—' }}</p>
            <a href="{{ $projectsUrl }}" class="link">مشاهده همه ←</a>
        </div>
        <div class="statcard" style="--acc:#03a9f4;">
            <div class="row">
                <span class="lbl">درخواست‌های دریافتی</span>
                <div class="tile" style="background:var(--ep-tint-sky); color:#03a9f4;"><i class="ri-inbox-line"></i></div>
            </div>
            <p class="num" style="color:#03a9f4;">{{ $receivedRequestsCount ?? '—' }}</p>
            <a href="{{ $requestsUrl }}" class="link">مشاهده همه ←</a>
        </div>
        <div class="statcard" style="--acc:#43a047;">
            <div class="row">
                <span class="lbl">پروژه‌های پیشنهادی</span>
                <div class="tile" style="background:var(--ep-tint-green); color:#43a047;"><i class="ri-lightbulb-flash-line"></i></div>
            </div>
            <p class="num" style="color:#43a047;">{{ $matchedCount ?? '—' }}</p>
            <a href="{{ $matchedUrl }}" class="link">مشاهده همه ←</a>
        </div>
        <div class="statcard" style="--acc:#d4a017;">
            <div class="row">
                <span class="lbl">درخواست‌های ارسالی</span>
                <div class="tile" style="background:var(--ep-tint-amber); color:#d4a017;"><i class="ri-send-plane-2-line"></i></div>
            </div>
            <p class="num" style="color:#d4a017;">{{ $sentRequestsCount ?? '—' }}</p>
            <a href="{{ $sentUrl }}" class="link">مشاهده همه ←</a>
        </div>
    </div>

    {{-- Two-column cards --}}
    <div class="two-col">
        <div class="card">
            <div class="card-head">
                <h5>آخرین پروژه‌های من</h5>
                <a href="{{ $projectsUrl }}" class="btn btn-soft-primary">مشاهده همه</a>
            </div>
            <div class="card-body">
                <div class="list">
                    @forelse($myProjects ?? [] as $project)
                        <div class="list-row">
                            <a href="#">{{ $project->title }}</a>
                            <span class="meta">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($project->created_at))->format('Y/m/d') }}</span>
                        </div>
                    @empty
                        <p style="color:var(--ep-muted); font-size:.9rem; margin:0;">هنوز پروژه‌ای ثبت نشده است.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h5>پروژه‌های پیشنهادی</h5>
                <a href="{{ $matchedUrl }}" class="btn btn-soft-accent">مشاهده همه</a>
            </div>
            <div class="card-body">
                <div class="list">
                    @forelse($matchedProjects ?? [] as $project)
                        <div class="list-row">
                            <a style="color:var(--ep-accent-600);">{{ $project->title }}</a>
                            <span class="badge b-soft-primary">{{ $project->domains->first()?->name ?? '' }}</span>
                        </div>
                    @empty
                        <p style="color:var(--ep-muted); font-size:.9rem; margin:0;">پروژه‌ای برای نمایش وجود ندارد.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
