@extends('layouts.master')
@section('title', 'مرکز کنترل مدیریت')
@section('content')
<main class="engi-dashboard">
    <section class="engi-dashboard__hero">
        <div><div class="engi-eyebrow">مرکز کنترل EngiPi</div><h1>نمای اجرایی پلتفرم</h1><p>رشد کاربران، پروژه‌ها و تعاملات اصلی را بدون داده‌های تزئینی پایش کنید.</p></div>
        <div class="engi-hero-actions"><a href="{{ route('admin.users.index') }}" class="engi-btn"><i class="ri-group-line"></i> کاربران</a><a href="{{ route('admin.projects.index') }}" class="engi-btn engi-btn--primary"><i class="ri-briefcase-4-line"></i> پروژه‌ها</a></div>
    </section>
    <div class="engi-stats">
        <x-dashboard.stat-card label="کارفرماها" :value="$stats['total_employers']" icon="ri-building-2-line" hint="حساب‌های کارفرمایی" />
        <x-dashboard.stat-card label="متخصص‌ها" :value="$stats['total_workers']" icon="ri-user-star-line" tone="violet" hint="حساب‌های تخصصی" />
        <x-dashboard.stat-card label="کل پروژه‌ها" :value="$stats['total_projects']" icon="ri-briefcase-4-line" tone="green" hint="پروژه‌های ثبت‌شده" />
        <x-dashboard.stat-card label="کل درخواست‌ها" :value="$stats['total_requests']" icon="ri-inbox-archive-line" tone="amber" hint="تعامل متخصص و کارفرما" />
    </div>
    <div class="engi-grid">
        <div class="engi-stack">
            <x-dashboard.panel title="آخرین پروژه‌ها" subtitle="نمای سریع جریان پروژه‌های پلتفرم" icon="ri-briefcase-4-line">
                <x-slot:action><a href="{{ route('admin.projects.index') }}" class="engi-badge engi-badge--primary">همه پروژه‌ها</a></x-slot:action>
                <div class="table-responsive"><table class="engi-table engi-table--responsive"><thead><tr><th>پروژه</th><th>کارفرما</th><th>نوع همکاری</th><th>تاریخ</th><th></th></tr></thead><tbody>
                @forelse($recentProjects as $project)<tr>
                    <td data-label="پروژه"><a href="{{ route('admin.projects.show', $project) }}">{{ Str::limit($project->title, 42) }}</a></td>
                    <td data-label="کارفرما">{{ $project->employer->full_name ?? '-' }}</td>
                    <td data-label="نوع همکاری"><span class="engi-badge">{{ __('project.work_type.' . $project->work_type) }}</span></td>
                    <td data-label="تاریخ">{{ $project->created_at->format('Y/m/d') }}</td>
                    <td data-label=""><a aria-label="مشاهده پروژه" href="{{ route('admin.projects.show', $project) }}"><i class="ri-arrow-left-line"></i></a></td>
                </tr>@empty<tr><td colspan="5"><div class="engi-empty"><h3>پروژه‌ای یافت نشد</h3></div></td></tr>@endforelse
                </tbody></table></div>
            </x-dashboard.panel>
            <x-dashboard.panel title="کاربران تازه" subtitle="آخرین حساب‌های ایجادشده" icon="ri-user-add-line">
                <x-slot:action><a href="{{ route('admin.users.index') }}" class="engi-badge engi-badge--primary">همه کاربران</a></x-slot:action>
                <div class="engi-list">@forelse($recentUsers->take(6) as $user)
                    <a class="engi-list-item" href="{{ route('admin.users.show', $user) }}"><span class="engi-list-item__icon">{{ mb_substr($user->full_name ?: $user->name, 0, 1) }}</span><span class="engi-list-item__content"><span class="engi-list-item__title">{{ $user->full_name ?: $user->name }}</span><span class="engi-list-item__meta"><span>{{ $user->display_role }}</span><span>{{ $user->created_at->format('Y/m/d') }}</span></span></span><i class="ri-arrow-left-s-line engi-list-item__action"></i></a>
                @empty<div class="engi-empty"><h3>کاربری یافت نشد</h3></div>@endforelse</div>
            </x-dashboard.panel>
        </div>
        <div class="engi-stack">
            <x-dashboard.panel title="دسترسی مدیریتی" subtitle="عملیات پرتکرار پلتفرم" icon="ri-command-line"><div class="engi-actions">
                <a class="engi-action" href="{{ route('admin.skill-suggestions.index') }}"><i class="ri-lightbulb-flash-line"></i><strong>پیشنهاد مهارت</strong><span>صف بررسی</span></a>
                <a class="engi-action" href="{{ route('admin.skills.index') }}"><i class="ri-tools-line"></i><strong>مهارت‌ها</strong><span>مدیریت ساختار</span></a>
                <a class="engi-action" href="{{ route('admin.tickets.index') }}"><i class="ri-customer-service-2-line"></i><strong>پشتیبانی</strong><span>تیکت‌ها</span></a>
                <a class="engi-action" href="{{ route('admin.domains.index') }}"><i class="ri-node-tree"></i><strong>حوزه‌ها</strong><span>طبقه‌بندی تخصصی</span></a>
            </div></x-dashboard.panel>
            <div class="engi-section-note"><i class="ri-pulse-line"></i><div><strong>سلامت داده‌های داشبورد</strong><span>تمام شاخص‌های این صفحه مستقیماً از داده‌های فعلی سامانه محاسبه می‌شوند؛ شاخص فاقد منبع نمایش داده نشده است.</span></div></div>
        </div>
    </div>
</main>
@endsection
