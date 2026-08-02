@extends('layouts.master')
@section('title', 'داشبورد کارفرما')
@section('content')
<main class="engi-dashboard">
    <section class="engi-dashboard__hero">
        <div><div class="engi-eyebrow">مرکز مدیریت پروژه</div><h1>روز بخیر، {{ Auth::user()->name }}</h1><p>پروژه‌ها و درخواست‌های متخصصان را از یک نمای روشن مدیریت کنید.</p></div>
        <div class="engi-hero-actions">
            <a href="{{ route('user.projects.index') }}" class="engi-btn"><i class="ri-briefcase-line"></i> پروژه‌های من</a>
            <a href="{{ route('employer.projects.create') }}" class="engi-btn engi-btn--primary"><i class="ri-add-line"></i> ثبت پروژه جدید</a>
        </div>
    </section>
    <div class="engi-stats">
        <x-dashboard.stat-card label="کل پروژه‌ها" :value="$stats['total_projects']" icon="ri-briefcase-4-line" hint="پروژه‌های ثبت‌شده شما" />
        <x-dashboard.stat-card label="در انتظار بررسی" :value="$stats['pending_requests']" icon="ri-time-line" tone="amber" hint="نیازمند تصمیم شما" />
        <x-dashboard.stat-card label="درخواست پذیرفته‌شده" :value="$stats['accepted_requests']" icon="ri-checkbox-circle-line" tone="green" hint="همکاری‌های تأییدشده" />
        <x-dashboard.stat-card label="وضعیت همکاری" :value="$stats['accepted_requests'] > 0 ? 'فعال' : 'آماده شروع'" icon="ri-pulse-line" tone="violet" hint="بر پایه درخواست‌های پذیرفته‌شده" />
    </div>
    <div class="engi-grid">
        <x-dashboard.panel title="پروژه‌های اخیر" subtitle="آخرین پروژه‌هایی که ثبت کرده‌اید" icon="ri-history-line">
            <x-slot:action><a href="{{ route('user.projects.index') }}" class="engi-badge engi-badge--primary">مشاهده همه <i class="ri-arrow-left-line"></i></a></x-slot:action>
            @forelse($recentProjects as $project)
                <a class="engi-list-item" href="{{ route('user.projects.show', $project) }}">
                    <span class="engi-list-item__icon"><i class="ri-draft-line"></i></span>
                    <span class="engi-list-item__content">
                        <span class="engi-list-item__title">{{ $project->title }}</span>
                        <span class="engi-list-item__meta">
                            <span>{{ $project->jalali_created_at ?? $project->created_at->format('Y/m/d') }}</span>
                            @if($project->skills->isNotEmpty())<span>{{ $project->skills->take(2)->pluck('name')->join('، ') }}</span>@endif
                        </span>
                    </span>
                    <i class="ri-arrow-left-s-line engi-list-item__action"></i>
                </a>
            @empty
                <div class="engi-empty"><div class="engi-empty__icon"><i class="ri-briefcase-4-line"></i></div><h3>هنوز پروژه‌ای ثبت نشده است</h3><p>نیاز مهندسی خود را ثبت کنید تا متخصصان مرتبط بتوانند برای همکاری درخواست ارسال کنند.</p><a href="{{ route('employer.projects.create') }}" class="engi-btn engi-btn--primary">ثبت اولین پروژه</a></div>
            @endforelse
        </x-dashboard.panel>
        <div class="engi-stack">
            <x-dashboard.panel title="اقدام‌های سریع" subtitle="مسیرهای پرکاربرد کارفرما" icon="ri-flashlight-line">
                <div class="engi-actions">
                    <a class="engi-action" href="{{ route('employer.projects.create') }}"><i class="ri-add-circle-line"></i><strong>پروژه جدید</strong><span>شرح نیاز مهندسی</span></a>
                    <a class="engi-action" href="{{ route('user.requests.received') }}"><i class="ri-inbox-archive-line"></i><strong>درخواست‌ها</strong><span>بررسی متخصصان</span></a>
                    <a class="engi-action" href="{{ route('user.messages.index') }}"><i class="ri-message-3-line"></i><strong>پیام‌ها</strong><span>گفت‌وگوهای کاری</span></a>
                    <a class="engi-action" href="{{ route('user.tickets.index') }}"><i class="ri-customer-service-2-line"></i><strong>پشتیبانی</strong><span>پیگیری تیکت‌ها</span></a>
                </div>
            </x-dashboard.panel>
            <div class="engi-section-note"><i class="ri-shield-check-line"></i><div><strong>همکاری شفاف‌تر</strong><span>شرح کامل پروژه و پاسخ‌گویی سریع به درخواست‌ها، انتخاب متخصص مناسب را ساده‌تر می‌کند.</span></div></div>
        </div>
    </div>
</main>
@endsection
