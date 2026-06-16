<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خط مشی و قوانین استفاده — EngPis</title>

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
        .brand-text   { font-size: 1.6rem; font-weight: 900; color: white; text-decoration: none; }
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
        .hero-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(10,179,156,0.18); color: #4eded0;
            border: 1px solid rgba(10,179,156,0.3); border-radius: 50px;
            padding: 0.35rem 1rem; font-size: 0.82rem; font-weight: 600;
        }

        /* ── TOC sidebar ── */
        .toc-card {
            background: white; border: 1px solid #e8edf5;
            border-radius: 16px; padding: 1.4rem;
            position: sticky; top: 80px;
        }
        .toc-card h6 { font-weight: 700; color: var(--clr-dark); margin-bottom: 0.9rem; }
        .toc-link {
            display: flex; align-items: center; gap: 8px;
            color: var(--clr-muted); text-decoration: none; font-size: 0.83rem;
            padding: 0.4rem 0; border-bottom: 1px solid #f0f4fa;
            transition: color 0.2s;
        }
        .toc-link:last-child { border-bottom: none; }
        .toc-link:hover { color: var(--clr-primary); }
        .toc-link i { font-size: 0.9rem; flex-shrink: 0; }

        /* ── Sections ── */
        .terms-section {
            background: white; border: 1px solid #ebebf0;
            border-radius: 18px; padding: 2rem 2.2rem;
            margin-bottom: 1.5rem;
            scroll-margin-top: 90px;
        }
        .terms-section:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.05); }
        .section-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 1rem; flex-shrink: 0;
        }
        .section-title {
            font-size: 1.1rem; font-weight: 800; color: var(--clr-dark);
            margin: 0 0 0.3rem;
        }
        .section-num {
            font-size: 0.75rem; font-weight: 700; color: var(--clr-muted);
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .terms-list {
            list-style: none; padding: 0; margin: 1rem 0 0;
        }
        .terms-list li {
            padding: 0.55rem 0; border-bottom: 1px solid #f4f6fa;
            font-size: 0.91rem; color: #444; line-height: 1.75;
            display: flex; gap: 10px; align-items: flex-start;
        }
        .terms-list li:last-child { border-bottom: none; }
        .terms-list li::before {
            content: ''; display: block; width: 6px; height: 6px;
            border-radius: 50%; background: var(--clr-accent);
            margin-top: 8px; flex-shrink: 0;
        }
        .last-updated {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(64,81,137,0.07); color: var(--clr-primary);
            border-radius: 50px; padding: 0.3rem 0.9rem; font-size: 0.78rem; font-weight: 600;
        }

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

        @media (max-width: 991px) {
            .toc-card { position: static; margin-bottom: 1.5rem; }
        }
        @media (max-width: 767px) {
            .page-hero { padding: 70px 0 55px; }
            .terms-section { padding: 1.4rem 1.2rem; }
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
                    <a class="nav-link text-white fw-medium" href="{{ route('about') }}">درباره ما</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('terms') }}"
                       style="color: var(--clr-accent) !important;">قوانین</a>
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
            <i class="ri-file-text-line"></i> خط مشی و قوانین
        </div>
        <h1 style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; margin: 0.8rem 0 1rem;">
            قوانین استفاده از <span style="color: var(--clr-accent);">EngPis</span>
        </h1>
        <p style="font-size: 1rem; opacity: 0.82; max-width: 520px; margin: 0 auto 1.5rem; line-height: 1.85;">
            لطفاً پیش از استفاده از خدمات EngPis، این قوانین را با دقت مطالعه کنید
        </p>
        <span class="last-updated">
            <i class="ri-time-line"></i> آخرین به‌روزرسانی: خرداد ۱۴۰۴
        </span>
    </div>
</section>

{{-- ═══════════ CONTENT ═══════════ --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- TOC Sidebar --}}
        <div class="col-lg-3">
            <div class="toc-card">
                <h6><i class="ri-list-check me-1" style="color: var(--clr-accent);"></i>فهرست مطالب</h6>
                <a href="#general" class="toc-link"><i class="ri-checkbox-circle-line" style="color: var(--clr-primary);"></i>قوانین کلی استفاده</a>
                <a href="#privacy" class="toc-link"><i class="ri-lock-line" style="color: var(--clr-accent);"></i>حریم خصوصی کاربران</a>
                <a href="#project-rules" class="toc-link"><i class="ri-briefcase-line" style="color: #6c63ff;"></i>قوانین ثبت پروژه</a>
                <a href="#services" class="toc-link"><i class="ri-service-line" style="color: #ff9800;"></i>قوانین ارائه خدمات</a>
                <a href="#employer" class="toc-link"><i class="ri-building-line" style="color: #e91e63;"></i>مسئولیت‌های کارفرما</a>
                <a href="#specialist" class="toc-link"><i class="ri-user-star-line" style="color: #00bcd4;"></i>مسئولیت‌های متخصص</a>
                <a href="#dispute" class="toc-link"><i class="ri-scales-line" style="color: #795548;"></i>حل اختلاف</a>
                <a href="#changes" class="toc-link"><i class="ri-refresh-line" style="color: var(--clr-muted);"></i>تغییرات در قوانین</a>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">

            {{-- 1. General --}}
            <div class="terms-section" id="general">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(64,81,137,0.09);">
                        <i class="ri-checkbox-circle-line" style="color: var(--clr-primary);"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش اول</div>
                        <h3 class="section-title">قوانین کلی استفاده</h3>
                        <p class="text-muted small mt-1 mb-0">شرایط پایه‌ای برای استفاده از پلتفرم EngPis</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>استفاده از EngPis به معنای پذیرش کامل تمامی قوانین و مقررات این پلتفرم است.</li>
                    <li>حداقل سن مجاز برای ثبت‌نام و استفاده از خدمات EngPis ۱۸ سال تمام است.</li>
                    <li>هر کاربر مجاز به داشتن تنها یک حساب کاربری فعال است و ایجاد چندین حساب ممنوع است.</li>
                    <li>اطلاعات ارائه‌شده در زمان ثبت‌نام باید صحیح، کامل و به‌روز باشند.</li>
                    <li>کاربران مسئول حفظ محرمانگی رمز عبور و اطلاعات ورود به حساب کاربری خود هستند.</li>
                    <li>EngPis این حق را دارد که در صورت نقض قوانین، حساب کاربری را تعلیق یا حذف کند.</li>
                    <li>هرگونه سوءاستفاده از پلتفرم، ارسال محتوای نامناسب یا رفتار مخالف با قوانین ممنوع است.</li>
                </ul>
            </div>

            {{-- 2. Privacy --}}
            <div class="terms-section" id="privacy">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(10,179,156,0.1);">
                        <i class="ri-lock-line" style="color: var(--clr-accent);"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش دوم</div>
                        <h3 class="section-title">حریم خصوصی کاربران</h3>
                        <p class="text-muted small mt-1 mb-0">نحوه جمع‌آوری، نگهداری و استفاده از اطلاعات کاربران</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>EngPis اطلاعات شخصی کاربران را تنها برای بهبود تجربه کاربری و اجرای خدمات پلتفرم جمع‌آوری می‌کند.</li>
                    <li>اطلاعات شخصی کاربران بدون رضایت صریح آن‌ها به اشخاص ثالث فروخته یا منتقل نخواهد شد.</li>
                    <li>EngPis از پروتکل‌های امنیتی استاندارد برای حفاظت از داده‌های کاربران استفاده می‌کند.</li>
                    <li>اطلاعات تماس کاربران (ایمیل، شماره موبایل) برای ارتباطات ضروری پلتفرم استفاده می‌شود.</li>
                    <li>کاربران می‌توانند در هر زمان درخواست حذف حساب و اطلاعات خود را از طریق پشتیبانی ارسال کنند.</li>
                    <li>اطلاعات مربوط به پروژه‌ها و مهارت‌ها در پلتفرم قابل مشاهده توسط طرفین مرتبط خواهند بود.</li>
                    <li>EngPis ممکن است از کوکی‌ها برای بهبود تجربه کاربری استفاده کند.</li>
                </ul>
            </div>

            {{-- 3. Project Rules --}}
            <div class="terms-section" id="project-rules">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(108,99,255,0.1);">
                        <i class="ri-briefcase-line" style="color: #6c63ff;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش سوم</div>
                        <h3 class="section-title">قوانین ثبت پروژه</h3>
                        <p class="text-muted small mt-1 mb-0">الزامات و محدودیت‌های ثبت پروژه‌های مهندسی در پلتفرم</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>پروژه‌های ثبت‌شده باید در حوزه‌های مهندسی و فنی باشند و ارتباط مشخصی با رشته‌های مهندسی داشته باشند.</li>
                    <li>عنوان و توضیحات پروژه باید دقیق، صادقانه و کامل باشند تا متخصصان اطلاعات کافی داشته باشند.</li>
                    <li>ثبت پروژه‌های غیراخلاقی، غیرقانونی یا مخالف با ارزش‌های اجتماعی ممنوع است.</li>
                    <li>کارفرما موظف است پروژه را پس از اتمام یا لغو، وضعیت آن را در پلتفرم به‌روز کند.</li>
                    <li>درج اطلاعات تماس مستقیم (تلفن، ایمیل) در متن پروژه تا قبل از تأیید درخواست ممنوع است.</li>
                    <li>هر پروژه باید با حوزه تخصصی و ابزارهای مورد نیاز دقیق تعریف شود تا تطبیق بهتری صورت گیرد.</li>
                </ul>
            </div>

            {{-- 4. Services --}}
            <div class="terms-section" id="services">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(255,152,0,0.1);">
                        <i class="ri-service-line" style="color: #ff9800;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش چهارم</div>
                        <h3 class="section-title">قوانین ارائه خدمات</h3>
                        <p class="text-muted small mt-1 mb-0">شرایط دسترسی به خدمات و سیاست تعرفه‌گذاری</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li><strong>در حال حاضر تمامی خدمات EngPis به صورت کاملاً رایگان ارائه می‌شوند.</strong> استفاده از پلتفرم برای ثبت پروژه، جستجوی متخصص و ارسال درخواست هیچ هزینه‌ای ندارد.</li>
                    <li>EngPis این حق را محفوظ می‌دارد که در آینده برای برخی خدمات پیشرفته تعرفه تعیین کند؛ در این صورت با اطلاع‌رسانی قبلی به کاربران اعمال خواهد شد.</li>
                    <li>پلتفرم EngPis در مراحل اولیه MVP قرار دارد و ممکن است برخی قابلیت‌ها در حال توسعه باشند.</li>
                    <li>EngPis خدمات پرداخت آنلاین یا ضمانت تراکنش مالی بین کارفرما و متخصص ارائه نمی‌دهد — توافق مالی مستقیماً بین دو طرف انجام می‌شود.</li>
                    <li>EngPis مسئولیتی در قبال کیفیت یا نتیجه همکاری بین کارفرما و متخصص ندارد و صرفاً بستر اتصال را فراهم می‌کند.</li>
                </ul>
            </div>

            {{-- 5. Employer --}}
            <div class="terms-section" id="employer">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(233,30,99,0.1);">
                        <i class="ri-building-line" style="color: #e91e63;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش پنجم</div>
                        <h3 class="section-title">مسئولیت‌های کارفرما</h3>
                        <p class="text-muted small mt-1 mb-0">تعهدات و وظایف کارفرمایان در قبال پلتفرم و متخصصان</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>کارفرما مسئول صحت و دقت اطلاعات پروژه ثبت‌شده است و تغییرات اساسی در پروژه باید به اطلاع متخصصان رسد.</li>
                    <li>کارفرما موظف است با متخصصانی که درخواست همکاری ارسال کرده‌اند با احترام و حسن نیت رفتار کند.</li>
                    <li>کارفرما نباید از اطلاعات متخصصان برای اهداف خارج از چارچوب همکاری حرفه‌ای استفاده کند.</li>
                    <li>پذیرش یا رد درخواست متخصصان باید در زمان معقول انجام شود تا از بلاتکلیفی جلوگیری گردد.</li>
                    <li>کارفرما مسئول رعایت تعهدات مالی توافق‌شده با متخصص خارج از پلتفرم است.</li>
                    <li>کارفرما نباید اطلاعات دریافت‌شده از پلتفرم را برای دور زدن سیستم EngPis به کار برد.</li>
                </ul>
            </div>

            {{-- 6. Specialist --}}
            <div class="terms-section" id="specialist">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(0,188,212,0.1);">
                        <i class="ri-user-star-line" style="color: #00bcd4;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش ششم</div>
                        <h3 class="section-title">مسئولیت‌های متخصص</h3>
                        <p class="text-muted small mt-1 mb-0">تعهدات و وظایف متخصصان در قبال پلتفرم و کارفرمایان</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>متخصص مسئول صحت اطلاعات مهارت‌ها، تخصص‌ها و سوابق حرفه‌ای ثبت‌شده در پروفایل خود است.</li>
                    <li>درج مهارت‌های دروغین یا اغراق در توانایی‌ها به منظور دریافت پروژه ممنوع است.</li>
                    <li>متخصص موظف است در صورت عدم توانایی یا علاقه برای اجرای پروژه، در اسرع وقت کارفرما را مطلع سازد.</li>
                    <li>متخصص باید اطلاعات محرمانه پروژه و کارفرما را با رعایت اصول حرفه‌ای حفظ کند.</li>
                    <li>ارسال درخواست همکاری برای پروژه‌هایی که متخصص توانایی اجرای آن‌ها را ندارد ممنوع است.</li>
                    <li>متخصص نباید اطلاعات کارفرمایان را برای اهداف تبلیغاتی یا خارج از چارچوب همکاری استفاده کند.</li>
                </ul>
            </div>

            {{-- 7. Dispute --}}
            <div class="terms-section" id="dispute">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(121,85,72,0.1);">
                        <i class="ri-scales-line" style="color: #795548;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش هفتم</div>
                        <h3 class="section-title">حل اختلاف</h3>
                        <p class="text-muted small mt-1 mb-0">فرایند رسیدگی به اختلافات بین کاربران</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>در صورت بروز اختلاف بین کارفرما و متخصص، طرفین باید ابتدا از طریق گفت‌وگوی مستقیم به حل مسئله بپردازند.</li>
                    <li>در صورتی که اختلاف از طریق مذاکره مستقیم حل نشود، می‌توانید موضوع را از طریق سیستم تیکت EngPis به تیم پشتیبانی گزارش دهید.</li>
                    <li>EngPis در اختلافات مالی بین کاربران مداخله نمی‌کند و مسئولیت حل این دسته از اختلافات بر عهده طرفین است.</li>
                    <li>در صورت گزارش تخلف آشکار از قوانین پلتفرم، تیم EngPis ممکن است حساب کاربری را تعلیق کند.</li>
                    <li>تمامی اختلافات حقوقی تابع قوانین جمهوری اسلامی ایران است و در صلاحیت محاکم قضایی تهران خواهد بود.</li>
                </ul>
            </div>

            {{-- 8. Changes --}}
            <div class="terms-section" id="changes">
                <div class="d-flex align-items-start gap-3">
                    <div class="section-icon" style="background: rgba(135,138,153,0.1);">
                        <i class="ri-refresh-line" style="color: var(--clr-muted);"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="section-num">بخش هشتم</div>
                        <h3 class="section-title">تغییرات در قوانین</h3>
                        <p class="text-muted small mt-1 mb-0">سیاست به‌روزرسانی این خط مشی</p>
                    </div>
                </div>
                <ul class="terms-list mt-3">
                    <li>EngPis این حق را دارد که قوانین و مقررات را در هر زمان به‌روز کند.</li>
                    <li>تغییرات اساسی در قوانین از طریق اطلاع‌رسانی در پلتفرم یا ایمیل به کاربران ثبت‌نام‌شده اعلام می‌شود.</li>
                    <li>ادامه استفاده از پلتفرم پس از اعلام تغییرات به معنای پذیرش قوانین جدید است.</li>
                    <li>تاریخ آخرین به‌روزرسانی این قوانین همواره در بالای همین صفحه درج خواهد شد.</li>
                    <li>در صورت مخالفت با تغییرات، کاربر می‌تواند با تماس با پشتیبانی درخواست حذف حساب کاربری دهد.</li>
                </ul>
            </div>

            {{-- Contact note --}}
            <div class="p-4 rounded-3 text-center" style="background: rgba(64,81,137,0.05); border: 1px dashed #c5cfe8;">
                <i class="ri-question-line fs-3 mb-2 d-block" style="color: var(--clr-primary);"></i>
                <h6 class="fw-bold mb-1">سؤالی دارید؟</h6>
                <p class="text-muted small mb-2">اگر در مورد قوانین یا نحوه کارکرد پلتفرم سؤالی دارید، از طریق سیستم تیکت با ما در ارتباط باشید.</p>
                @auth
                    <a href="{{ route('user.tickets.create') }}" class="btn btn-sm" style="background: var(--clr-primary); color: white; border-radius: 50px; padding: 0.4rem 1.2rem;">
                        ارسال تیکت
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm" style="background: var(--clr-primary); color: white; border-radius: 50px; padding: 0.4rem 1.2rem;">
                        ورود و ارسال تیکت
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>

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
