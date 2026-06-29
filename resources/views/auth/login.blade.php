@extends('layouts.master-without-nav')

@section('title', 'ورود')

{{-- ══════════════════════════════════════════════════════
     Inject a proper <body> tag — master-without-nav needs it
══════════════════════════════════════════════════════ --}}
@section('body')
<body style="margin:0;padding:0;overflow-x:hidden;background:#fff;">
@endsection

{{-- ══════════════════════════════════════════════════════
     Page-level CSS  (injected into <head> via head-css.blade.php)
══════════════════════════════════════════════════════ --}}
@section('css')
<style>
/* ══════════════════════════════════════════════════════
   EngPis Login Page — Blueprint Split Layout
   Light theme, RTL, Vazirmatn
══════════════════════════════════════════════════════ */
html, body {
    height: 100%;
    margin: 0; padding: 0;
    background: #fff !important;
    color: var(--bp-text) !important;
}

/* ─── Outer wrapper ──────────────────────────────── */
.bp-auth-wrap {
    display: flex;
    flex-direction: row;
    min-height: 100vh;
}

/* ════════════════════════════════════════════════════
   FORM PANEL  (right side in RTL, 42%)
════════════════════════════════════════════════════ */
.bp-auth-form {
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
[dir="rtl"] .bp-auth-form {
    border-left: 1px solid var(--bp-hair);
    box-shadow: -12px 0 40px rgba(14,27,44,.05);
}

/* ─── Logo ─────────────────────────────────────── */
.bp-auth-logo { margin-bottom: 2.2rem; }
.bp-auth-logo a { text-decoration: none; }
.bp-auth-logo-word { font-size: 1.65rem; font-weight: 900; color: var(--bp-ink); letter-spacing: -0.5px; line-height: 1; }
.bp-auth-logo-word .a { color: var(--bp-blue); }

/* ─── Heading ──────────────────────────────────── */
.bp-auth-heading { font-size: 1.55rem; font-weight: 800; color: var(--bp-ink); margin: 0 0 0.3rem; }
.bp-auth-subhead { font-size: 0.88rem; color: var(--bp-muted); margin: 0 0 1.8rem; }

/* ─── Alerts ───────────────────────────────────── */
.bp-auth-alert-ok {
    background: var(--bp-tint-green); border: 1px solid #2E9E5B55; color: #1d6e40;
    border-radius: var(--bp-r); padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1.2rem;
}
.bp-auth-alert-err {
    background: var(--bp-tint-red); border: 1px solid #E14B4B55; color: #a32f2f;
    border-radius: var(--bp-r); padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1.2rem;
}

/* ─── Field ────────────────────────────────────── */
.bp-auth-field { margin-bottom: 1rem; }
.bp-auth-label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--bp-text); margin-bottom: 0.4rem; }

/* Input wrapper */
.bp-auth-input-box { position: relative; display: flex; align-items: center; }
.bp-auth-ico {
    position: absolute; font-size: 1rem; color: var(--bp-muted);
    pointer-events: none; z-index: 2; top: 50%; transform: translateY(-50%);
}
[dir="rtl"] .bp-auth-ico { right: 13px; }

.bp-auth-inp {
    width: 100%;
    padding: 11px 42px 11px 42px;
    border: 1.5px solid var(--bp-border);
    border-radius: var(--bp-r);
    font-size: 0.875rem;
    color: var(--bp-ink);
    background: var(--bp-surface);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    direction: ltr;
}
[dir="rtl"] .bp-auth-inp { text-align: right; }
.bp-auth-inp::placeholder { color: #b8c4d0; direction: ltr; text-align: left; }
.bp-auth-inp:focus { border-color: var(--bp-blue); background: #fff; box-shadow: 0 0 0 3.5px var(--bp-tint-blue); }
.bp-auth-inp.is-invalid { border-color: #E14B4B; }
.bp-auth-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,75,75,.12); }

/* Password toggle button */
.bp-auth-eye {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--bp-muted); cursor: pointer;
    font-size: 1rem; padding: 4px 8px; transition: color 0.2s; z-index: 2; line-height: 1;
}
[dir="rtl"] .bp-auth-eye { left: 8px; }
.bp-auth-eye:hover { color: var(--bp-blue); }

/* Forgot password */
.bp-auth-forgot { font-size: 0.77rem; color: var(--bp-blue); text-decoration: none; font-weight: 600; transition: color 0.2s; }
.bp-auth-forgot:hover { color: var(--bp-blue-d); text-decoration: underline; }

/* ─── Remember me ──────────────────────────────── */
.bp-auth-remember { display: flex; align-items: center; gap: 8px; margin-bottom: 1.4rem; justify-content: space-between; }
.bp-auth-checkbox { width: 15px; height: 15px; accent-color: var(--bp-blue); cursor: pointer; border-radius: 4px; flex-shrink: 0; }
.bp-auth-check-lbl { font-size: 0.82rem; color: var(--bp-text); cursor: pointer; user-select: none; }

/* ─── Sign up row ──────────────────────────────── */
.bp-auth-signup { text-align: center; font-size: 0.83rem; color: var(--bp-muted); margin: 0; }
.bp-auth-signup a { color: var(--bp-blue); font-weight: 700; text-decoration: none; transition: color 0.2s; }
.bp-auth-signup a:hover { color: var(--bp-blue-d); text-decoration: underline; }

/* ════════════════════════════════════════════════════
   SHOWCASE PANEL  (left side in RTL, navy blueprint)
════════════════════════════════════════════════════ */
.bp-auth-showcase {
    flex: 1;
    background: var(--bp-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    position: relative;
    overflow: hidden;
}
.bp-auth-showcase .grid-bg { position: absolute; inset: 0; opacity: .6; }
.bp-auth-showcase .glow { position: absolute; border-radius: 50%; filter: blur(10px); }
.bp-auth-showcase .g1 { top: -100px; inset-inline-end: -60px; width: 340px; height: 340px; background: radial-gradient(circle, rgba(31,111,235,.35), transparent 70%); }
.bp-auth-showcase .g2 { bottom: -120px; inset-inline-start: -40px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,184,169,.22), transparent 70%); }

.bp-auth-showcase-inner { max-width: 480px; width: 100%; position: relative; z-index: 2; }

/* ─── Showcase copy ────────────────────────────── */
.bp-auth-sc-title { font-size: clamp(1.4rem, 2.4vw, 2rem); font-weight: 900; color: #fff; line-height: 1.45; margin-bottom: 0.7rem; }
.bp-auth-sc-title .hl { color: var(--bp-blue-l); }
.bp-auth-sc-sub { font-size: 0.88rem; color: rgba(255,255,255,.74); line-height: 1.75; margin-bottom: 1.8rem; max-width: 400px; }

/* ─── Browser mockup ───────────────────────────── */
.bp-auth-browser { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.12); border-radius: var(--bp-r-xl); overflow: hidden; margin-bottom: 1.4rem; backdrop-filter: blur(8px); }
.bp-auth-browser-bar { background: rgba(255,255,255,.04); border-bottom: 1px solid rgba(255,255,255,.1); padding: 9px 14px; display: flex; align-items: center; gap: 10px; }
.bp-auth-dots { display: flex; gap: 5px; }
.bp-auth-dots span { width: 9px; height: 9px; border-radius: 50%; display: block; }
.bp-auth-url-bar { font-size: 0.68rem; color: rgba(255,255,255,.55); background: rgba(255,255,255,.06); border-radius: 6px; padding: 3px 10px; direction: ltr; letter-spacing: 0.01em; }
.bp-auth-browser-body { padding: 14px; }

/* Mini stats */
.bp-auth-mini-stats { display: flex; gap: 8px; margin-bottom: 12px; }
.bp-auth-ms { flex: 1; background: rgba(255,255,255,.05); border-radius: var(--bp-r-lg); padding: 10px; border-top: 3px solid var(--c); }
.bp-auth-ms-num { font-size: 0.95rem; font-weight: 800; color: var(--c); line-height: 1.1; }
.bp-auth-ms-lbl { font-size: 0.6rem; color: rgba(255,255,255,.5); margin-top: 3px; }

/* Chart bars */
.bp-auth-chart { display: flex; align-items: flex-end; gap: 5px; height: 48px; background: rgba(255,255,255,.05); border-radius: var(--bp-r); padding: 8px; margin-bottom: 12px; }
.bp-auth-bar { flex: 1; border-radius: 3px 3px 0 0; min-height: 6px; background: var(--bp-blue-l); transition: all 0.3s; }

/* Rows */
.bp-auth-rows { display: flex; flex-direction: column; gap: 6px; }
.bp-auth-row { display: flex; align-items: center; gap: 10px; padding: 7px 10px; background: rgba(255,255,255,.05); border-radius: var(--bp-r); }
.bp-auth-av { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.68rem; font-weight: 800; flex-shrink: 0; }
.bp-auth-rtext { flex: 1; }
.bp-auth-rl { height: 5px; background: rgba(255,255,255,.12); border-radius: 3px; margin-bottom: 4px; }
.bp-auth-rl:last-child { margin-bottom: 0; }
.bp-auth-w60 { width: 60%; } .bp-auth-w50 { width: 50%; } .bp-auth-w55 { width: 55%; }
.bp-auth-w40 { width: 40%; } .bp-auth-w35 { width: 35%; } .bp-auth-w30 { width: 30%; }
.bp-auth-badge-sm { font-size: 0.6rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; flex-shrink: 0; white-space: nowrap; }

/* ─── Floating stat cards ──────────────────────── */
.bp-auth-float {
    position: absolute; background: #fff; border-radius: var(--bp-r-lg);
    box-shadow: var(--bp-sh-lg); padding: 11px 16px;
    display: flex; align-items: center; gap: 11px; min-width: 155px; z-index: 3;
}
.bp-auth-float-1 { bottom: 60px; right: 16px; animation: bpAuthFloat 5s ease-in-out infinite; }
.bp-auth-float-2 { top: 90px; left: 16px; animation: bpAuthFloat 5s ease-in-out infinite; animation-delay: 2.5s; }
@keyframes bpAuthFloat { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-9px); } }
.bp-auth-float-ic { width: 34px; height: 34px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.bp-auth-float-num { font-size: 0.98rem; font-weight: 800; color: var(--bp-ink); line-height: 1.1; }
.bp-auth-float-lbl { font-size: 0.65rem; color: var(--bp-muted); margin-top: 1px; }

/* ─── Responsive ───────────────────────────────── */
@media (max-width: 900px) {
    .bp-auth-form { width: 100%; padding: 2rem 1.6rem; }
    .bp-auth-showcase { display: none; }
}
@media (max-width: 480px) {
    .bp-auth-form { padding: 1.8rem 1.2rem; }
    .bp-auth-heading { font-size: 1.3rem; }
}
</style>
@endsection


{{-- ══════════════════════════════════════════════════════
     Page content
══════════════════════════════════════════════════════ --}}
@section('content')
<div class="bp-auth-wrap">

    {{-- ════════════════════════════════════════
         FORM PANEL — right side in RTL
    ════════════════════════════════════════ --}}
    <div class="bp-auth-form">

        {{-- Logo --}}
        <div class="bp-auth-logo">
            <a href="{{ route('root') }}">
                <div class="bp-auth-logo-word"><span class="a">Eng</span>Pis</div>
            </a>
        </div>

        {{-- Heading --}}
        <h2 class="bp-auth-heading">خوش آمدید! 👋</h2>
        <p class="bp-auth-subhead">با اطلاعات حساب کاربری خود وارد شوید</p>

        {{-- Flash & validation messages --}}
        @if(session('status'))
            <div class="bp-auth-alert-ok">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="bp-auth-alert-err">{{ $errors->first() }}</div>
        @endif

        {{-- ── Login Form ── --}}
        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Email / Mobile --}}
            <div class="bp-auth-field">
                <label class="bp-auth-label" for="login">ایمیل یا شماره موبایل</label>
                <div class="bp-auth-input-box">
                    <i class="ri-user-3-line bp-auth-ico"></i>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        class="bp-auth-inp @error('login') is-invalid @enderror"
                        value="{{ old('login') }}"
                        placeholder="email@example.com"
                        autocomplete="username"
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div class="bp-auth-field">
                <label class="bp-auth-label" for="password">رمز عبور</label>
                <div style="position:relative;">
                    <input type="password" name="password" id="password"
                        class="bp-auth-inp @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                        style="padding-left:45px;">
                    <button type="button" class="bp-pwd-toggle toggle-password" data-target="#password" aria-label="نمایش رمز عبور" aria-pressed="false">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
            </div>

            {{-- Remember me + Forgot password on one line --}}
            <div class="bp-auth-remember">
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" id="auth-remember-check" name="remember" class="bp-auth-checkbox">
                    <label for="auth-remember-check" class="bp-auth-check-lbl">مرا به خاطر بسپار</label>
                </div>
                <a href="{{ route('password.request') }}" class="bp-auth-forgot">فراموش کردید؟</a>
            </div>

            {{-- CTA button --}}
            <button type="submit" class="bp-btn bp-btn--primary ajax-submit" style="width:100%; justify-content:center; margin-bottom:1rem;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                <i class="ri-login-box-line"></i>
                ورود به حساب
            </button>

        </form>

        {{-- Sign up link --}}
        <p class="bp-auth-signup">
            حساب کاربری ندارید؟&nbsp;
            <a href="{{ route('register') }}">ثبت‌نام رایگان</a>
        </p>

    </div>{{-- /bp-auth-form --}}


    {{-- ════════════════════════════════════════
         SHOWCASE PANEL — left side in RTL
    ════════════════════════════════════════ --}}
    <div class="bp-auth-showcase" style="background-image: linear-gradient(rgba(10,20,40,0.5), rgba(10,20,40,0.5)), url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center;">
        <div class="grid-bg bp-grid" style="display:none;"></div>
        <div class="glow g1" style="display:none;"></div>
        <div class="glow g2" style="display:none;"></div>

        <div class="bp-auth-showcase-inner">

            {{-- Heading copy --}}
            <h2 class="bp-auth-sc-title">
                سریع‌ترین راه برای یافتن<br>
                <span class="hl">متخصص مهندسی</span>
            </h2>
            <p class="bp-auth-sc-sub">
                بیش از ۱۲۰۰ متخصص در حوزه‌های برق، مکانیک، کامپیوتر و عمران آماده همکاری با شما هستند.
            </p>

            {{-- ── Dashboard browser mockup ── --}}
            <div class="bp-auth-browser">

                {{-- Browser chrome bar --}}
                <div class="bp-auth-browser-bar">
                    <div class="bp-auth-dots">
                        <span style="background:#E14B4B;"></span>
                        <span style="background:#E0930B;"></span>
                        <span style="background:#00B8A9;"></span>
                    </div>
                    <div class="bp-auth-url-bar">&#x1F512;&nbsp; engpis.ir/dashboard</div>
                </div>

                {{-- Browser content --}}
                <div class="bp-auth-browser-body">

                    {{-- Mini stat cards --}}
                    <div class="bp-auth-mini-stats">
                        <div class="bp-auth-ms" style="--c:#4d97ff;">
                            <div class="bp-auth-ms-num">+۵۰۰</div>
                            <div class="bp-auth-ms-lbl">پروژه فعال</div>
                        </div>
                        <div class="bp-auth-ms" style="--c:#00B8A9;">
                            <div class="bp-auth-ms-num">+۱۲۰۰</div>
                            <div class="bp-auth-ms-lbl">متخصص</div>
                        </div>
                        <div class="bp-auth-ms" style="--c:#E0930B;">
                            <div class="bp-auth-ms-num">۹۸٪</div>
                            <div class="bp-auth-ms-lbl">رضایت</div>
                        </div>
                    </div>

                    {{-- Bar chart --}}
                    <div class="bp-auth-chart">
                        <div class="bp-auth-bar" style="height:36%;opacity:.35;"></div>
                        <div class="bp-auth-bar" style="height:52%;opacity:.5;"></div>
                        <div class="bp-auth-bar" style="height:44%;opacity:.65;"></div>
                        <div class="bp-auth-bar" style="height:70%;opacity:.8;"></div>
                        <div class="bp-auth-bar" style="height:56%;opacity:.9;"></div>
                        <div class="bp-auth-bar" style="height:86%;"></div>
                    </div>

                    {{-- Project rows --}}
                    <div class="bp-auth-rows">
                        <div class="bp-auth-row">
                            <div class="bp-auth-av" style="background:rgba(0,184,169,.18);color:#00B8A9;">م</div>
                            <div class="bp-auth-rtext">
                                <div class="bp-auth-rl bp-auth-w60"></div>
                                <div class="bp-auth-rl bp-auth-w40" style="opacity:.45;"></div>
                            </div>
                            <div class="bp-auth-badge-sm" style="background:rgba(0,184,169,.18);color:#5fe0d2;">فعال</div>
                        </div>
                        <div class="bp-auth-row">
                            <div class="bp-auth-av" style="background:rgba(224,147,11,.18);color:#E0930B;">ع</div>
                            <div class="bp-auth-rtext">
                                <div class="bp-auth-rl bp-auth-w50"></div>
                                <div class="bp-auth-rl bp-auth-w35" style="opacity:.45;"></div>
                            </div>
                            <div class="bp-auth-badge-sm" style="background:rgba(224,147,11,.18);color:#f3b65c;">در انتظار</div>
                        </div>
                        <div class="bp-auth-row">
                            <div class="bp-auth-av" style="background:rgba(31,111,235,.18);color:#4d97ff;">س</div>
                            <div class="bp-auth-rtext">
                                <div class="bp-auth-rl bp-auth-w55"></div>
                                <div class="bp-auth-rl bp-auth-w30" style="opacity:.45;"></div>
                            </div>
                            <div class="bp-auth-badge-sm" style="background:rgba(31,111,235,.18);color:#8db8ff;">تکمیل شد</div>
                        </div>
                    </div>

                </div>
            </div>{{-- /bp-auth-browser --}}

            {{-- ── Floating stat cards ── --}}
            <div class="bp-auth-float bp-auth-float-1">
                <div class="bp-auth-float-ic" style="background:var(--bp-tint-teal);">
                    <i class="ri-check-double-line" style="color:var(--bp-teal);"></i>
                </div>
                <div>
                    <div class="bp-auth-float-num">+۳۵۰</div>
                    <div class="bp-auth-float-lbl">پروژه تکمیل شده</div>
                </div>
            </div>

            <div class="bp-auth-float bp-auth-float-2">
                <div class="bp-auth-float-ic" style="background:var(--bp-tint-blue);">
                    <i class="ri-star-fill" style="color:var(--bp-blue);"></i>
                </div>
                <div>
                    <div class="bp-auth-float-num">۴.۹ / ۵</div>
                    <div class="bp-auth-float-lbl">میانگین رضایت</div>
                </div>
            </div>

        </div>{{-- /bp-auth-showcase-inner --}}
    </div>{{-- /bp-auth-showcase --}}

</div>{{-- /bp-auth-wrap --}}
@endsection
@section('script')
    <script>
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(btn.getAttribute('data-target'));
            var icon = btn.querySelector('i');
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('ri-eye-line', !isHidden);
            icon.classList.toggle('ri-eye-off-line', isHidden);
            btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            btn.setAttribute('aria-label', isHidden ? 'پنهان کردن رمز عبور' : 'نمایش رمز عبور');
        });
    });
    </script>
@endsection
