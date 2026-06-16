<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درباره EngPis</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Vazirmatn', sans-serif !important; box-sizing: border-box; }
        :root {
            --clr-primary: #405189;
            --clr-accent:  #0ab39c;
            --clr-dark:    #1a1d2e;
            --clr-muted:   #878a99;
            --clr-surface: #f8f9fc;
        }
        body { color: #333; scroll-behavior: smooth; margin: 0; }

        /* ── Navbar ── */
        .ep-nav {
            background: linear-gradient(135deg, #0f1225 0%, #405189 60%, #0c6b5a 100%);
            position: sticky; top: 0; z-index: 1000;
        }
        .brand-text  { font-size: 1.6rem; font-weight: 900; color: white; text-decoration: none; }
        .brand-accent { color: var(--clr-accent); }
        .btn-nav-outline {
            border: 1.5px solid rgba(255,255,255,0.7); color: white !important;
            border-radius: 50px; padding: 0.35rem 1.2rem;
            font-size: 0.88rem; font-weight: 600; transition: all 0.2s; text-decoration: none;
        }
        .btn-nav-outline:hover { background: rgba(255,255,255,0.15); }
        .btn-cta-sm {
            background: var(--clr-accent); color: white; border: none;
            padding: 0.4rem 1.2rem; border-radius: 50px; font-weight: 700;
            font-size: 0.88rem; text-decoration: none; transition: all 0.2s;
        }
        .btn-cta-sm:hover { background: #089b87; color: white; }

        /* ── Hero ── */
        .page-hero {
            background: linear-gradient(135deg, #0f1225 0%, var(--clr-primary) 55%, #0c8b7a 100%);
            padding: 80px 0 70px;
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: ''; position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px; border-radius: 50%;
            background: rgba(255,255,255,0.04); pointer-events: none;
        }
        .page-hero::after {
            content: ''; position: absolute;
            bottom: -60px; left: -60px;
            width: 260px; height: 260px; border-radius: 50%;
            background: rgba(255,255,255,0.03); pointer-events: none;
        }
        .hero-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(10,179,156,0.18); color: #4eded0;
            border: 1px solid rgba(10,179,156,0.3); border-radius: 50px;
            padding: 0.35rem 1rem; font-size: 0.82rem; font-weight: 600;
        }

        /* ── Stats bar ── */
        .stats-bar { background: white; box-shadow: 0 4px 30px rgba(0,0,0,0.06); }
        .stat-num  { font-size: 2.2rem; font-weight: 900; color: var(--clr-primary); }
        .stat-lbl  { color: var(--clr-muted); font-size: 0.85rem; margin-top: 2px; }
        .stat-divider { width: 1px; height: 55px; background: #e8e8e8; }

        /* ── Sections ── */
        .section-wrap { padding: 90px 0; }
        .section-bg   { background: var(--clr-surface); }
        .eyebrow {
            display: inline-block;
            background: rgba(64,81,137,0.09); color: var(--clr-primary);
            border-radius: 50px; padding: 0.35rem 1.1rem;
            font-size: 0.82rem; font-weight: 700; letter-spacing: 0.4px; margin-bottom: 1rem;
        }
        .sec-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 900; color: var(--clr-dark); }

        /* ── Cards ── */
        .feature-card {
            background: white; border: 1px solid #ebebf0; border-radius: 16px;
            padding: 1.8rem; height: 100%; transition: all 0.25s;
        }
        .feature-card:hover {
            border-color: transparent;
            box-shadow: 0 12px 36px rgba(0,0,0,0.07); transform: translateY(-3px);
        }
        .feat-icon {
            width: 52px; height: 52px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 1.1rem;
        }

        /* ── Timeline ── */
        .timeline { position: relative; padding-right: 2rem; }
        .timeline::before {
            content: ''; position: absolute;
            right: 11px; top: 8px; bottom: 8px;
            width: 2px; background: #e5eaf3;
        }
        .timeline-item { position: relative; margin-bottom: 2rem; padding-right: 2.5rem; }
        .timeline-dot {
            position: absolute; right: 0; top: 4px;
            width: 22px; height: 22px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 800; color: white;
            box-shadow: 0 3px 10px rgba(64,81,137,0.3);
        }

        /* ── Vision banner ── */
        .vision-banner {
            background: linear-gradient(135deg, #0f1225 0%, #405189 55%, #0c8b7a 100%);
            border-radius: 24px;
        }
        .btn-cta-primary {
            background: var(--clr-accent); color: white; border: none;
            padding: 0.85rem 2.2rem; border-radius: 50px;
            font-weight: 700; font-size: 1rem; transition: all 0.25s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-cta-primary:hover { background: #089b87; color: white; transform: translateY(-2px); }
        .btn-cta-outline-wh {
            border: 2px solid rgba(255,255,255,0.55); color: white;
            padding: 0.85rem 2.2rem; border-radius: 50px;
            font-weight: 700; font-size: 1rem; transition: all 0.25s;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            background: transparent;
        }
        .btn-cta-outline-wh:hover { background: rgba(255,255,255,0.12); color: white; }

        /* ── Footer ── */
        .site-footer { background: var(--clr-dark); color: rgba(255,255,255,0.6); }
        .site-footer h6 { color: white; }
        .site-footer a  { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s; }
        .site-footer a:hover { color: white; }
        .footer-social {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7); transition: all 0.2s;
        }
        .footer-social:hover { background: var(--clr-accent); color: white !important; }
        .footer-hr { border-color: rgba(255,255,255,0.08); }

        @media (max-width: 767px) {
            .section-wrap { padding: 60px 0; }
            .stat-divider  { display: none; }
            .page-hero { padding: 70px 0 55px; }
            #navMenu.show {
                background: rgba(15,18,37,0.97);
                border-radius: 10px; padding: 1rem; margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>

{{-- ═══════════ NAVBAR ═══════════ --}}
<nav class="ep-nav py-3 navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand brand-text" href="{{ route('root') }}">
            <span class="brand-accent">Eng</span>Pis
        </a>
        <button class="navbar-toggler border-0 text-white" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="ri-menu-line fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="{{ route('root') }}#how-it-works">چگونه کار می‌کند؟</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('about') }}"
                       style="color: var(--clr-accent) !important;">درباره ما</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="{{ route('terms') }}">قوانین</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="btn-cta-sm">
                        <i class="ri-dashboard-line me-1"></i>داشبورد
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white fw-medium text-decoration-none px-2">ورود</a>
                    <a href="{{ route('register') }}" class="btn-nav-outline">ثبت‌نام رایگان</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ═══════════ HERO ═══════════ --}}
<section class="page-hero text-white text-center">
    <div class="container" style="position: relative; z-index: 2;">
        <div class="hero-tag mb-3">
            <i class="ri-building-line"></i> تهران، ایران · ۱۴۰۴
        </div>
        <h1 style="font-size: clamp(2rem, 4.5vw, 3rem); font-weight: 900; margin: 0.8rem 0 1rem;">
            داستان <span style="color: var(--clr-accent);">EngPis</span>
        </h1>
        <p style="font-size: 1.05rem; opacity: 0.82; max-width: 540px; margin: 0 auto; line-height: 1.85;">
            پلتفرمی که مهندسان ایران را به پروژه‌های واقعی وصل می‌کند
        </p>
    </div>
</section>

{{-- ═══════════ STATS BAR ═══════════ --}}
<div class="stats-bar py-4">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5 text-center">
            <div>
                <div class="stat-num">+۵۰۰</div>
                <div class="stat-lbl">پروژه ثبت‌شده</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">+۱۲۰۰</div>
                <div class="stat-lbl">مهندس متخصص</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">+۱۵</div>
                <div class="stat-lbl">حوزه تخصصی</div>
            </div>
            <div class="stat-divider"></div>
            <div>
                <div class="stat-num">۱۴۰۴</div>
                <div class="stat-lbl">سال تأسیس</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ STORY ═══════════ --}}
<section class="section-wrap">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <span class="eyebrow">داستان ما</span>
                <h2 class="sec-title mb-4">از کجا شروع کردیم؟</h2>
                <p class="text-muted mb-3" style="line-height: 1.95; font-size: 0.97rem;">
                    EngPis در سال <strong>۱۴۰۴</strong> در <strong>تهران</strong> با یک ایده ساده متولد شد: بازار پروژه‌های مهندسی ایران، پراکنده و ناکارآمد است. کارفرمایان ساعت‌ها وقت می‌گذارند تا متخصص مناسب پیدا کنند و متخصصان فنی نمی‌دانند پروژه‌های واقعی را کجا بیابند.
                </p>
                <p class="text-muted mb-3" style="line-height: 1.95; font-size: 0.97rem;">
                    ما <strong>تیمی از مهندسان و متخصصان فناوری</strong> هستیم که خودمان این مشکل را تجربه کرده‌ایم. به همین دلیل تصمیم گرفتیم EngPis را بسازیم — پلتفرمی هوشمند که با الگوریتم تطبیق تخصصی، صاحبان پروژه‌های مهندسی را با بهترین متخصص در حوزه مرتبط می‌کند.
                </p>
                <p class="text-muted" style="line-height: 1.95; font-size: 0.97rem;">
                    مأموریت ما ساده است: <strong>اتصال صاحبان پروژه‌های مهندسی به مهندسان متخصص</strong> — سریع، دقیق، و قابل اعتماد.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="feature-card">
                            <div class="feat-icon" style="background: rgba(64,81,137,0.09);">
                                <i class="ri-map-pin-2-line" style="color: var(--clr-primary);"></i>
                            </div>
                            <h6 class="fw-bold mb-1">مکان</h6>
                            <p class="text-muted small mb-0">تهران، ایران</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card">
                            <div class="feat-icon" style="background: rgba(10,179,156,0.1);">
                                <i class="ri-calendar-check-line" style="color: var(--clr-accent);"></i>
                            </div>
                            <h6 class="fw-bold mb-1">تأسیس</h6>
                            <p class="text-muted small mb-0">سال ۱۴۰۴</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card">
                            <div class="feat-icon" style="background: rgba(108,99,255,0.1);">
                                <i class="ri-team-line" style="color: #6c63ff;"></i>
                            </div>
                            <h6 class="fw-bold mb-1">تیم</h6>
                            <p class="text-muted small mb-0">مهندسان و متخصصان فناوری</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card">
                            <div class="feat-icon" style="background: rgba(255,152,0,0.1);">
                                <i class="ri-cpu-line" style="color: #ff9800;"></i>
                            </div>
                            <h6 class="fw-bold mb-1">رویکرد</h6>
                            <p class="text-muted small mb-0">تطبیق هوشمند تخصصی</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════ MISSION ═══════════ --}}
<section class="section-wrap section-bg">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">مأموریت</span>
            <h2 class="sec-title">چرا EngPis وجود دارد؟</h2>
            <p class="text-muted mt-3" style="max-width: 560px; margin: 1rem auto 0; line-height: 1.85;">
                ما برای حل یک مشکل واقعی در اکوسیستم مهندسی ایران ساخته شدیم
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feat-icon mx-auto" style="background: rgba(10,179,156,0.1);">
                        <i class="ri-links-line" style="color: var(--clr-accent);"></i>
                    </div>
                    <h5 class="fw-bold mb-2">اتصال هوشمند</h5>
                    <p class="text-muted small mb-0">اتصال دقیق کارفرمایان پروژه‌های مهندسی با متخصصان واقعی از طریق الگوریتم تطبیق بر اساس حوزه، زیرشاخه و سطح مهارت</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feat-icon mx-auto" style="background: rgba(64,81,137,0.09);">
                        <i class="ri-shield-check-line" style="color: var(--clr-primary);"></i>
                    </div>
                    <h5 class="fw-bold mb-2">اعتماد و شفافیت</h5>
                    <p class="text-muted small mb-0">ایجاد محیطی شفاف و قابل اعتماد برای همکاری مهندسی — بدون واسطه‌های غیرضروری</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feat-icon mx-auto" style="background: rgba(108,99,255,0.1);">
                        <i class="ri-rocket-2-line" style="color: #6c63ff;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">رشد اکوسیستم</h5>
                    <p class="text-muted small mb-0">توسعه اکوسیستم مهندسی ایران با تسهیل همکاری‌های تخصصی در بیش از ۱۵ حوزه فنی</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ HOW WE WORK ═══════════ --}}
<section class="section-wrap">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <span class="eyebrow">نحوه کار ما</span>
                <h2 class="sec-title mb-4">الگوریتم تطبیق تخصصی</h2>
                <p class="text-muted" style="line-height: 1.9; font-size: 0.97rem;">
                    برخلاف پلتفرم‌های عمومی، EngPis از یک الگوریتم هوشمند استفاده می‌کند که پروژه‌ها را بر اساس <strong>حوزه تخصصی</strong>، <strong>زیرشاخه</strong>، <strong>ابزار</strong> و <strong>سطح مهارت</strong> با متخصصان مطابقت می‌دهد.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: var(--clr-primary);">۱</div>
                        <h6 class="fw-bold mb-1">کارفرما پروژه ثبت می‌کند</h6>
                        <p class="text-muted small mb-0">مشخصات فنی پروژه، حوزه تخصصی، ابزارهای مورد نیاز و سطح مهارت مورد انتظار را وارد می‌کند</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: var(--clr-accent);">۲</div>
                        <h6 class="fw-bold mb-1">الگوریتم تطبیق اجرا می‌شود</h6>
                        <p class="text-muted small mb-0">سیستم بر اساس مهارت‌های ثبت‌شده متخصصان، بهترین تطابق را پیدا می‌کند</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: #6c63ff;">۳</div>
                        <h6 class="fw-bold mb-1">متخصص درخواست ارسال می‌کند</h6>
                        <p class="text-muted small mb-0">متخصصان واجد شرایط پروژه را در داشبورد خود می‌بینند و درخواست همکاری ارسال می‌کنند</p>
                    </div>
                    <div class="timeline-item" style="margin-bottom: 0;">
                        <div class="timeline-dot" style="background: #ff9800;">۴</div>
                        <h6 class="fw-bold mb-1">کارفرما بهترین را انتخاب می‌کند</h6>
                        <p class="text-muted small mb-0">از بین درخواست‌های دریافتی، مناسب‌ترین متخصص برای همکاری انتخاب می‌شود</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ VISION ═══════════ --}}
<section class="section-wrap">
    <div class="container">
        <div class="vision-banner text-white text-center p-5">
            <span class="hero-tag d-inline-flex mb-3">
                <i class="ri-eye-line"></i> چشم‌انداز
            </span>
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 900; margin: 0.5rem 0 1rem;">
                بزرگ‌ترین مارکت‌پلیس مهندسی ایران
            </h2>
            <p style="font-size: 1rem; opacity: 0.82; max-width: 580px; margin: 0 auto 2rem; line-height: 1.9;">
                چشم‌انداز ما این است که EngPis به معتبرترین پلتفرم اتصال کارفرمایان و متخصصان مهندسی در ایران تبدیل شود — جایی که هر پروژه فنی بتواند بهترین مهندس متخصص خود را پیدا کند.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn-cta-primary">
                    <i class="ri-add-circle-line"></i>ثبت پروژه
                </a>
                <a href="{{ route('register') }}" class="btn-cta-outline-wh">
                    <i class="ri-user-star-line"></i>عضویت به عنوان متخصص
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ FOOTER ═══════════ --}}
<footer class="site-footer py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="text-white fw-bold mb-3">
                    <span style="color: var(--clr-accent);">Eng</span>Pis
                </h4>
                <p style="font-size: 0.9rem; line-height: 1.8;">
                    بزرگ‌ترین مارکت‌پلیس تخصصی پروژه‌های فنی و مهندسی ایران.
                    جایی که کارفرمایان و متخصصان مهندسی به هم وصل می‌شوند.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="footer-social"><i class="ri-instagram-line"></i></a>
                    <a href="#" class="footer-social"><i class="ri-linkedin-box-line"></i></a>
                    <a href="#" class="footer-social"><i class="ri-telegram-line"></i></a>
                    <a href="#" class="footer-social"><i class="ri-twitter-x-line"></i></a>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 offset-lg-2">
                <h6 class="fw-bold mb-3">EngPis</h6>
                <ul class="list-unstyled" style="font-size: 0.88rem;">
                    <li class="mb-2"><a href="{{ route('about') }}">درباره ما</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}">قوانین و مقررات</a></li>
                    <li class="mb-2"><a href="{{ route('root') }}">صفحه اصلی</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h6 class="fw-bold mb-3">کارفرمایان</h6>
                <ul class="list-unstyled" style="font-size: 0.88rem;">
                    <li class="mb-2"><a href="{{ route('register') }}">ثبت پروژه</a></li>
                    <li class="mb-2"><a href="{{ route('root') }}#how-it-works">نحوه کار</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h6 class="fw-bold mb-3">متخصصان</h6>
                <ul class="list-unstyled" style="font-size: 0.88rem;">
                    <li class="mb-2"><a href="{{ route('register') }}">ثبت‌نام</a></li>
                    <li class="mb-2"><a href="{{ route('root') }}#domains">حوزه‌های تخصصی</a></li>
                </ul>
            </div>
        </div>
        <hr class="footer-hr mt-4 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2" style="font-size: 0.83rem;">
            <span>© ۱۴۰۴ EngPis. تمامی حقوق محفوظ است.</span>
            <div class="d-flex gap-3">
                <a href="{{ route('terms') }}">قوانین و مقررات</a>
                <a href="{{ route('terms') }}">حریم خصوصی</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
