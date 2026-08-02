@extends('layouts.master')

@section('title', 'داشبورد متخصص')

@section('content')
@php
    $dashboardUser = Auth::user();
    $firstName = trim(explode(' ', $dashboardUser->name)[0] ?? $dashboardUser->name);
    $profileProcesses = $profile ? $profile->processes->take(8) : collect();
    $profileReady = $profileProcesses->isNotEmpty();
    $recommendedProjects = $recentMatchedProjects->take(3);
@endphp

<main class="sp-dashboard">
    <section class="sp-page-intro" aria-labelledby="specialistDashboardTitle">
        <div>
            <span class="sp-page-intro__eyebrow">فضای کاری متخصص</span>
            <h1 id="specialistDashboardTitle">داشبورد حرفه‌ای شما</h1>
            <p>فرصت‌های مرتبط و وضعیت همکاری‌های خود را در یک نگاه دنبال کنید.</p>
        </div>
        <a href="{{ route('user.matched-projects.index') }}" class="sp-primary-action"><i class="ri-compass-3-line"></i>مشاهده پروژه‌ها</a>
    </section>

    <section class="sp-welcome" aria-label="خوش‌آمدگویی">
        <div><h2>سلام {{ $firstName }} <span aria-hidden="true">👋</span></h2><p>خوش آمدید به پنل متخصصان EngiPi؛ فرصت‌های مهندسی مناسب شما اینجا نمایش داده می‌شوند.</p></div>
        <span class="sp-welcome__mark" aria-hidden="true"><i class="ri-shape-2-line"></i></span>
    </section>

    <section class="sp-stats" aria-label="خلاصه عملکرد">
        <x-dashboard.statistic-card label="پروژه‌های متناسب" :value="number_format($stats['matched_projects'])" icon="ri-briefcase-4-line" description="براساس تخصص ثبت‌شده" />
        <x-dashboard.statistic-card label="درخواست‌های ارسالی" :value="number_format($stats['sent_requests'])" icon="ri-send-plane-line" description="مجموع درخواست‌های همکاری" tone="violet" />
        <x-dashboard.statistic-card label="همکاری‌های پذیرفته‌شده" :value="number_format($stats['accepted_requests'])" icon="ri-checkbox-circle-line" description="درخواست‌های تأییدشده" tone="teal" />
        <x-dashboard.statistic-card label="پروفایل تخصصی" :value="$profileReady ? 'آماده' : 'نیازمند تکمیل'" icon="ri-user-settings-line" :description="$profileReady ? 'مهارت‌ها برای تطبیق فعال‌اند' : 'برای پیشنهاد دقیق‌تر تکمیل کنید'" tone="violet" />
    </section>

    <section class="sp-main-grid">
        <x-dashboard.dashboard-card title="پروژه‌های پیشنهادی برای شما" subtitle="بیشترین تطبیق با مهارت‌های ثبت‌شده" class="sp-projects-card">
            <x-slot:action><a href="{{ route('user.matched-projects.index') }}">مشاهده همه پروژه‌ها <i class="ri-arrow-left-line"></i></a></x-slot:action>
            <div class="sp-project-list">
                @forelse($recommendedProjects as $project)
                    <x-dashboard.project-card :project="$project" />
                @empty
                    <div class="sp-empty-state">
                        <i class="ri-radar-line" aria-hidden="true"></i>
                        @if(!$profileReady)
                            <h3>پروفایل تخصصی را تکمیل کنید</h3><p>پس از ثبت مهارت‌ها، پروژه‌های مناسب در این بخش قرار می‌گیرند.</p>
                            <a href="{{ route('user.skills.index') }}">تکمیل مهارت‌ها</a>
                        @else
                            <h3>فعلاً پروژه تازه‌ای نیست</h3><p>به محض ثبت فرصت متناسب، آن را همین‌جا خواهید دید.</p>
                        @endif
                    </div>
                @endforelse
            </div>
        </x-dashboard.dashboard-card>

        <x-dashboard.dashboard-card title="فعالیت و وضعیت اخیر" subtitle="نمای فشرده از جریان همکاری شما" class="sp-activity-card">
            <div class="sp-timeline">
                <x-dashboard.activity-item title="درخواست‌های ارسال‌شده" :description="$stats['sent_requests'].' درخواست در سوابق شما ثبت شده است'" icon="ri-send-plane-2-line" :href="route('user.requests.sent')" />
                <x-dashboard.activity-item title="همکاری‌های پذیرفته‌شده" :description="$stats['accepted_requests'].' درخواست همکاری تأیید شده است'" icon="ri-check-double-line" tone="violet" :href="route('user.requests.sent')" />
                <x-dashboard.activity-item title="فرصت‌های قابل بررسی" :description="$stats['matched_projects'].' پروژه با تخصص شما تطبیق دارد'" icon="ri-sparkling-2-line" :href="route('user.matched-projects.index')" />
                <x-dashboard.activity-item title="وضعیت پروفایل" :description="$profileReady ? 'پروفایل تخصصی برای تطبیق پروژه آماده است' : 'ثبت مهارت‌ها هنوز کامل نشده است'" icon="ri-user-settings-line" tone="violet" :href="route('user.skills.index')" />
            </div>
        </x-dashboard.dashboard-card>
    </section>

    <section class="sp-analytics" aria-label="تحلیل و مهارت‌ها">
        @isset($incomeSeries)
            @if(collect($incomeSeries)->isNotEmpty())
                @php $incomeMax = max(1, collect($incomeSeries)->max('value')); @endphp
                <x-dashboard.dashboard-card title="نمودار درآمد ۶ ماه اخیر" subtitle="روند درآمد ثبت‌شده از همکاری‌ها" class="sp-income-card">
                    <div class="sp-bars">@foreach($incomeSeries as $month)<div><span style="height:{{ max(6, ($month['value'] / $incomeMax) * 100) }}%"></span><small>{{ $month['label'] }}</small></div>@endforeach</div>
                </x-dashboard.dashboard-card>
            @endif
        @endisset

        <x-dashboard.dashboard-card title="مهارت‌های فعال" subtitle="تخصص‌هایی که برای تطبیق پروژه استفاده می‌شوند" class="sp-skills-card">
            <x-slot:action><a href="{{ route('user.skills.index') }}"><i class="ri-add-line"></i>مدیریت مهارت‌ها</a></x-slot:action>
            @if($profileProcesses->isNotEmpty())
                <div class="sp-skill-list">@foreach($profileProcesses as $process)<x-dashboard.skill-chip :name="$process->name" :level="$process->pivot->level ?? null" />@endforeach</div>
            @else
                <div class="sp-inline-empty"><span>هنوز مهارتی برای نمایش ثبت نشده است.</span><a href="{{ route('user.skills.index') }}">افزودن مهارت</a></div>
            @endif
        </x-dashboard.dashboard-card>
    </section>
</main>
@endsection

@push('styles')
<style>
.sp-dashboard{--sp-primary:#16B6B2;--sp-secondary:#635BDF;--sp-bg:#F7F9FC;--sp-text:#172033;--sp-muted:#697386;--sp-border:#E5EAF0;display:grid;gap:24px;max-width:1440px;margin:0 auto;padding:4px 0 36px;color:var(--sp-text);direction:rtl}
.sp-page-intro{display:flex;align-items:flex-end;justify-content:space-between;gap:24px}.sp-page-intro__eyebrow{display:block;margin-bottom:6px;color:var(--sp-primary);font-size:.76rem;font-weight:800}.sp-page-intro h1{margin:0;font-size:clamp(1.45rem,2.5vw,2rem);font-weight:850;letter-spacing:-.025em}.sp-page-intro p{margin:7px 0 0;color:var(--sp-muted);font-size:.9rem}.sp-primary-action{display:inline-flex;min-height:44px;align-items:center;gap:8px;padding:9px 16px;border-radius:11px;background:var(--sp-primary);color:#fff;font-size:.82rem;font-weight:800;box-shadow:0 8px 20px rgba(22,182,178,.18)}.sp-primary-action:hover{background:#109d99;color:#fff;transform:translateY(-1px)}
.sp-welcome{position:relative;display:flex;min-height:120px;align-items:center;justify-content:space-between;overflow:hidden;padding:24px 28px;border:1px solid rgba(99,91,223,.12);border-radius:18px;background:linear-gradient(120deg,#f2fbfb 0%,#f7f7ff 100%)}.sp-welcome h2{margin:0 0 8px;font-size:1.25rem;font-weight:850}.sp-welcome p{max-width:650px;margin:0;color:var(--sp-muted);font-size:.88rem;line-height:1.8}.sp-welcome__mark{display:grid;width:70px;height:70px;place-items:center;border-radius:20px;background:rgba(255,255,255,.72);color:var(--sp-secondary);font-size:2rem;box-shadow:0 10px 28px rgba(99,91,223,.1)}
.sp-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.sp-stat{min-width:0;padding:19px;border:1px solid var(--sp-border);border-radius:16px;background:#fff;box-shadow:0 6px 22px rgba(23,32,51,.045)}.sp-stat__head{display:flex;align-items:center;justify-content:space-between;gap:12px;color:var(--sp-muted);font-size:.78rem;font-weight:700}.sp-stat__head i{display:grid;width:34px;height:34px;place-items:center;border-radius:10px;background:rgba(22,182,178,.1);color:var(--sp-primary);font-size:1.05rem}.sp-stat--violet .sp-stat__head i{background:rgba(99,91,223,.1);color:var(--sp-secondary)}.sp-stat>strong{display:block;margin-top:14px;color:var(--sp-text);font-size:clamp(1.35rem,2vw,1.75rem);font-weight:900;white-space:nowrap}.sp-stat>small{display:block;margin-top:5px;overflow:hidden;color:#8a94a6;font-size:.7rem;text-overflow:ellipsis;white-space:nowrap}
.sp-main-grid{display:grid;grid-template-columns:minmax(0,1.75fr) minmax(290px,.75fr);align-items:start;gap:20px}.sp-card{min-width:0;border:1px solid var(--sp-border);border-radius:18px;background:#fff;box-shadow:0 8px 28px rgba(23,32,51,.045)}.sp-card__header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:20px 22px;border-bottom:1px solid var(--sp-border)}.sp-card__header h2{margin:0;font-size:1rem;font-weight:850}.sp-card__header p{margin:5px 0 0;color:var(--sp-muted);font-size:.74rem}.sp-card__action a{display:inline-flex;align-items:center;gap:5px;color:var(--sp-secondary);font-size:.74rem;font-weight:800}.sp-card__body{padding:8px 22px 14px}
.sp-project-list{display:grid}.sp-project{display:grid;grid-template-columns:48px minmax(0,1fr) auto;align-items:center;gap:14px;padding:17px 0;border-bottom:1px solid var(--sp-border)}.sp-project:last-child{border-bottom:0}.sp-project__thumb{display:grid;width:48px;height:48px;place-items:center;border-radius:13px;background:#eefafa;color:var(--sp-primary);font-size:1.2rem}.sp-project__content{min-width:0}.sp-project__title-row{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.sp-project h3{margin:0;overflow:hidden;font-size:.88rem;font-weight:850;text-overflow:ellipsis;white-space:nowrap}.sp-project__title-row p{margin:4px 0 0;color:var(--sp-muted);font-size:.7rem}.sp-project__title-row>span{flex:0 0 auto;padding:4px 8px;border-radius:999px;background:#eefafa;color:#0f8e8a;font-size:.65rem;font-weight:800}.sp-project__facts,.sp-project__skills{display:flex;flex-wrap:wrap;gap:7px 13px;margin-top:9px}.sp-project__facts span{display:inline-flex;align-items:center;gap:4px;color:var(--sp-muted);font-size:.68rem}.sp-project__facts i{color:var(--sp-primary)}.sp-project__skills span{padding:3px 7px;border:1px solid var(--sp-border);border-radius:6px;background:#fafbfc;color:#596579;font-size:.63rem}.sp-project__button{display:inline-flex;min-height:36px;align-items:center;gap:5px;padding:7px 11px;border:1px solid var(--sp-border);border-radius:9px;color:var(--sp-secondary);font-size:.7rem;font-weight:800}.sp-project__button:hover{border-color:var(--sp-secondary);background:#f5f4ff;color:var(--sp-secondary)}
.sp-timeline{position:relative;padding:8px 0}.sp-timeline:before{position:absolute;inset-block:25px;inset-inline-start:17px;width:1px;background:var(--sp-border);content:""}.sp-activity{position:relative;display:grid;grid-template-columns:36px minmax(0,1fr) 20px;align-items:center;gap:11px;padding:13px 0}.sp-activity__icon{z-index:1;display:grid;width:36px;height:36px;place-items:center;border:5px solid #fff;border-radius:50%;background:#e9f9f8;color:var(--sp-primary);font-size:.9rem}.sp-activity--violet .sp-activity__icon{background:#efefff;color:var(--sp-secondary)}.sp-activity strong{display:block;font-size:.76rem}.sp-activity p{margin:4px 0 0;color:var(--sp-muted);font-size:.66rem;line-height:1.6}.sp-activity>a{color:#9aa3b2;font-size:1rem}.sp-activity>a:hover{color:var(--sp-secondary)}
.sp-analytics{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px}.sp-skills-card .sp-card__body{padding-block:20px}.sp-skill-list{display:flex;flex-wrap:wrap;gap:9px}.sp-skill-chip{display:inline-flex;min-height:34px;align-items:center;gap:6px;padding:6px 10px;border:1px solid var(--sp-border);border-radius:999px;background:#fafcfd;color:#455165;font-size:.72rem;font-weight:700}.sp-skill-chip>i{color:var(--sp-primary)}.sp-skill-chip small{padding-inline-start:7px;border-inline-start:1px solid var(--sp-border);color:var(--sp-secondary);font-size:.62rem}.sp-inline-empty{display:flex;align-items:center;justify-content:space-between;gap:16px;color:var(--sp-muted);font-size:.78rem}.sp-inline-empty a,.sp-empty-state a{color:var(--sp-secondary);font-weight:800}.sp-bars{display:flex;height:190px;align-items:flex-end;justify-content:space-around;gap:12px;padding-top:22px}.sp-bars>div{display:flex;height:100%;flex:1;flex-direction:column;align-items:center;justify-content:flex-end;gap:8px}.sp-bars span{width:min(38px,70%);border-radius:8px 8px 3px 3px;background:linear-gradient(180deg,var(--sp-secondary),var(--sp-primary))}.sp-bars small{color:var(--sp-muted);font-size:.65rem}.sp-empty-state{display:grid;min-height:210px;place-items:center;align-content:center;gap:8px;text-align:center}.sp-empty-state>i{font-size:2rem;color:var(--sp-primary)}.sp-empty-state h3{margin:0;font-size:.9rem}.sp-empty-state p{max-width:380px;margin:0;color:var(--sp-muted);font-size:.72rem;line-height:1.7}
@media(max-width:1100px){.sp-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.sp-main-grid{grid-template-columns:1fr}.sp-activity-card{order:2}}
@media(max-width:767.98px){.sp-dashboard{gap:16px;padding-bottom:24px}.sp-page-intro{align-items:flex-start;flex-direction:column}.sp-primary-action{width:100%;justify-content:center}.sp-welcome{min-height:112px;padding:20px}.sp-welcome__mark{display:none}.sp-stats{grid-template-columns:1fr;gap:12px}.sp-card__header{align-items:flex-start;padding:17px;flex-direction:column}.sp-card__body{padding-inline:17px}.sp-project{grid-template-columns:42px minmax(0,1fr);gap:10px}.sp-project__thumb{width:42px;height:42px}.sp-project__button{grid-column:1/-1;justify-content:center}.sp-project__title-row{display:block}.sp-project__title-row>span{display:inline-block;margin-top:7px}.sp-project__facts{gap:6px 10px}.sp-analytics{grid-template-columns:minmax(0,1fr)}.sp-inline-empty{align-items:flex-start;flex-direction:column}}
</style>
@endpush