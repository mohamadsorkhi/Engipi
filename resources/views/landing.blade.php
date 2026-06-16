<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EngPis — مارکت‌پلیس تخصصی پروژه‌های مهندسی</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Vazirmatn', sans-serif !important; }

        :root {
            --clr-primary:   #405189;
            --clr-accent:    #0ab39c;
            --clr-dark:      #1a1d2e;
            --clr-muted:     #878a99;
            --clr-surface:   #f8f9fc;
        }

        * { box-sizing: border-box; }

        body {
            color: #333;
            scroll-behavior: smooth;
        }

        /* ── Navbar ─────────────────────────────────────────────── */
        #mainNav {
            transition: background 0.3s, box-shadow 0.3s;
            z-index: 1000;
        }
        #mainNav.pinned {
            position: fixed !important;
            top: 0;
            background: rgba(255,255,255,0.97) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        #mainNav.pinned .nav-link,
        #mainNav.pinned .brand-text { color: var(--clr-dark) !important; }
        #mainNav.pinned .btn-nav-outline {
            border-color: var(--clr-primary);
            color: var(--clr-primary) !important;
        }

        .brand-text { font-size: 1.75rem; font-weight: 900; }
        .brand-accent { color: var(--clr-accent); }

        /* ── Hero ────────────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f1225 0%, var(--clr-primary) 55%, #0c8b7a 100%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 480px; height: 480px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -80px;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(10,179,156,0.18);
            color: #4eded0;
            border: 1px solid rgba(10,179,156,0.3);
            border-radius: 50px;
            padding: 0.35rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .hero-title {
            font-size: clamp(2rem, 4.5vw, 3.2rem);
            font-weight: 900;
            line-height: 1.3;
        }
        .hero-title .highlight { color: var(--clr-accent); }

        .hero-sub { font-size: 1.1rem; opacity: 0.82; max-width: 520px; line-height: 1.8; }

        .btn-cta-primary {
            background: var(--clr-accent);
            color: white;
            border: none;
            padding: 0.85rem 2.2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s;
        }
        .btn-cta-primary:hover {
            background: #089b87;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(10,179,156,0.38);
        }
        .btn-cta-outline {
            border: 2px solid rgba(255,255,255,0.55);
            color: white;
            padding: 0.85rem 2.2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s;
            background: transparent;
        }
        .btn-cta-outline:hover {
            background: rgba(255,255,255,0.12);
            border-color: white;
            color: white;
        }
        .btn-nav-outline {
            border: 1.5px solid rgba(255,255,255,0.7);
            color: white !important;
            border-radius: 50px;
            padding: 0.35rem 1.2rem;
            font-size: 0.88rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-nav-outline:hover { background: rgba(255,255,255,0.15); }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 7px;
            color: rgba(255,255,255,0.6);
            font-size: 0.88rem;
        }

        /* Floating hero cards */
        .hero-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-14px); }
        }
        .floating { animation: float 5s ease-in-out infinite; }

        /* Wave divider */
        .wave-bottom { position: absolute; bottom: 0; left: 0; right: 0; line-height: 0; }

        /* ── Stats ───────────────────────────────────────────────── */
        .stats-bar {
            background: white;
            box-shadow: 0 4px 30px rgba(0,0,0,0.06);
            position: relative;
            z-index: 1;
        }
        .stat-num  { font-size: 2.4rem; font-weight: 900; color: var(--clr-primary); }
        .stat-lbl  { color: var(--clr-muted); font-size: 0.88rem; margin-top: 2px; }
        .stat-divider {
            width: 1px;
            height: 60px;
            background: #e8e8e8;
        }

        /* ── Shared section helpers ─────────────────────────────── */
        .section-wrap { padding: 90px 0; }
        .section-bg   { background: var(--clr-surface); }

        .eyebrow {
            display: inline-block;
            background: rgba(64,81,137,0.09);
            color: var(--clr-primary);
            border-radius: 50px;
            padding: 0.35rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            margin-bottom: 1rem;
        }
        .sec-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 900;
            color: var(--clr-dark);
        }

        /* ── How it works ────────────────────────────────────────── */
        .how-tab-nav { background: #eef0f6; border-radius: 50px; padding: 6px; }
        .how-tab-nav .nav-link {
            border-radius: 50px;
            padding: 0.55rem 1.8rem;
            font-weight: 600;
            color: var(--clr-muted);
            transition: all 0.2s;
        }
        .how-tab-nav .nav-link.active {
            background: var(--clr-primary);
            color: white;
            box-shadow: 0 4px 14px rgba(64,81,137,0.3);
        }

        .step-card {
            background: white;
            border: 1px solid #ebebf0;
            border-radius: 18px;
            padding: 2.2rem 1.8rem;
            height: 100%;
            position: relative;
            transition: all 0.28s;
        }
        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 45px rgba(0,0,0,0.08);
            border-color: transparent;
        }
        .step-num {
            position: absolute;
            top: -13px; left: 50%; transform: translateX(-50%);
            width: 28px; height: 28px;
            background: var(--clr-primary);
            color: white;
            border-radius: 50%;
            font-size: 0.72rem;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 10px rgba(64,81,137,0.35);
        }
        .step-icon-wrap {
            width: 70px; height: 70px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.3rem;
            font-size: 1.9rem;
        }

        /* ── Domains ─────────────────────────────────────────────── */
        .domain-card {
            background: white;
            border: 1.5px solid #ebebf0;
            border-radius: 16px;
            padding: 1.8rem 1.2rem;
            text-align: center;
            height: 100%;
            transition: all 0.25s;
        }
        .domain-card:hover {
            border-color: var(--clr-primary);
            box-shadow: 0 10px 30px rgba(64,81,137,0.1);
            transform: translateY(-4px);
        }
        .domain-icon {
            width: 58px; height: 58px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.6rem;
        }

        /* ── Features ────────────────────────────────────────────── */
        .feature-card {
            background: white;
            border: 1px solid #ebebf0;
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            transition: all 0.25s;
        }
        .feature-card:hover {
            border-color: transparent;
            box-shadow: 0 12px 36px rgba(0,0,0,0.07);
            transform: translateY(-3px);
        }
        .feat-icon {
            width: 52px; height: 52px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
        }

        /* ── Testimonials ────────────────────────────────────────── */
        .testi-card {
            background: white;
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 6px 28px rgba(0,0,0,0.06);
            height: 100%;
            position: relative;
        }
        .testi-card::before {
            content: '\201C';
            position: absolute;
            top: 14px; right: 24px;
            font-size: 5rem;
            color: var(--clr-accent);
            opacity: 0.12;
            font-family: Georgia, serif;
            line-height: 1;
        }
        .testi-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        /* ── CTA banner ──────────────────────────────────────────── */
        .cta-banner {
            background: linear-gradient(135deg, var(--clr-primary) 0%, #0c8b7a 100%);
            border-radius: 24px;
        }

        /* ── Footer ─────────────────────────────────────────────── */
        .site-footer {
            background: var(--clr-dark);
            color: rgba(255,255,255,0.6);
        }
        .site-footer h6 { color: white; }
        .site-footer a  { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s; }
        .site-footer a:hover { color: white; }
        .footer-social {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s;
        }
        .footer-social:hover { background: var(--clr-accent); color: white !important; }
        .footer-hr { border-color: rgba(255,255,255,0.08); }

        /* ── Hero inner padding ─────────────────────────────────── */
        .hero-inner {
            padding-top: 130px !important;
            padding-bottom: 110px !important;
        }

        @media (max-width: 767px) {
            .section-wrap { padding: 60px 0; }
            .stat-divider  { display: none; }

            /* Hero */
            .hero-inner {
                padding-top: 90px !important;
                padding-bottom: 60px !important;
            }
            .hero-title { font-size: 1.65rem !important; }
            .hero-sub   { font-size: 0.92rem !important; }

            /* CTA buttons full-width stack */
            .hero-cta { flex-direction: column !important; }
            .hero-cta .btn { width: 100% !important; text-align: center !important; }

            /* Navbar collapse overlay */
            #navMenu.show {
                background: rgba(15,18,37,0.97);
                border-radius: 10px;
                padding: 1rem;
                margin-top: 0.5rem;
            }

            /* Stats bar */
            .stat-num { font-size: 1.8rem; }

            /* Reduce section gaps */
            .section-wrap { padding: 50px 0; }

            /* CTA banner buttons */
            .cta-banner .d-flex { flex-direction: column !important; align-items: stretch !important; }
            .cta-banner .d-flex .btn { width: 100% !important; margin: 0 !important; }
        }

        @media (max-width: 575px) {
            .how-tab-nav .nav-link { padding: 0.45rem 1.1rem; font-size: 0.85rem; }
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg position-absolute w-100 py-3" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#">
            <span class="brand-text text-white">
                <span class="brand-accent">Eng</span>Pis
            </span>
        </a>

        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="ri-menu-line fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="#how-it-works">چگونه کار می‌کند؟</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="#domains">حوزه‌های تخصصی</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="#features">مزایا</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-medium" href="{{ route('about') }}">درباره ما</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="btn btn-cta-primary btn-sm px-3">
                        <i class="ri-dashboard-line me-1"></i>داشبورد
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white fw-medium text-decoration-none px-2">ورود</a>
                    <a href="{{ route('register') }}" class="btn btn-nav-outline">ثبت‌نام رایگان</a>
                @endauth
            </div>
        </div>
    </div>
</nav>


{{-- ═══════════════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════════════ --}}
<section class="hero d-flex align-items-center text-white">
    <div class="container py-5 hero-inner" style="position: relative; z-index: 2;">
        <div class="row align-items-center g-5">

            {{-- Left: copy --}}
            <div class="col-lg-6">
                <div class="hero-tag mb-4">
                    <i class="ri-verified-badge-line"></i>
                    بزرگ‌ترین مارکت‌پلیس مهندسی ایران
                </div>

                <h1 class="hero-title mb-4">
                    پروژه مهندسی‌ات را به<br>
                    <span class="highlight">متخصص واقعی</span> بسپار
                </h1>

                <p class="hero-sub mb-5">
                    EngPis کارفرمایان پروژه‌های فنی را با بهترین متخصصان حوزه‌های
                    برق، مکانیک، کامپیوتر، عمران و سایر رشته‌های مهندسی متصل می‌کند.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5 hero-cta">
                    <a href="{{ route('register') }}" class="btn btn-cta-primary">
                        <i class="ri-add-circle-line me-2"></i>ثبت پروژه
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-cta-outline">
                        <i class="ri-briefcase-4-line me-2"></i>دنبال کار می‌گردم
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-4">
                    <div class="trust-badge">
                        <i class="ri-shield-check-fill text-success fs-5"></i>
                        پرداخت امن
                    </div>
                    <div class="trust-badge">
                        <i class="ri-user-star-fill text-warning fs-5"></i>
                        متخصصان تأیید شده
                    </div>
                    <div class="trust-badge">
                        <i class="ri-customer-service-2-fill text-info fs-5"></i>
                        پشتیبانی ۲۴ / ۷
                    </div>
                </div>
            </div>

            {{-- Right: floating illustration --}}
            <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                <div class="position-relative floating" style="width: 360px; height: 340px;">

                    {{-- Central icon --}}
                    <div class="position-absolute top-50 start-50 translate-middle text-center" style="opacity: 0.12;">
                        <i class="ri-tools-fill" style="font-size: 11rem;"></i>
                    </div>

                    {{-- Card: project done --}}
                    <div class="hero-card position-absolute d-flex align-items-center gap-2" style="top: 0; right: 0; width: 200px;">
                        <div class="rounded-2 p-2 flex-shrink-0" style="background: rgba(10,179,156,0.25);">
                            <i class="ri-check-double-line text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">پروژه تکمیل شد ✓</div>
                            <div style="font-size: 0.7rem; opacity: 0.7;">شبیه‌سازی ANSYS</div>
                        </div>
                    </div>

                    {{-- Card: rating --}}
                    <div class="hero-card position-absolute d-flex align-items-center gap-2" style="bottom: 20px; left: 0; width: 210px;">
                        <div class="rounded-2 p-2 flex-shrink-0" style="background: rgba(255,190,0,0.25);">
                            <i class="ri-star-fill text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">امتیاز ۴.۹ / ۵</div>
                            <div style="font-size: 0.7rem; opacity: 0.7;">میانگین رضایت کارفرمایان</div>
                        </div>
                    </div>

                    {{-- Card: new request --}}
                    <div class="hero-card position-absolute d-flex align-items-center gap-2" style="bottom: 110px; right: -20px; width: 195px;">
                        <div class="rounded-2 p-2 flex-shrink-0" style="background: rgba(255,255,255,0.15);">
                            <i class="ri-notification-3-fill fs-5" style="color: #a78bfa;"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">درخواست جدید</div>
                            <div style="font-size: 0.7rem; opacity: 0.7;">۳ متخصص پیشنهاد دادند</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="wave-bottom">
        <svg viewBox="0 0 1440 70" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path fill="white" d="M0,35 C480,75 960,0 1440,35 L1440,70 L0,70 Z"/>
        </svg>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     STATS BAR
═══════════════════════════════════════════════════════════════ --}}
<section class="stats-bar py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-0">
            <div class="text-center px-4 px-md-5">
                <div class="stat-num">+۵۰۰</div>
                <div class="stat-lbl">پروژه ثبت شده</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="text-center px-4 px-md-5">
                <div class="stat-num">+۱۲۰۰</div>
                <div class="stat-lbl">متخصص فعال</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="text-center px-4 px-md-5">
                <div class="stat-num">+۳۵۰</div>
                <div class="stat-lbl">کارفرمای راضی</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="text-center px-4 px-md-5">
                <div class="stat-num">۱۵+</div>
                <div class="stat-lbl">حوزه تخصصی</div>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════════════════ --}}
<section class="section-wrap section-bg" id="how-it-works">
    <div class="container">

        <div class="text-center mb-5">
            <span class="eyebrow">روش کار</span>
            <h2 class="sec-title">چگونه EngPis کار می‌کند؟</h2>
            <p class="text-muted mt-2">فرآیند ساده و شفاف — برای هر دو طرف</p>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <ul class="nav how-tab-nav gap-1" id="howTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tabEmployer" role="tab">
                        <i class="ri-building-4-line me-1"></i>کارفرمایان
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabSpecialist" role="tab">
                        <i class="ri-user-star-line me-1"></i>متخصصان
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content">

            {{-- Employer steps --}}
            <div class="tab-pane fade show active" id="tabEmployer" role="tabpanel">
                <div class="row g-4">
                    @php
                        $empSteps = [
                            ['icon'=>'ri-file-add-line','bg'=>'rgba(64,81,137,0.1)','ic'=>'#405189','num'=>'۱',
                             'title'=>'پروژه خود را ثبت کنید',
                             'body'=>'توضیحات فنی، حوزه تخصصی، بودجه و زمان‌بندی پروژه‌تان را وارد کنید.'],
                            ['icon'=>'ri-user-search-line','bg'=>'rgba(10,179,156,0.1)','ic'=>'#0ab39c','num'=>'۲',
                             'title'=>'متخصصان پیشنهادی را بررسی کنید',
                             'body'=>'سیستم هوشمند ما متخصصانی که با پروژه‌تان مطابقت دارند را معرفی می‌کند.'],
                            ['icon'=>'ri-handshake-line','bg'=>'rgba(255,190,0,0.1)','ic'=>'#d4a017','num'=>'۳',
                             'title'=>'همکاری را آغاز کنید',
                             'body'=>'بهترین متخصص را انتخاب کنید و پروژه‌تان را به سرانجام برسانید.'],
                        ];
                    @endphp
                    @foreach($empSteps as $step)
                    <div class="col-md-4">
                        <div class="step-card text-center">
                            <div class="step-num">{{ $step['num'] }}</div>
                            <div class="step-icon-wrap" style="background: {{ $step['bg'] }};">
                                <i class="{{ $step['icon'] }}" style="color: {{ $step['ic'] }};"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                            <p class="text-muted mb-0 small">{{ $step['body'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Specialist steps --}}
            <div class="tab-pane fade" id="tabSpecialist" role="tabpanel">
                <div class="row g-4">
                    @php
                        $spSteps = [
                            ['icon'=>'ri-profile-line','bg'=>'rgba(64,81,137,0.1)','ic'=>'#405189','num'=>'۱',
                             'title'=>'پروفایل تخصصی بسازید',
                             'body'=>'مهارت‌ها، حوزه‌های تخصصی و سطح توانایی خود را وارد کنید تا پروژه‌های متناسب پیدا شوند.'],
                            ['icon'=>'ri-search-eye-line','bg'=>'rgba(10,179,156,0.1)','ic'=>'#0ab39c','num'=>'۲',
                             'title'=>'پروژه‌های مناسب بیابید',
                             'body'=>'الگوریتم ما پروژه‌هایی که با مهارت‌هایتان مطابقت دارند را به‌صورت خودکار نمایش می‌دهد.'],
                            ['icon'=>'ri-send-plane-2-line','bg'=>'rgba(255,190,0,0.1)','ic'=>'#d4a017','num'=>'۳',
                             'title'=>'درخواست همکاری بدهید',
                             'body'=>'روی پروژه مورد نظر درخواست ارسال کنید و منتظر تأیید کارفرما باشید.'],
                        ];
                    @endphp
                    @foreach($spSteps as $step)
                    <div class="col-md-4">
                        <div class="step-card text-center">
                            <div class="step-num">{{ $step['num'] }}</div>
                            <div class="step-icon-wrap" style="background: {{ $step['bg'] }};">
                                <i class="{{ $step['icon'] }}" style="color: {{ $step['ic'] }};"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $step['title'] }}</h5>
                            <p class="text-muted mb-0 small">{{ $step['body'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     ENGINEERING DOMAINS
═══════════════════════════════════════════════════════════════ --}}
<section class="section-wrap" id="domains">
    <div class="container">

        <div class="text-center mb-5">
            <span class="eyebrow">حوزه‌های تخصصی</span>
            <h2 class="sec-title">در کدام رشته مهندسی فعالیت دارید؟</h2>
            <p class="text-muted mt-2">EngPis طیف گسترده‌ای از رشته‌های مهندسی را پوشش می‌دهد</p>
        </div>

        @php
            $domains = [
                ['icon'=>'ri-flashlight-line',       'bg'=>'rgba(255,190,0,0.1)',   'ic'=>'#d4a017', 'name'=>'مهندسی برق',      'sub'=>'مدار، قدرت، الکترونیک'],
                ['icon'=>'ri-settings-4-line',       'bg'=>'rgba(64,81,137,0.1)',   'ic'=>'#405189', 'name'=>'مهندسی مکانیک',   'sub'=>'طراحی، ساخت، دینامیک'],
                ['icon'=>'ri-building-2-line',       'bg'=>'rgba(10,179,156,0.1)',  'ic'=>'#0ab39c', 'name'=>'مهندسی عمران',    'sub'=>'سازه، ژئوتکنیک، راه'],
                ['icon'=>'ri-code-s-slash-line',     'bg'=>'rgba(67,160,71,0.1)',   'ic'=>'#43a047', 'name'=>'مهندسی کامپیوتر', 'sub'=>'نرم‌افزار، هوش مصنوعی'],
                ['icon'=>'ri-flask-line',            'bg'=>'rgba(239,83,80,0.1)',   'ic'=>'#ef5350', 'name'=>'مهندسی شیمی',     'sub'=>'فرآیند، پلیمر، پتروشیمی'],
                ['icon'=>'ri-bar-chart-grouped-line','bg'=>'rgba(156,39,176,0.1)',  'ic'=>'#9c27b0', 'name'=>'مهندسی صنایع',    'sub'=>'بهینه‌سازی، لجستیک'],
                ['icon'=>'ri-flight-takeoff-line',   'bg'=>'rgba(3,169,244,0.1)',   'ic'=>'#03a9f4', 'name'=>'مهندسی هوافضا',  'sub'=>'آیرودینامیک، پیشرانش'],
                ['icon'=>'ri-microscope-line',       'bg'=>'rgba(255,152,0,0.1)',   'ic'=>'#ff9800', 'name'=>'سایر رشته‌ها',    'sub'=>'معدن، متالورژی، محیط زیست'],
            ];
        @endphp

        <div class="row g-3">
            @foreach($domains as $d)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="domain-card">
                    <div class="domain-icon" style="background: {{ $d['bg'] }};">
                        <i class="{{ $d['icon'] }}" style="color: {{ $d['ic'] }};"></i>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $d['name'] }}</h6>
                    <small class="text-muted">{{ $d['sub'] }}</small>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     FEATURES / WHY ENGPIS
═══════════════════════════════════════════════════════════════ --}}
<section class="section-wrap section-bg" id="features">
    <div class="container">

        <div class="text-center mb-5">
            <span class="eyebrow">چرا EngPis؟</span>
            <h2 class="sec-title">مزایایی که EngPis را متمایز می‌کند</h2>
        </div>

        @php
            $features = [
                ['icon'=>'ri-robot-line',           'bg'=>'rgba(64,81,137,0.1)',  'ic'=>'#405189',
                 'title'=>'تطابق هوشمند',
                 'body'=>'الگوریتم ما پروژه‌ها را بر اساس مهارت، سطح تجربه و حوزه تخصصی با دقت بالا تطبیق می‌دهد.'],
                ['icon'=>'ri-shield-keyhole-line',  'bg'=>'rgba(10,179,156,0.1)', 'ic'=>'#0ab39c',
                 'title'=>'پرداخت امن',
                 'body'=>'وجه پروژه تا تأیید نهایی تحویل نزد EngPis نگه‌داری می‌شود. هیچ ریسکی برای هیچ طرفی وجود ندارد.'],
                ['icon'=>'ri-user-follow-line',     'bg'=>'rgba(255,190,0,0.1)',  'ic'=>'#d4a017',
                 'title'=>'متخصصان تأیید شده',
                 'body'=>'هویت، مدارک تحصیلی و سابقه کاری تمامی متخصصان قبل از فعالیت توسط تیم EngPis احراز می‌شود.'],
                ['icon'=>'ri-line-chart-line',      'bg'=>'rgba(67,160,71,0.1)',  'ic'=>'#43a047',
                 'title'=>'رشد مستمر',
                 'body'=>'با هر پروژه موفق، پروفایل و اعتبار شما رشد می‌کند و دسترسی به فرصت‌های بهتر افزایش می‌یابد.'],
                ['icon'=>'ri-customer-service-2-line','bg'=>'rgba(239,83,80,0.1)','ic'=>'#ef5350',
                 'title'=>'پشتیبانی تخصصی',
                 'body'=>'تیم پشتیبانی EngPis از آغاز تا پایان پروژه همراه شماست و در حل اختلافات نقش میانجی دارد.'],
                ['icon'=>'ri-time-line',            'bg'=>'rgba(156,39,176,0.1)', 'ic'=>'#9c27b0',
                 'title'=>'سرعت و سادگی',
                 'body'=>'ثبت پروژه تنها چند دقیقه طول می‌کشد. بدون بروکراسی، بدون پیچیدگی.'],
            ];
        @endphp

        <div class="row g-4">
            @foreach($features as $f)
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feat-icon" style="background: {{ $f['bg'] }};">
                        <i class="{{ $f['icon'] }}" style="color: {{ $f['ic'] }};"></i>
                    </div>
                    <h6 class="fw-bold mb-2">{{ $f['title'] }}</h6>
                    <p class="text-muted small mb-0">{{ $f['body'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     TESTIMONIALS
═══════════════════════════════════════════════════════════════ --}}
<section class="section-wrap">
    <div class="container">

        <div class="text-center mb-5">
            <span class="eyebrow">نظرات کاربران</span>
            <h2 class="sec-title">آنچه کاربران درباره EngPis می‌گویند</h2>
        </div>

        @php
            $testimonials = [
                ['stars'=>5, 'clr'=>'#405189',
                 'text'=>'«از طریق EngPis توانستم یک متخصص MATLAB عالی برای شبیه‌سازی سیستم کنترل پیدا کنم. فرآیند ساده بود و نتیجه فوق‌العاده.»',
                 'name'=>'علی محمدی', 'role'=>'کارفرما | مهندسی مکانیک', 'initial'=>'ع'],
                ['stars'=>5, 'clr'=>'#0ab39c',
                 'text'=>'«به عنوان متخصص ANSYS چندین پروژه موفق از طریق EngPis انجام داده‌ام. سیستم matching خیلی دقیق کار می‌کند و پروژه‌های مناسب پیشنهاد می‌دهد.»',
                 'name'=>'سارا احمدی', 'role'=>'متخصص | مهندسی عمران', 'initial'=>'س'],
                ['stars'=>5, 'clr'=>'#d4a017',
                 'text'=>'«پروژه پایان‌نامه‌ام را از طریق EngPis با یک متخصص Python تکمیل کردم. سریع، حرفه‌ای و قیمت منصفانه. قطعاً دوباره استفاده می‌کنم.»',
                 'name'=>'رضا کریمی', 'role'=>'کارفرما | دانشجوی دکترا', 'initial'=>'ر'],
            ];
        @endphp

        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="mb-3">
                        @for($i = 0; $i < $t['stars']; $i++)
                            <i class="ri-star-fill text-warning"></i>
                        @endfor
                    </div>
                    <p class="text-muted mb-4" style="line-height: 1.8;">{{ $t['text'] }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="testi-avatar" style="background: {{ $t['clr'] }};">{{ $t['initial'] }}</div>
                        <div>
                            <div class="fw-bold small">{{ $t['name'] }}</div>
                            <div class="text-muted" style="font-size: 0.78rem;">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     CTA BANNER
═══════════════════════════════════════════════════════════════ --}}
<section class="section-wrap" style="padding: 0 0 90px;">
    <div class="container">
        <div class="cta-banner text-white text-center p-5">
            <h2 class="sec-title text-white mb-3">آماده شروع هستید؟</h2>
            <p class="mb-5 opacity-75 fs-5">همین حالا رایگان ثبت‌نام کنید و اولین قدم را بردارید</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold" style="color: var(--clr-primary);">
                    <i class="ri-add-circle-line me-2"></i>ثبت پروژه مهندسی
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold">
                    <i class="ri-briefcase-4-line me-2"></i>جستجوی کار مهندسی
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════════ --}}
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
                    <li class="mb-2"><a href="#">تماس با ما</a></li>
                    <li class="mb-2"><a href="#">وبلاگ</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <h6 class="fw-bold mb-3">کارفرمایان</h6>
                <ul class="list-unstyled" style="font-size: 0.88rem;">
                    <li class="mb-2"><a href="{{ route('register') }}">ثبت پروژه</a></li>
                    <li class="mb-2"><a href="#">نحوه کار</a></li>
                    <li class="mb-2"><a href="#">تعرفه‌ها</a></li>
                    <li class="mb-2"><a href="#">سوالات متداول</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <h6 class="fw-bold mb-3">متخصصان</h6>
                <ul class="list-unstyled" style="font-size: 0.88rem;">
                    <li class="mb-2"><a href="{{ route('register') }}">ثبت‌نام</a></li>
                    <li class="mb-2"><a href="#">یافتن پروژه</a></li>
                    <li class="mb-2"><a href="#">راهنمای شروع</a></li>
                    <li class="mb-2"><a href="#">سوالات متداول</a></li>
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

<script>
(function () {
    // Sticky navbar on scroll
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 60) {
            nav.classList.add('pinned');
        } else {
            nav.classList.remove('pinned');
        }
    }, { passive: true });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offset = 80;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });
}());
</script>

</body>
</html>
