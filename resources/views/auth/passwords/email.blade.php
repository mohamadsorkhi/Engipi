@extends('layouts.master-without-nav')

@section('title', 'فراموشی رمز عبور')

@section('body')
<body style="margin:0;padding:0;overflow-x:hidden;background:#fff;">
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after {
    font-family: 'Vazirmatn', sans-serif !important;
    box-sizing: border-box;
}
html, body {
    height: 100%;
    margin: 0; padding: 0;
    background: #fff !important;
    color: #1a2a4a !important;
}

.ep-wrap {
    display: flex;
    flex-direction: row;
    min-height: 100vh;
}

/* ── Form Panel ── */
.ep-form {
    width: 42%;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2.5rem 3.5rem;
    background: #ffffff;
    position: relative;
    z-index: 2;
    overflow-y: auto;
}
[dir="rtl"] .ep-form {
    border-left: 1px solid #f0f4fa;
    box-shadow: -12px 0 40px rgba(0,0,0,0.05);
}
[dir="ltr"] .ep-form {
    border-right: 1px solid #f0f4fa;
    box-shadow: 12px 0 40px rgba(0,0,0,0.05);
}

.ep-logo { margin-bottom: 2.2rem; }
.ep-logo a { text-decoration: none; }
.ep-logo-word {
    font-size: 1.65rem;
    font-weight: 900;
    color: #1a2a4a;
    letter-spacing: -0.5px;
    line-height: 1;
}
.ep-logo-word .acc { color: #00d4aa; }

.ep-heading {
    font-size: 1.55rem;
    font-weight: 800;
    color: #1a2a4a;
    margin: 0 0 0.3rem;
}
.ep-subhead {
    font-size: 0.88rem;
    color: #8090a4;
    margin: 0 0 1.8rem;
}

.ep-alert-ok {
    background: #f0fdf8;
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.83rem;
    margin-bottom: 1.2rem;
}
.ep-alert-err {
    background: #fff5f5;
    border: 1px solid #fca5a5;
    color: #991b1b;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.83rem;
    margin-bottom: 1.2rem;
}

.ep-field { margin-bottom: 1rem; }
.ep-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #374558;
    margin-bottom: 0.4rem;
}

.ep-input-box {
    position: relative;
    display: flex;
    align-items: center;
}
.ep-ico {
    position: absolute;
    font-size: 1rem;
    color: #b0bfcc;
    pointer-events: none;
    z-index: 2;
    top: 50%;
    transform: translateY(-50%);
}
[dir="rtl"] .ep-ico { right: 13px; }
[dir="ltr"] .ep-ico { left: 13px; }

.ep-inp {
    width: 100%;
    padding: 11px 42px 11px 42px;
    border: 1.5px solid #e5eaf3;
    border-radius: 10px;
    font-size: 0.875rem;
    color: #1a2a4a;
    background: #fafbfd;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    direction: ltr;
}
[dir="rtl"] .ep-inp { text-align: right; }
.ep-inp::placeholder { color: #b8c4d0; direction: ltr; text-align: left; }
.ep-inp:focus {
    border-color: #00d4aa;
    background: #fff;
    box-shadow: 0 0 0 3.5px rgba(0,212,170,0.13);
}
.ep-inp.is-invalid { border-color: #ef5350; }
.ep-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,83,80,0.12); }

.ep-btn-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 13px 20px;
    background: linear-gradient(120deg, #00d4aa 0%, #6c63ff 100%);
    color: #fff;
    font-size: 0.92rem;
    font-weight: 700;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.22s, box-shadow 0.22s, filter 0.22s;
    letter-spacing: 0.01em;
    margin-bottom: 1.4rem;
}
.ep-btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,212,170,0.32);
    filter: brightness(1.04);
}
.ep-btn-cta:active { transform: translateY(0); box-shadow: none; }

.ep-back {
    text-align: center;
    font-size: 0.83rem;
    color: #8090a4;
    margin: 0;
}
.ep-back a {
    color: #00a882;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s;
}
.ep-back a:hover { color: #00d4aa; text-decoration: underline; }

/* ── Showcase Panel ── */
.ep-showcase {
    flex: 1;
    background:
        linear-gradient(rgba(15,35,64,0.75), rgba(15,35,64,0.75)),
        url('/images/login-bg.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 3rem;
    position: relative;
    overflow: hidden;
}
.ep-showcase::before {
    content: '';
    position: absolute;
    top: -90px; right: -90px;
    width: 340px; height: 340px;
    border-radius: 50%;
    background: rgba(0,212,170,0.10);
    pointer-events: none;
}
.ep-showcase::after {
    content: '';
    position: absolute;
    bottom: -70px; left: -70px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(108,99,255,0.10);
    pointer-events: none;
}
.ep-dot-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(0,0,0,0.06) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}
.ep-showcase-inner {
    max-width: 510px;
    width: 100%;
    position: relative;
    z-index: 2;
}
.ep-sc-title {
    font-size: clamp(1.4rem, 2.4vw, 2rem);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.45;
    margin-bottom: 0.7rem;
}
.ep-sc-title .hl { color: #00d4aa; }
.ep-sc-sub {
    font-size: 0.88rem;
    color: rgba(255,255,255,0.75);
    line-height: 1.75;
    margin-bottom: 1.8rem;
    max-width: 400px;
}

.ep-browser {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 24px 64px rgba(0,40,80,0.14), 0 4px 16px rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 1.4rem;
    border: 1px solid rgba(0,0,0,0.04);
}
.ep-browser-bar {
    background: #f4f7fb;
    border-bottom: 1px solid #eaeef5;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ep-dots { display: flex; gap: 5px; }
.ep-dots span {
    width: 9px; height: 9px;
    border-radius: 50%;
    display: block;
}
.ep-url-bar {
    font-size: 0.68rem;
    color: #8898aa;
    background: #eaeef5;
    border-radius: 6px;
    padding: 3px 10px;
    direction: ltr;
    letter-spacing: 0.01em;
}
.ep-browser-body { padding: 14px; }

.ep-mini-stats { display: flex; gap: 8px; margin-bottom: 12px; }
.ep-ms {
    flex: 1;
    background: #f7f9fc;
    border-radius: 10px;
    padding: 10px;
    border-top: 3px solid var(--c);
}
.ep-ms-num { font-size: 0.95rem; font-weight: 800; color: var(--c); line-height: 1.1; }
.ep-ms-lbl { font-size: 0.6rem; color: #9aabb8; margin-top: 3px; }

.ep-chart {
    display: flex;
    align-items: flex-end;
    gap: 5px;
    height: 48px;
    background: #f7f9fc;
    border-radius: 8px;
    padding: 8px;
    margin-bottom: 12px;
}
.ep-bar { flex: 1; border-radius: 3px 3px 0 0; min-height: 6px; }

.ep-rows { display: flex; flex-direction: column; gap: 6px; }
.ep-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 10px;
    background: #f7f9fc;
    border-radius: 8px;
}
.ep-av {
    width: 26px; height: 26px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.68rem; font-weight: 800;
    flex-shrink: 0;
}
.ep-rtext { flex: 1; }
.ep-rl { height: 5px; background: #e2e8f0; border-radius: 3px; margin-bottom: 4px; }
.ep-rl:last-child { margin-bottom: 0; }
.ep-w60 { width: 60%; } .ep-w50 { width: 50%; } .ep-w55 { width: 55%; }
.ep-w40 { width: 40%; } .ep-w35 { width: 35%; } .ep-w30 { width: 30%; }
.ep-badge-sm {
    font-size: 0.6rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    flex-shrink: 0;
    white-space: nowrap;
}

.ep-float {
    position: absolute;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 32px rgba(0,40,80,0.12), 0 2px 8px rgba(0,0,0,0.05);
    padding: 11px 16px;
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 155px;
    z-index: 3;
}
.ep-float-1 { bottom: 60px; right: 16px; animation: floatUp 5s ease-in-out infinite; }
.ep-float-2 { top: 90px; left: 16px; animation: floatUp 5s ease-in-out infinite; animation-delay: 2.5s; }
@keyframes floatUp {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-9px); }
}
.ep-float-ic {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.ep-float-num { font-size: 0.98rem; font-weight: 800; color: #1a2a4a; line-height: 1.1; }
.ep-float-lbl { font-size: 0.65rem; color: #8898aa; margin-top: 1px; }

@media (max-width: 900px) {
    .ep-form { width: 100%; padding: 2rem 1.6rem; }
    .ep-showcase { display: none; }
}
@media (max-width: 767px) {
    .ep-form {
        background-image: linear-gradient(rgba(255,255,255,0.92), rgba(255,255,255,0.92)), url('/images/login-bg.jpg');
        background-size: cover;
        background-position: center;
    }
}
@media (max-width: 480px) {
    .ep-form { padding: 1.8rem 1.2rem; }
    .ep-heading { font-size: 1.3rem; }
}
</style>
@endsection

@section('content')
<div class="ep-wrap">

    {{-- ── Form Panel ── --}}
    <div class="ep-form">

        <div class="ep-logo">
            <a href="{{ route('root') }}">
                <div class="ep-logo-word"><span class="acc">Eng</span>Pis</div>
            </a>
        </div>

        <h2 class="ep-heading">فراموشی رمز عبور</h2>
        <p class="ep-subhead">ایمیل خود را وارد کنید تا لینک بازنشانی برایتان ارسال شود</p>

        @if(session('status'))
            <div class="ep-alert-ok">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="ep-alert-err">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="ep-field">
                <label class="ep-label" for="email">ایمیل</label>
                <div class="ep-input-box">
                    <i class="ri-mail-line ep-ico"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="ep-inp @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="email@example.com"
                        autocomplete="email"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="ep-btn-cta">
                <i class="ri-send-plane-line"></i>
                ارسال لینک بازنشانی
            </button>

        </form>

        <p class="ep-back">
            <a href="{{ route('login') }}">
                <i class="ri-arrow-right-line"></i>
                بازگشت به صفحه ورود
            </a>
        </p>

    </div>{{-- /ep-form --}}

    {{-- ── Showcase Panel ── --}}
    <div class="ep-showcase">
        <div class="ep-dot-grid"></div>

        <div class="ep-showcase-inner">

            <h2 class="ep-sc-title">
                سریع‌ترین راه برای یافتن<br>
                <span class="hl">متخصص مهندسی</span>
            </h2>
            <p class="ep-sc-sub">
                بیش از ۱۲۰۰ متخصص در حوزه‌های برق، مکانیک، کامپیوتر و عمران آماده همکاری با شما هستند.
            </p>

            <div class="ep-browser">
                <div class="ep-browser-bar">
                    <div class="ep-dots">
                        <span style="background:#ff6b6b;"></span>
                        <span style="background:#ffd43b;"></span>
                        <span style="background:#5ddfb0;"></span>
                    </div>
                    <div class="ep-url-bar">&#x1F512;&nbsp; engpis.ir/dashboard</div>
                </div>

                <div class="ep-browser-body">
                    <div class="ep-mini-stats">
                        <div class="ep-ms" style="--c:#00d4aa;">
                            <div class="ep-ms-num">+۵۰۰</div>
                            <div class="ep-ms-lbl">پروژه فعال</div>
                        </div>
                        <div class="ep-ms" style="--c:#6c63ff;">
                            <div class="ep-ms-num">+۱۲۰۰</div>
                            <div class="ep-ms-lbl">متخصص</div>
                        </div>
                        <div class="ep-ms" style="--c:#ff9800;">
                            <div class="ep-ms-num">۹۸٪</div>
                            <div class="ep-ms-lbl">رضایت</div>
                        </div>
                    </div>

                    <div class="ep-chart">
                        <div class="ep-bar" style="height:36%;background:rgba(0,212,170,0.18);"></div>
                        <div class="ep-bar" style="height:52%;background:rgba(0,212,170,0.30);"></div>
                        <div class="ep-bar" style="height:44%;background:rgba(0,212,170,0.42);"></div>
                        <div class="ep-bar" style="height:70%;background:rgba(0,212,170,0.58);"></div>
                        <div class="ep-bar" style="height:56%;background:rgba(0,212,170,0.72);"></div>
                        <div class="ep-bar" style="height:86%;background:#00d4aa;"></div>
                    </div>

                    <div class="ep-rows">
                        <div class="ep-row">
                            <div class="ep-av" style="background:#6c63ff1a;color:#6c63ff;">م</div>
                            <div class="ep-rtext">
                                <div class="ep-rl ep-w60"></div>
                                <div class="ep-rl ep-w40" style="opacity:.45;"></div>
                            </div>
                            <div class="ep-badge-sm" style="background:#00d4aa1a;color:#00a882;">فعال</div>
                        </div>
                        <div class="ep-row">
                            <div class="ep-av" style="background:#ff98001a;color:#e07b00;">ع</div>
                            <div class="ep-rtext">
                                <div class="ep-rl ep-w50"></div>
                                <div class="ep-rl ep-w35" style="opacity:.45;"></div>
                            </div>
                            <div class="ep-badge-sm" style="background:#ffd43b1a;color:#a87800;">در انتظار</div>
                        </div>
                        <div class="ep-row">
                            <div class="ep-av" style="background:#00d4aa1a;color:#00a882;">س</div>
                            <div class="ep-rtext">
                                <div class="ep-rl ep-w55"></div>
                                <div class="ep-rl ep-w30" style="opacity:.45;"></div>
                            </div>
                            <div class="ep-badge-sm" style="background:#5ddfb01a;color:#1a7a52;">تکمیل شد</div>
                        </div>
                    </div>
                </div>
            </div>{{-- /ep-browser --}}

            <div class="ep-float ep-float-1">
                <div class="ep-float-ic" style="background:#00d4aa1a;">
                    <i class="ri-check-double-line" style="color:#00d4aa;"></i>
                </div>
                <div>
                    <div class="ep-float-num">+۳۵۰</div>
                    <div class="ep-float-lbl">پروژه تکمیل شده</div>
                </div>
            </div>

            <div class="ep-float ep-float-2">
                <div class="ep-float-ic" style="background:#6c63ff1a;">
                    <i class="ri-star-fill" style="color:#6c63ff;"></i>
                </div>
                <div>
                    <div class="ep-float-num">۴.۹ / ۵</div>
                    <div class="ep-float-lbl">میانگین رضایت</div>
                </div>
            </div>

        </div>{{-- /ep-showcase-inner --}}
    </div>{{-- /ep-showcase --}}

</div>{{-- /ep-wrap --}}
@endsection
