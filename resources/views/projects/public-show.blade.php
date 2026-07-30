<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.social-meta', [
        'metaTitle' => $project->title,
        'metaDescription' => $description,
        'canonicalUrl' => 'https://www.engipi.com/projects/'.$project->getRouteKey(),
        'socialImage' => $image,
        'socialImageType' => $imageType,
        'socialImageAlt' => $project->title,
    ])
    <link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/vazirmatn.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/engipi/css/blueprint.css') }}">
    <style>
        body { min-height: 100vh; background: var(--bp-surface); }
        .public-header { position: relative; overflow: hidden; background: var(--bp-navy); color: #fff; border-bottom: 3px solid var(--bp-blue); }
        .public-header::before { content: ""; position: absolute; inset: 0; background-image: linear-gradient(rgba(31,111,235,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(31,111,235,.12) 1px,transparent 1px); background-size: 28px 28px; mask-image: linear-gradient(to left,#000,transparent 80%); }
        .public-nav { position: relative; min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .public-brand { display: inline-flex; align-items: center; gap: 12px; color: #fff; font-size: 1.25rem; font-weight: 900; }
        .public-brand-mark { display: grid; place-items: center; width: 42px; height: 42px; border: 1px solid rgba(255,255,255,.2); border-radius: var(--bp-r-lg); background: #fff; color: var(--bp-blue); box-shadow: 0 8px 24px rgba(0,0,0,.18); font-size: 1.4rem; }
        .public-brand small { display: block; color: #aebdd0; font-size: .72rem; font-weight: 500; }
        .public-nav-link { color: #dce8f7; font-size: .9rem; font-weight: 600; }
        .public-nav-link:hover { color: #fff; }
        .public-page { padding-block: 36px 64px; }
        .public-shell { display: grid; grid-template-columns: minmax(0,1fr) 310px; gap: 24px; align-items: start; }
        .public-card { overflow: hidden; border: 1px solid var(--bp-border); border-radius: var(--bp-r-xl); background: #fff; box-shadow: var(--bp-sh-sm); }
        .project-hero { padding: clamp(28px,5vw,48px); border-bottom: 1px solid var(--bp-hair); background: radial-gradient(circle at 8% 0%,var(--bp-tint-blue),transparent 34%),#fff; }
        .project-kicker { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 18px; }
        .project-code { color: var(--bp-muted); font-size: .82rem; direction: ltr; }
        .project-title { max-width: 820px; font-size: clamp(1.75rem,4vw,2.7rem); line-height: 1.45; letter-spacing: -.04em; }
        .project-description { padding: clamp(28px,5vw,44px); }
        .section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; font-size: 1.08rem; }
        .section-title::before { content: ""; width: 4px; height: 22px; border-radius: 10px; background: var(--bp-blue); }
        .project-copy { color: var(--bp-text); font-size: 1rem; line-height: 2.15; overflow-wrap: anywhere; }
        .skills-section { padding: 0 clamp(28px,5vw,44px) clamp(28px,5vw,44px); }
        .skill-list { display: flex; flex-wrap: wrap; gap: 10px; }
        .skill-chip { padding: 8px 14px; border: 1px solid rgba(31,111,235,.18); border-radius: 999px; background: var(--bp-tint-blue); color: var(--bp-blue-d); font-size: .86rem; font-weight: 700; }
        .public-sidebar { display: grid; gap: 18px; }
        .details-card,.cta-card { padding: 24px; }
        .detail-list { display: grid; gap: 0; margin: 0; }
        .detail-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 15px 0; border-bottom: 1px solid var(--bp-hair); }
        .detail-row:last-child { padding-bottom: 0; border-bottom: 0; }
        .detail-row dt { color: var(--bp-muted); font-size: .84rem; }
        .detail-row dd { margin: 0; color: var(--bp-ink); font-size: .9rem; font-weight: 700; text-align: left; }
        .cta-card { position: relative; overflow: hidden; border-color: transparent; background: var(--bp-navy); color: #fff; box-shadow: var(--bp-sh-md); }
        .cta-card::after { content: ""; position: absolute; width: 130px; height: 130px; inset: auto auto -65px -45px; border-radius: 50%; background: rgba(31,111,235,.28); }
        .cta-card h2 { margin-bottom: 10px; color: #fff; font-size: 1.12rem; }
        .cta-card p { margin-bottom: 20px; color: #b9c8da; font-size: .87rem; line-height: 1.9; }
        .cta-card .bp-btn { position: relative; z-index: 1; width: 100%; justify-content: center; }
        .public-footer { padding: 24px; color: var(--bp-muted); font-size: .8rem; text-align: center; }
        @media (max-width: 860px) { .public-shell { grid-template-columns: 1fr; } .public-sidebar { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width: 600px) { .bp-container { padding-inline: 16px; } .public-nav { min-height: 68px; } .public-brand small,.public-nav-link { display: none; } .public-page { padding-block: 18px 40px; } .public-sidebar { grid-template-columns: 1fr; } .project-hero,.project-description,.details-card,.cta-card { padding: 22px; } .skills-section { padding: 0 22px 24px; } .project-title { font-size: 1.55rem; } }
    </style>
</head>
<body>
    @php
        $workTypes = ['remote' => 'دورکاری', 'onsite' => 'حضوری', 'hybrid' => 'ترکیبی'];
        $workType = $workTypes[$project->work_type] ?? null;
        $hasBudget = filled($project->budget_min) || filled($project->budget_max);
        $hasDetails = $workType || $hasBudget || filled($project->duration_days) || filled($project->deadline_date) || filled($project->view_count);
    @endphp

    <header class="public-header">
        <div class="bp-container public-nav">
            <a class="public-brand" href="{{ route('root') }}" aria-label="صفحه اصلی EngiPi">
                <span class="public-brand-mark" aria-hidden="true">E</span>
                <span>EngiPi<small>بازار تخصصی پروژه‌های مهندسی</small></span>
            </a>
            <a class="public-nav-link" href="{{ route('root') }}">مشاهده وب‌سایت EngiPi ←</a>
        </div>
    </header>

    <main class="bp-container public-page">
        <div class="public-shell">
            <article class="public-card">
                <header class="project-hero">
                    <div class="project-kicker">
                        <span class="bp-eyebrow">پروژه مهندسی</span>
                        @if(filled($project->short_id))<span class="project-code">#{{ $project->short_id }}</span>@endif
                    </div>
                    <h1 class="project-title">{{ $project->title }}</h1>
                </header>

                @if(filled($project->description))
                    <section class="project-description" aria-labelledby="project-description-title">
                        <h2 class="section-title" id="project-description-title">شرح پروژه</h2>
                        <div class="project-copy">{!! nl2br(e($project->description)) !!}</div>
                    </section>
                @endif

                @if($project->skills->isNotEmpty())
                    <section class="skills-section" aria-labelledby="project-skills-title">
                        <h2 class="section-title" id="project-skills-title">مهارت‌های مورد نیاز</h2>
                        <div class="skill-list">
                            @foreach($project->skills as $skill)<span class="skill-chip">{{ $skill->name }}</span>@endforeach
                        </div>
                    </section>
                @endif
            </article>

            <aside class="public-sidebar" aria-label="اطلاعات پروژه">
                @if($hasDetails)
                    <section class="public-card details-card">
                        <h2 class="section-title">مشخصات پروژه</h2>
                        <dl class="detail-list">
                            @if($workType)<div class="detail-row"><dt>نوع همکاری</dt><dd>{{ $workType }}</dd></div>@endif
                            @if($hasBudget)
                                <div class="detail-row"><dt>بودجه</dt><dd>
                                    @if(filled($project->budget_min) && filled($project->budget_max))
                                        {{ number_format((float) $project->budget_min) }} تا {{ number_format((float) $project->budget_max) }} تومان
                                    @elseif(filled($project->budget_min))
                                        از {{ number_format((float) $project->budget_min) }} تومان
                                    @else
                                        تا {{ number_format((float) $project->budget_max) }} تومان
                                    @endif
                                </dd></div>
                            @endif
                            @if(filled($project->duration_days))<div class="detail-row"><dt>مدت پروژه</dt><dd>{{ number_format($project->duration_days) }} روز</dd></div>@endif
                            @if(filled($project->deadline_date))<div class="detail-row"><dt>مهلت انجام</dt><dd>{{ \Morilog\Jalali\Jalalian::fromDateTime($project->deadline_date)->format('Y/m/d') }}</dd></div>@endif
                            @if(filled($project->view_count))<div class="detail-row"><dt>تعداد بازدید</dt><dd>{{ number_format($project->view_count) }}</dd></div>@endif
                        </dl>
                    </section>
                @endif

                <section class="public-card cta-card">
                    <h2>پروژه مهندسی دارید؟</h2>
                    <p>در EngiPi پروژه خود را ثبت کنید و با متخصصان حوزه‌های مختلف مهندسی همکاری کنید.</p>
                    <a class="bp-btn bp-btn--primary" href="{{ route('root') }}">ورود به EngiPi</a>
                </section>
            </aside>
        </div>
    </main>

    <footer class="public-footer">EngiPi؛ ارتباط حرفه‌ای برای اجرای پروژه‌های مهندسی</footer>
</body>
</html>
