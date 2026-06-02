@extends('layouts.ep-website')

@section('title', 'EngPis — بزرگ‌ترین مارکت‌پلیس مهندسی ایران')

@section('content')

{{-- ── Hero ────────────────────────────────────────────── --}}
<section class="hero" id="top">
    <div class="container hero-grid">
        <div>
            <span class="hero-tag">
                <i class="ri-verified-badge-line"></i>
                بزرگ‌ترین مارکت‌پلیس مهندسی ایران
            </span>
            <h1 class="hero-title">
                پروژه مهندسی‌ات را به<br>
                <span class="hl">متخصص واقعی</span> بسپار
            </h1>
            <p class="hero-sub">
                EngPis کارفرمایان پروژه‌های فنی را با بهترین متخصصان حوزه‌های برق، مکانیک، کامپیوتر، عمران و سایر رشته‌های مهندسی متصل می‌کند.
            </p>
            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn btn-cta btn-lg">
                    <i class="ri-add-circle-line"></i>ثبت پروژه
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline btn-lg">
                    <i class="ri-briefcase-4-line"></i>دنبال کار می‌گردم
                </a>
            </div>
            <div class="trust-row">
                <span class="trust">
                    <i class="ri-shield-check-fill" style="color:#0ab39c;font-size:1.1rem;"></i>
                    پرداخت امن
                </span>
                <span class="trust">
                    <i class="ri-user-star-fill" style="color:#ffbe00;font-size:1.1rem;"></i>
                    متخصصان تأیید شده
                </span>
                <span class="trust">
                    <i class="ri-customer-service-2-fill" style="color:#03a9f4;font-size:1.1rem;"></i>
                    پشتیبانی ۲۴ / ۷
                </span>
            </div>
        </div>

        <div class="hero-art floating">
            <i class="ri-tools-fill deco"></i>
            <div class="glass" style="top:0; inset-inline-end:0; width:200px;">
                <div class="ic" style="background:rgba(10,179,156,.25); color:#5ddfb0;">
                    <i class="ri-check-double-line"></i>
                </div>
                <div>
                    <div class="gt">پروژه تکمیل شد ✓</div>
                    <div class="gs">شبیه‌سازی ANSYS</div>
                </div>
            </div>
            <div class="glass" style="bottom:20px; inset-inline-start:0; width:210px;">
                <div class="ic" style="background:rgba(255,190,0,.25); color:#ffd43b;">
                    <i class="ri-star-fill"></i>
                </div>
                <div>
                    <div class="gt">امتیاز ۴.۹ / ۵</div>
                    <div class="gs">میانگین رضایت کارفرمایان</div>
                </div>
            </div>
            <div class="glass" style="bottom:120px; inset-inline-end:-20px; width:200px;">
                <div class="ic" style="background:rgba(255,255,255,.15); color:#a78bfa;">
                    <i class="ri-notification-3-fill"></i>
                </div>
                <div>
                    <div class="gt">درخواست جدید</div>
                    <div class="gs">۳ متخصص پیشنهاد دادند</div>
                </div>
            </div>
        </div>
    </div>

    <div class="wave">
        <svg viewBox="0 0 1440 70" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="width:100%;height:70px;">
            <path fill="#fff" d="M0,35 C480,75 960,0 1440,35 L1440,70 L0,70 Z"/>
        </svg>
    </div>
</section>

{{-- ── Stats bar ──────────────────────────────────────── --}}
<section class="stats">
    <div class="container stats-row">
        <div class="stat">
            <div class="stat-num">+۵۰۰</div>
            <div class="stat-lbl">پروژه ثبت شده</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat">
            <div class="stat-num">+۱۲۰۰</div>
            <div class="stat-lbl">متخصص فعال</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat">
            <div class="stat-num">+۳۵۰</div>
            <div class="stat-lbl">کارفرمای راضی</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat">
            <div class="stat-num">۱۵+</div>
            <div class="stat-lbl">حوزه تخصصی</div>
        </div>
    </div>
</section>

{{-- ── How it works ───────────────────────────────────── --}}
<section class="section wash" id="how-it-works">
    <div class="container">
        <div class="sec-head">
            <span class="ep-eyebrow">روش کار</span>
            <h2 class="sec-title">چگونه EngPis کار می‌کند؟</h2>
            <p class="sec-sub">فرآیند ساده و شفاف — برای هر دو طرف</p>
        </div>
        <div class="tabs">
            <div class="tab-nav">
                <button class="tab-btn active" data-tab="employer">
                    <i class="ri-building-4-line"></i> کارفرمایان
                </button>
                <button class="tab-btn" data-tab="specialist">
                    <i class="ri-user-star-line"></i> متخصصان
                </button>
            </div>
        </div>

        <div class="tab-panel grid-3 active" id="tab-employer">
            <div class="step">
                <div class="step-num">۱</div>
                <div class="step-ic" style="background:rgba(64,81,137,.1); color:#405189;">
                    <i class="ri-file-add-line"></i>
                </div>
                <h5>پروژه خود را ثبت کنید</h5>
                <p>توضیحات فنی، حوزه تخصصی، بودجه و زمان‌بندی پروژه‌تان را وارد کنید.</p>
            </div>
            <div class="step">
                <div class="step-num">۲</div>
                <div class="step-ic" style="background:rgba(10,179,156,.1); color:#0ab39c;">
                    <i class="ri-user-search-line"></i>
                </div>
                <h5>متخصصان پیشنهادی را بررسی کنید</h5>
                <p>سیستم هوشمند ما متخصصانی که با پروژه‌تان مطابقت دارند را معرفی می‌کند.</p>
            </div>
            <div class="step">
                <div class="step-num">۳</div>
                <div class="step-ic" style="background:rgba(255,190,0,.1); color:#d4a017;">
                    <i class="ri-handshake-line"></i>
                </div>
                <h5>همکاری را آغاز کنید</h5>
                <p>بهترین متخصص را انتخاب کنید و پروژه‌تان را به سرانجام برسانید.</p>
            </div>
        </div>

        <div class="tab-panel grid-3" id="tab-specialist">
            <div class="step">
                <div class="step-num">۱</div>
                <div class="step-ic" style="background:rgba(64,81,137,.1); color:#405189;">
                    <i class="ri-profile-line"></i>
                </div>
                <h5>پروفایل تخصصی بسازید</h5>
                <p>مهارت‌ها، حوزه‌های تخصصی و سطح توانایی خود را وارد کنید تا پروژه‌های متناسب پیدا شوند.</p>
            </div>
            <div class="step">
                <div class="step-num">۲</div>
                <div class="step-ic" style="background:rgba(10,179,156,.1); color:#0ab39c;">
                    <i class="ri-search-eye-line"></i>
                </div>
                <h5>پروژه‌های مناسب بیابید</h5>
                <p>الگوریتم ما پروژه‌هایی که با مهارت‌هایتان مطابقت دارند را به‌صورت خودکار نمایش می‌دهد.</p>
            </div>
            <div class="step">
                <div class="step-num">۳</div>
                <div class="step-ic" style="background:rgba(255,190,0,.1); color:#d4a017;">
                    <i class="ri-send-plane-2-line"></i>
                </div>
                <h5>درخواست همکاری بدهید</h5>
                <p>روی پروژه مورد نظر درخواست ارسال کنید و منتظر تأیید کارفرما باشید.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── Domains ────────────────────────────────────────── --}}
<section class="section" id="domains">
    <div class="container">
        <div class="sec-head">
            <span class="ep-eyebrow">حوزه‌های تخصصی</span>
            <h2 class="sec-title">در کدام رشته مهندسی فعالیت دارید؟</h2>
            <p class="sec-sub">EngPis طیف گسترده‌ای از رشته‌های مهندسی را پوشش می‌دهد</p>
        </div>
        <div class="grid-4">
            <div class="domain">
                <div class="domain-ic" style="background:rgba(255,190,0,.1); color:#d4a017;"><i class="ri-flashlight-line"></i></div>
                <h6>مهندسی برق</h6><small>مدار، قدرت، الکترونیک</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(64,81,137,.1); color:#405189;"><i class="ri-settings-4-line"></i></div>
                <h6>مهندسی مکانیک</h6><small>طراحی، ساخت، دینامیک</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(10,179,156,.1); color:#0ab39c;"><i class="ri-building-2-line"></i></div>
                <h6>مهندسی عمران</h6><small>سازه، ژئوتکنیک، راه</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(67,160,71,.1); color:#43a047;"><i class="ri-code-s-slash-line"></i></div>
                <h6>مهندسی کامپیوتر</h6><small>نرم‌افزار، هوش مصنوعی</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(239,83,80,.1); color:#ef5350;"><i class="ri-flask-line"></i></div>
                <h6>مهندسی شیمی</h6><small>فرآیند، پلیمر، پتروشیمی</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(156,39,176,.1); color:#9c27b0;"><i class="ri-bar-chart-grouped-line"></i></div>
                <h6>مهندسی صنایع</h6><small>بهینه‌سازی، لجستیک</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(3,169,244,.1); color:#03a9f4;"><i class="ri-flight-takeoff-line"></i></div>
                <h6>مهندسی هوافضا</h6><small>آیرودینامیک، پیشرانش</small>
            </div>
            <div class="domain">
                <div class="domain-ic" style="background:rgba(255,152,0,.1); color:#ff9800;"><i class="ri-microscope-line"></i></div>
                <h6>سایر رشته‌ها</h6><small>معدن، متالورژی، محیط زیست</small>
            </div>
        </div>
    </div>
</section>

{{-- ── Features ───────────────────────────────────────── --}}
<section class="section wash" id="features">
    <div class="container">
        <div class="sec-head">
            <span class="ep-eyebrow">چرا EngPis؟</span>
            <h2 class="sec-title">مزایایی که EngPis را متمایز می‌کند</h2>
        </div>
        <div class="grid-3">
            <div class="feature">
                <div class="feat-ic" style="background:rgba(64,81,137,.1); color:#405189;"><i class="ri-robot-line"></i></div>
                <h6>تطابق هوشمند</h6>
                <p>الگوریتم ما پروژه‌ها را بر اساس مهارت، سطح تجربه و حوزه تخصصی با دقت بالا تطبیق می‌دهد.</p>
            </div>
            <div class="feature">
                <div class="feat-ic" style="background:rgba(10,179,156,.1); color:#0ab39c;"><i class="ri-shield-keyhole-line"></i></div>
                <h6>پرداخت امن</h6>
                <p>وجه پروژه تا تأیید نهایی تحویل نزد EngPis نگه‌داری می‌شود. هیچ ریسکی برای هیچ طرفی وجود ندارد.</p>
            </div>
            <div class="feature">
                <div class="feat-ic" style="background:rgba(255,190,0,.1); color:#d4a017;"><i class="ri-user-follow-line"></i></div>
                <h6>متخصصان تأیید شده</h6>
                <p>هویت، مدارک تحصیلی و سابقه کاری تمامی متخصصان قبل از فعالیت احراز می‌شود.</p>
            </div>
            <div class="feature">
                <div class="feat-ic" style="background:rgba(67,160,71,.1); color:#43a047;"><i class="ri-line-chart-line"></i></div>
                <h6>رشد مستمر</h6>
                <p>با هر پروژه موفق، پروفایل و اعتبار شما رشد می‌کند و دسترسی به فرصت‌های بهتر افزایش می‌یابد.</p>
            </div>
            <div class="feature">
                <div class="feat-ic" style="background:rgba(239,83,80,.1); color:#ef5350;"><i class="ri-customer-service-2-line"></i></div>
                <h6>پشتیبانی تخصصی</h6>
                <p>تیم پشتیبانی EngPis از آغاز تا پایان پروژه همراه شماست و در حل اختلافات نقش میانجی دارد.</p>
            </div>
            <div class="feature">
                <div class="feat-ic" style="background:rgba(156,39,176,.1); color:#9c27b0;"><i class="ri-time-line"></i></div>
                <h6>سرعت و سادگی</h6>
                <p>ثبت پروژه تنها چند دقیقه طول می‌کشد. بدون بروکراسی، بدون پیچیدگی.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── Testimonials ───────────────────────────────────── --}}
<section class="section">
    <div class="container">
        <div class="sec-head">
            <span class="ep-eyebrow">نظرات کاربران</span>
            <h2 class="sec-title">آنچه کاربران درباره EngPis می‌گویند</h2>
        </div>
        <div class="grid-3">
            <div class="testi">
                <div class="stars">★★★★★</div>
                <p>«از طریق EngPis توانستم یک متخصص MATLAB عالی برای شبیه‌سازی سیستم کنترل پیدا کنم. فرآیند ساده بود و نتیجه فوق‌العاده.»</p>
                <div class="testi-person">
                    <div class="avatar" style="background:#405189;">ع</div>
                    <div>
                        <div class="n">علی محمدی</div>
                        <div class="r">کارفرما | مهندسی مکانیک</div>
                    </div>
                </div>
            </div>
            <div class="testi">
                <div class="stars">★★★★★</div>
                <p>«به عنوان متخصص ANSYS چندین پروژه موفق از طریق EngPis انجام داده‌ام. سیستم matching خیلی دقیق کار می‌کند و پروژه‌های مناسب پیشنهاد می‌دهد.»</p>
                <div class="testi-person">
                    <div class="avatar" style="background:#0ab39c;">س</div>
                    <div>
                        <div class="n">سارا احمدی</div>
                        <div class="r">متخصص | مهندسی عمران</div>
                    </div>
                </div>
            </div>
            <div class="testi">
                <div class="stars">★★★★★</div>
                <p>«پروژه پایان‌نامه‌ام را از طریق EngPis با یک متخصص Python تکمیل کردم. سریع، حرفه‌ای و قیمت منصفانه. قطعاً دوباره استفاده می‌کنم.»</p>
                <div class="testi-person">
                    <div class="avatar" style="background:#d4a017;">ر</div>
                    <div>
                        <div class="n">رضا کریمی</div>
                        <div class="r">کارفرما | دانشجوی دکترا</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA banner ─────────────────────────────────────── --}}
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="cta-banner">
            <h2>آماده شروع هستید؟</h2>
            <p>همین حالا رایگان ثبت‌نام کنید و اولین قدم را بردارید</p>
            <div class="cta-row">
                <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                    <i class="ri-add-circle-line"></i>ثبت پروژه مهندسی
                </a>
                <a href="{{ route('register') }}" class="btn btn-ghost-light btn-lg">
                    <i class="ri-briefcase-4-line"></i>جستجوی کار مهندسی
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    (function () {
        var buttons = document.querySelectorAll('.tab-btn');
        var panels  = document.querySelectorAll('.tab-panel');
        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.dataset.tab;
                buttons.forEach(function (b) { b.classList.remove('active'); });
                panels.forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('tab-' + target);
                if (panel) panel.classList.add('active');
            });
        });
    })();
</script>
@endsection
