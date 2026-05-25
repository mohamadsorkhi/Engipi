@extends('layouts.master-without-nav')

@section('title', 'ثبت نام رایگان')

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

/* ─── Outer wrapper ── */
.ep-wrap {
    display: flex;
    flex-direction: row;
    min-height: 100vh;
}

/* ════════════════════════════════════════════════════
   SHOWCASE PANEL  (left side in RTL, 58%)
════════════════════════════════════════════════════ */
.ep-showcase {
    flex: 1;
    background:
        linear-gradient(rgba(15,35,64,0.70), rgba(15,35,64,0.70)),
        url('/images/register-bg.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
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
    background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}
.ep-showcase-inner {
    max-width: 460px;
    width: 100%;
    position: relative;
    z-index: 2;
}

/* ─── Showcase copy ── */
.ep-sc-title {
    font-size: clamp(1.4rem, 2.2vw, 1.9rem);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.5;
    margin-bottom: 0.7rem;
}
.ep-sc-title .hl { color: #00d4aa; }
.ep-sc-sub {
    font-size: 0.88rem;
    color: rgba(255,255,255,0.72);
    line-height: 1.75;
    margin-bottom: 2rem;
    max-width: 380px;
}

/* ─── Stats ── */
.ep-stats {
    display: flex;
    gap: 1.2rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}
.ep-stat {
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 14px;
    padding: 0.9rem 1.2rem;
    flex: 1;
    min-width: 90px;
    text-align: center;
}
.ep-stat-num {
    font-size: 1.3rem;
    font-weight: 900;
    color: #00d4aa;
    line-height: 1.1;
}
.ep-stat-lbl {
    font-size: 0.68rem;
    color: rgba(255,255,255,0.65);
    margin-top: 3px;
}

/* ─── Floating cards ── */
.ep-float {
    position: absolute;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 32px rgba(0,40,80,0.18);
    padding: 11px 16px;
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 150px;
    z-index: 3;
}
.ep-float-1 { bottom: 60px; right: 20px; animation: floatUp 5s ease-in-out infinite; }
.ep-float-2 { top: 80px;   left: 20px;  animation: floatUp 5s ease-in-out infinite; animation-delay: 2.5s; }
@keyframes floatUp {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-9px); }
}
.ep-float-ic {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.ep-float-num { font-size: 0.95rem; font-weight: 800; color: #1a2a4a; line-height: 1.1; }
.ep-float-lbl { font-size: 0.63rem; color: #8898aa; margin-top: 1px; }

/* ════════════════════════════════════════════════════
   FORM PANEL  (right side in RTL, 42%)
════════════════════════════════════════════════════ */
.ep-form {
    width: 42%;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2rem 3rem;
    background: #ffffff;
    position: relative;
    z-index: 2;
    overflow-y: auto;
}
[dir="rtl"] .ep-form {
    border-left: 1px solid #f0f4fa;
    box-shadow: -12px 0 40px rgba(0,0,0,0.05);
}

/* ─── Logo ── */
.ep-logo { margin-bottom: 1.6rem; }
.ep-logo a { text-decoration: none; }
.ep-logo-word { font-size: 1.55rem; font-weight: 900; color: #1a2a4a; letter-spacing: -0.5px; }
.ep-logo-word .acc { color: #00d4aa; }

/* ─── Heading ── */
.ep-heading { font-size: 1.4rem; font-weight: 800; color: #1a2a4a; margin: 0 0 0.25rem; }
.ep-subhead  { font-size: 0.85rem; color: #8090a4; margin: 0 0 1.2rem; }

/* ─── Role path cards ── */
.ep-role-row { display: flex; gap: 0.6rem; margin-bottom: 1.2rem; }
.ep-role-card {
    flex: 1; text-align: center; padding: 0.75rem 0.5rem;
    border: 1.5px solid #e5eaf3; border-radius: 12px;
    text-decoration: none; color: #1a2a4a;
    transition: all 0.2s; cursor: pointer;
}
.ep-role-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.08); transform: translateY(-2px); color: #1a2a4a; }
.ep-role-card.employer { border-color: #ffc107; }
.ep-role-card.specialist { border-color: #00d4aa; background: rgba(0,212,170,0.04); }
.ep-role-icon { font-size: 1.35rem; margin-bottom: 0.2rem; }
.ep-role-name { font-size: 0.82rem; font-weight: 700; display: block; }
.ep-role-desc { font-size: 0.68rem; color: #8090a4; line-height: 1.4; }

/* ─── Divider ── */
.ep-divider-sm {
    display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;
}
.ep-divider-sm::before, .ep-divider-sm::after {
    content: ''; flex: 1; height: 1px; background: #e5eaf3;
}
.ep-divider-sm span { font-size: 0.72rem; color: #b0bfcc; white-space: nowrap; }

/* ─── Alerts ── */
.ep-alert-ok {
    background: #f0fdf8; border: 1px solid #a7f3d0; color: #065f46;
    border-radius: 10px; padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1rem;
}
.ep-alert-err {
    background: #fff5f5; border: 1px solid #fca5a5; color: #991b1b;
    border-radius: 10px; padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1rem;
}

/* ─── Field ── */
.ep-field { margin-bottom: 0.85rem; }
.ep-field-row { display: flex; gap: 0.75rem; }
.ep-field-row .ep-field { flex: 1; }
.ep-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374558; margin-bottom: 0.35rem; }
.ep-input-box { position: relative; display: flex; align-items: center; }
.ep-ico {
    position: absolute; font-size: 1rem; color: #b0bfcc;
    pointer-events: none; z-index: 2; top: 50%; transform: translateY(-50%);
}
[dir="rtl"] .ep-ico { right: 13px; }
.ep-inp {
    width: 100%;
    padding: 10px 42px 10px 42px;
    border: 1.5px solid #e5eaf3; border-radius: 10px;
    font-size: 0.855rem; color: #1a2a4a; background: #fafbfd;
    outline: none; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    direction: ltr;
}
[dir="rtl"] .ep-inp { text-align: right; }
.ep-inp::placeholder { color: #b8c4d0; direction: ltr; text-align: left; }
.ep-inp:focus { border-color: #00d4aa; background: #fff; box-shadow: 0 0 0 3.5px rgba(0,212,170,0.13); }
.ep-inp.is-invalid { border-color: #ef5350; }
.ep-eye {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #b0bfcc; cursor: pointer;
    font-size: 1rem; padding: 4px 8px; transition: color 0.2s; z-index: 2; line-height: 1;
}
[dir="rtl"] .ep-eye { left: 8px; }
.ep-eye:hover { color: #00d4aa; }
.ep-hint { font-size: 0.72rem; color: #a0aab4; margin-top: 3px; }

/* ─── CTA button ── */
.ep-btn-cta {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 12px 20px;
    background: linear-gradient(120deg, #00d4aa 0%, #6c63ff 100%);
    color: #fff; font-size: 0.92rem; font-weight: 700;
    border: none; border-radius: 10px; cursor: pointer;
    transition: transform 0.22s, box-shadow 0.22s, filter 0.22s;
    margin-bottom: 0.9rem; margin-top: 0.4rem;
}
.ep-btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,212,170,0.32);
    filter: brightness(1.04);
}

/* ─── Sign in row ── */
.ep-signin { text-align: center; font-size: 0.82rem; color: #8090a4; margin: 0; }
.ep-signin a { color: #00a882; font-weight: 700; text-decoration: none; transition: color 0.2s; }
.ep-signin a:hover { color: #00d4aa; text-decoration: underline; }

/* ─── Inline field errors ── */
.ep-field .invalid-feedback {
    display: none;
    font-size: 0.77rem;
    color: #ef5350;
    margin-top: 4px;
    padding-right: 2px;
}
.ep-field .invalid-feedback.d-block { display: block !important; }
.ep-inp.is-invalid { border-color: #ef5350 !important; }
.ep-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,83,80,0.12) !important; }

/* ─── Responsive ── */
@media (max-width: 900px) {
    .ep-form { width: 100%; padding: 2rem 1.8rem; }
    .ep-showcase { display: none; }
}
@media (max-width: 767px) {
    .ep-form {
        background-image: linear-gradient(rgba(255,255,255,0.92), rgba(255,255,255,0.92)), url('/images/register-bg.jpg');
        background-size: cover;
        background-position: center;
    }
}
@media (max-width: 480px) {
    .ep-form { padding: 1.6rem 1.1rem; }
    .ep-heading { font-size: 1.2rem; }
    .ep-field-row { flex-direction: column; gap: 0; }
}
</style>
@endsection

@section('content')
<div class="ep-wrap">

    {{-- ════════════ FORM PANEL — right in RTL ════════════ --}}
    <div class="ep-form">

        {{-- Logo --}}
        <div class="ep-logo">
            <a href="{{ route('root') }}">
                <div class="ep-logo-word"><span class="acc">Eng</span>Pis</div>
            </a>
        </div>

        {{-- Heading --}}
        <h2 class="ep-heading">ثبت نام رایگان</h2>
        <p class="ep-subhead">همین حالا حساب کاربری رایگان خود را بسازید</p>

        {{-- Role path selector --}}
        <div class="ep-role-row">
            <a href="{{ route('guest.project') }}" class="ep-role-card employer">
                <div class="ep-role-icon"><i class="ri-briefcase-line" style="color:#ffc107;"></i></div>
                <span class="ep-role-name">کارفرما</span>
                <span class="ep-role-desc">اول پروژه‌ام را ثبت می‌کنم</span>
            </a>
            <div class="ep-role-card specialist">
                <div class="ep-role-icon"><i class="ri-user-star-line" style="color:#00d4aa;"></i></div>
                <span class="ep-role-name">فریلنسر</span>
                <span class="ep-role-desc">فرم زیر را تکمیل می‌کنم</span>
            </div>
        </div>

        <div class="ep-divider-sm"><span>اطلاعات حساب</span></div>

        {{-- Alerts --}}
        @if(session('project_saved'))
            <div class="ep-alert-ok">
                <i class="ri-checkbox-circle-line me-1"></i>
                اطلاعات پروژه شما ذخیره شد. ثبت‌نام را تکمیل کنید.
            </div>
        @endif
        @if($errors->any())
            <div class="ep-alert-err">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            {{-- Name row --}}
            <div class="ep-field-row">
                <div class="ep-field mb-3">
                    <label class="ep-label" for="first_name">نام <span style="color:#ef5350;">*</span></label>
                    <div class="ep-input-box">
                        <i class="ri-user-3-line ep-ico"></i>
                        <input type="text" id="first_name" name="first_name"
                               class="ep-inp @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}"
                               placeholder="علی" required minlength="2" maxlength="255">
                    </div>
                    <div class="invalid-feedback @error('first_name') d-block @enderror">
                        <span>@error('first_name'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="ep-field mb-3">
                    <label class="ep-label" for="last_name">نام خانوادگی <span style="color:#ef5350;">*</span></label>
                    <div class="ep-input-box">
                        <i class="ri-user-3-line ep-ico"></i>
                        <input type="text" id="last_name" name="last_name"
                               class="ep-inp @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}"
                               placeholder="رضایی" required minlength="2" maxlength="255">
                    </div>
                    <div class="invalid-feedback @error('last_name') d-block @enderror">
                        <span>@error('last_name'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>

            {{-- Mobile --}}
            <div class="ep-field mb-3">
                <label class="ep-label" for="mobile">شماره موبایل <span style="color:#ef5350;">*</span></label>
                <div class="ep-input-box">
                    <i class="ri-smartphone-line ep-ico"></i>
                    <input type="text" id="mobile" name="mobile"
                           class="ep-inp @error('mobile') is-invalid @enderror"
                           value="{{ old('mobile') }}"
                           placeholder="09123456789" required pattern="^09\d{9}$">
                </div>
                <div class="invalid-feedback @error('mobile') d-block @enderror">
                    <span>@error('mobile'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Email --}}
            <div class="ep-field mb-3">
                <label class="ep-label" for="useremail">ایمیل <span style="color:#ef5350;">*</span></label>
                <div class="ep-input-box">
                    <i class="ri-mail-line ep-ico"></i>
                    <input type="email" id="useremail" name="email"
                           class="ep-inp @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="email@example.com" required autocomplete="email">
                </div>
                <div class="invalid-feedback @error('email') d-block @enderror">
                    <span>@error('email'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Password --}}
            <div class="ep-field mb-3">
                <label class="ep-label" for="password">رمز عبور <span style="color:#ef5350;">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password" id="password"
                           class="ep-inp @error('password') is-invalid @enderror"
                           placeholder="حداقل ۸ کاراکتر" required minlength="8" autocomplete="new-password"
                           style="padding-left:45px;">
                    <span onclick="document.getElementById('password').type=document.getElementById('password').type==='password'?'text':'password'; this.innerHTML=document.getElementById('password').type==='password'?'👁':'🙈';"
                          style="position:absolute;left:12px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:18px;z-index:10;">👁</span>
                </div>
                <div class="invalid-feedback @error('password') d-block @enderror">
                    <span>@error('password'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Password confirmation --}}
            <div class="ep-field mb-3">
                <label class="ep-label" for="password_confirmation">تایید رمز عبور <span style="color:#ef5350;">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="ep-inp @error('password_confirmation') is-invalid @enderror"
                           placeholder="••••••••" required minlength="8" autocomplete="new-password"
                           style="padding-left:45px;">
                    <span onclick="document.getElementById('password_confirmation').type=document.getElementById('password_confirmation').type==='password'?'text':'password'; this.innerHTML=document.getElementById('password_confirmation').type==='password'?'👁':'🙈';"
                          style="position:absolute;left:12px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:18px;z-index:10;">👁</span>
                </div>
                <div class="invalid-feedback @error('password_confirmation') d-block @enderror">
                    <span>@error('password_confirmation'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="ep-btn-cta ajax-submit">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                <i class="ri-user-add-line"></i>
                ثبت نام رایگان
            </button>

        </form>

        {{-- Sign in link --}}
        <p class="ep-signin">
            قبلاً ثبت‌نام کرده‌اید؟&nbsp;
            <a href="{{ route('login') }}">ورود به حساب</a>
        </p>

    </div>{{-- /ep-form --}}


    {{-- ════════════ SHOWCASE PANEL — left in RTL ════════════ --}}
    <div class="ep-showcase">
        <div class="ep-dot-grid"></div>

        <div class="ep-showcase-inner">

            {{-- Brand --}}
            <div style="margin-bottom:1.8rem;">
                <a href="{{ route('root') }}" style="text-decoration:none;">
                    <span style="font-size:1.9rem;font-weight:900;color:white;letter-spacing:-0.5px;">
                        <span style="color:#00d4aa;">Eng</span>Pis
                    </span>
                </a>
            </div>

            {{-- Heading --}}
            <h2 class="ep-sc-title">
                به بزرگترین پلتفرم<br>
                <span class="hl">مهندسی ایران</span> بپیوندید
            </h2>
            <p class="ep-sc-sub">
                ثبت‌نام رایگان است. کارفرمایان پروژه‌هایشان را ثبت می‌کنند و متخصصان بهترین فرصت‌های مهندسی را می‌یابند.
            </p>

            {{-- Stats --}}
            <div class="ep-stats">
                <div class="ep-stat">
                    <div class="ep-stat-num">+۵۰۰</div>
                    <div class="ep-stat-lbl">پروژه فعال</div>
                </div>
                <div class="ep-stat">
                    <div class="ep-stat-num">+۱۲۰۰</div>
                    <div class="ep-stat-lbl">متخصص</div>
                </div>
                <div class="ep-stat">
                    <div class="ep-stat-num">+۱۵</div>
                    <div class="ep-stat-lbl">حوزه تخصصی</div>
                </div>
            </div>

            {{-- Trust badges --}}
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-shield-check-line" style="color:#00d4aa;"></i> ثبت‌نام رایگان
                </div>
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-lock-line" style="color:#00d4aa;"></i> اطلاعات محفوظ
                </div>
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-rocket-line" style="color:#00d4aa;"></i> شروع فوری
                </div>
            </div>

        </div>

        {{-- Floating cards --}}
        <div class="ep-float ep-float-1">
            <div class="ep-float-ic" style="background:#00d4aa1a;">
                <i class="ri-briefcase-line" style="color:#00d4aa;"></i>
            </div>
            <div>
                <div class="ep-float-num">+۵۰۰</div>
                <div class="ep-float-lbl">پروژه ثبت‌شده</div>
            </div>
        </div>
        <div class="ep-float ep-float-2">
            <div class="ep-float-ic" style="background:#6c63ff1a;">
                <i class="ri-user-star-line" style="color:#6c63ff;"></i>
            </div>
            <div>
                <div class="ep-float-num">+۱۲۰۰</div>
                <div class="ep-float-lbl">متخصص فعال</div>
            </div>
        </div>

    </div>{{-- /ep-showcase --}}

</div>{{-- /ep-wrap --}}
@endsection
