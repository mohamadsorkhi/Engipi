@extends('layouts.master-without-nav')

@section('title', 'ثبت نام رایگان')

@section('body')
<body style="margin:0;padding:0;overflow-x:hidden;background:#fff;">
@endsection

@section('css')
<style>
html, body {
    height: 100%;
    margin: 0; padding: 0;
    background: #fff !important;
    color: var(--bp-text) !important;
}

/* ─── Outer wrapper ── */
.bp-auth-wrap {
    display: flex;
    flex-direction: row;
    min-height: 100vh;
}

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
.bp-auth-showcase-inner { max-width: 460px; width: 100%; position: relative; z-index: 2; }

/* ─── Showcase copy ── */
.bp-auth-sc-title { font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 900; color: #fff; line-height: 1.5; margin-bottom: 0.7rem; }
.bp-auth-sc-title .hl { color: var(--bp-blue-l); }
.bp-auth-sc-sub { font-size: 0.88rem; color: rgba(255,255,255,.72); line-height: 1.75; margin-bottom: 2rem; max-width: 380px; }

/* ─── Stats ── */
.bp-auth-stats { display: flex; gap: 1.2rem; flex-wrap: wrap; margin-bottom: 2rem; }
.bp-auth-stat { background: rgba(255,255,255,.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.15); border-radius: var(--bp-r-lg); padding: 0.9rem 1.2rem; flex: 1; min-width: 90px; text-align: center; }
.bp-auth-stat-num { font-size: 1.3rem; font-weight: 900; color: var(--bp-blue-l); line-height: 1.1; }
.bp-auth-stat-lbl { font-size: 0.68rem; color: rgba(255,255,255,.65); margin-top: 3px; }

/* ─── Floating cards ── */
.bp-auth-float {
    position: absolute; background: #fff; border-radius: var(--bp-r-lg);
    box-shadow: var(--bp-sh-lg); padding: 11px 16px;
    display: flex; align-items: center; gap: 11px; min-width: 150px; z-index: 3;
}
.bp-auth-float-1 { bottom: 60px; right: 20px; animation: bpAuthFloat 5s ease-in-out infinite; }
.bp-auth-float-2 { top: 80px; left: 20px; animation: bpAuthFloat 5s ease-in-out infinite; animation-delay: 2.5s; }
@keyframes bpAuthFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
.bp-auth-float-ic { width: 34px; height: 34px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.bp-auth-float-num { font-size: 0.95rem; font-weight: 800; color: var(--bp-ink); line-height: 1.1; }
.bp-auth-float-lbl { font-size: 0.63rem; color: var(--bp-muted); margin-top: 1px; }

/* ════════════════════════════════════════════════════
   FORM PANEL  (right side in RTL, 42%)
════════════════════════════════════════════════════ */
.bp-auth-form {
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
[dir="rtl"] .bp-auth-form {
    border-left: 1px solid var(--bp-hair);
    box-shadow: -12px 0 40px rgba(14,27,44,.05);
}

/* ─── Logo ── */
.bp-auth-logo { margin-bottom: 1.6rem; }
.bp-auth-logo a { text-decoration: none; }
.bp-auth-logo-word { font-size: 1.55rem; font-weight: 900; color: var(--bp-ink); letter-spacing: -0.5px; }
.bp-auth-logo-word .a { color: var(--bp-blue); }

/* ─── Heading ── */
.bp-auth-heading { font-size: 1.4rem; font-weight: 800; color: var(--bp-ink); margin: 0 0 0.25rem; }
.bp-auth-subhead { font-size: 0.85rem; color: var(--bp-muted); margin: 0 0 1.2rem; }

/* ─── Role path cards ── */
.bp-auth-role-row { display: flex; gap: 0.6rem; margin-bottom: 1.2rem; }
.bp-auth-role-card {
    flex: 1; text-align: center; padding: 0.75rem 0.5rem;
    border: 1.5px solid var(--bp-border); border-radius: var(--bp-r-lg);
    text-decoration: none; color: var(--bp-ink);
    transition: all 0.2s var(--bp-ease); cursor: pointer;
}
.bp-auth-role-card:hover { box-shadow: var(--bp-sh-sm); transform: translateY(-2px); color: var(--bp-ink); border-color: var(--bp-blue); }
.bp-auth-role-card.employer { border-color: #E0930B; }
.bp-auth-role-card.specialist { border-color: var(--bp-teal); background: var(--bp-tint-teal); }
.bp-auth-role-icon { font-size: 1.35rem; margin-bottom: 0.2rem; }
.bp-auth-role-name { font-size: 0.82rem; font-weight: 700; display: block; }
.bp-auth-role-desc { font-size: 0.68rem; color: var(--bp-muted); line-height: 1.4; }

/* ─── Divider ── */
.bp-auth-divider-sm { display: flex; align-items: center; gap: 8px; margin-bottom: 1rem; }
.bp-auth-divider-sm::before, .bp-auth-divider-sm::after { content: ''; flex: 1; height: 1px; background: var(--bp-border); }
.bp-auth-divider-sm span { font-size: 0.72rem; color: var(--bp-muted); white-space: nowrap; }

/* ─── Alerts ── */
.bp-auth-alert-ok { background: var(--bp-tint-green); border: 1px solid #2E9E5B55; color: #1d6e40; border-radius: var(--bp-r); padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1rem; }
.bp-auth-alert-err { background: var(--bp-tint-red); border: 1px solid #E14B4B55; color: #a32f2f; border-radius: var(--bp-r); padding: 10px 14px; font-size: 0.83rem; margin-bottom: 1rem; }

/* ─── Field ── */
.bp-auth-field { margin-bottom: 0.85rem; }
.bp-auth-field-row { display: flex; gap: 0.75rem; }
.bp-auth-field-row .bp-auth-field { flex: 1; }
.bp-auth-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--bp-text); margin-bottom: 0.35rem; }
.bp-auth-input-box { position: relative; display: flex; align-items: center; }
.bp-auth-ico { position: absolute; font-size: 1rem; color: var(--bp-muted); pointer-events: none; z-index: 2; top: 50%; transform: translateY(-50%); }
[dir="rtl"] .bp-auth-ico { right: 13px; }
.bp-auth-inp {
    width: 100%;
    padding: 10px 42px 10px 42px;
    border: 1.5px solid var(--bp-border); border-radius: var(--bp-r);
    font-size: 0.855rem; color: var(--bp-ink); background: var(--bp-surface);
    outline: none; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    direction: ltr;
}
[dir="rtl"] .bp-auth-inp { text-align: right; }
.bp-auth-inp::placeholder { color: #b8c4d0; direction: ltr; text-align: left; }
.bp-auth-inp:focus { border-color: var(--bp-blue); background: #fff; box-shadow: 0 0 0 3.5px var(--bp-tint-blue); }
.bp-auth-inp.is-invalid { border-color: #E14B4B !important; }
.bp-auth-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,75,75,.12) !important; }
.bp-auth-eye {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--bp-muted); cursor: pointer;
    font-size: 1rem; padding: 4px 8px; transition: color 0.2s; z-index: 2; line-height: 1;
}
[dir="rtl"] .bp-auth-eye { left: 8px; }
.bp-auth-eye:hover { color: var(--bp-blue); }
.bp-auth-hint { font-size: 0.72rem; color: var(--bp-muted); margin-top: 3px; }

/* ─── Sign in row ── */
.bp-auth-signin { text-align: center; font-size: 0.82rem; color: var(--bp-muted); margin: 0; }
.bp-auth-signin a { color: var(--bp-blue); font-weight: 700; text-decoration: none; transition: color 0.2s; }
.bp-auth-signin a:hover { color: var(--bp-blue-d); text-decoration: underline; }

/* ─── Inline field errors ── */
.bp-auth-field .invalid-feedback { display: none; font-size: 0.77rem; color: #E14B4B; margin-top: 4px; padding-right: 2px; }
.bp-auth-field .invalid-feedback.d-block { display: block !important; }

/* ─── Responsive ── */
@media (max-width: 900px) {
    .bp-auth-form { width: 100%; padding: 2rem 1.8rem; }
    .bp-auth-showcase { display: none; }
}
@media (max-width: 480px) {
    .bp-auth-form { padding: 1.6rem 1.1rem; }
    .bp-auth-heading { font-size: 1.2rem; }
    .bp-auth-field-row { flex-direction: column; gap: 0; }
}
</style>
@endsection

@section('content')
<div class="bp-auth-wrap">

    {{-- ════════════ FORM PANEL — right in RTL ════════════ --}}
    <div class="bp-auth-form">

        {{-- Logo --}}
        <div class="bp-auth-logo">
            <a href="{{ route('root') }}">
                <div class="bp-auth-logo-word"><span class="a">Eng</span>Pis</div>
            </a>
        </div>

        {{-- Heading --}}
        <h2 class="bp-auth-heading">ثبت نام رایگان</h2>
        <p class="bp-auth-subhead">همین حالا حساب کاربری رایگان خود را بسازید</p>

        {{-- Role path selector --}}
        <div class="bp-auth-role-row">
            <a href="{{ route('guest.project') }}" class="bp-auth-role-card employer">
                <div class="bp-auth-role-icon"><i class="ri-briefcase-line" style="color:#E0930B;"></i></div>
                <span class="bp-auth-role-name">کارفرما</span>
                <span class="bp-auth-role-desc">اول پروژه‌ام را ثبت می‌کنم</span>
            </a>
            <a href="{{ route('register') }}" class="bp-auth-role-card specialist">
                <div class="bp-auth-role-icon"><i class="ri-user-star-line" style="color:var(--bp-teal);"></i></div>
                <span class="bp-auth-role-name">فریلنسر</span>
                <span class="bp-auth-role-desc">فرم زیر را تکمیل می‌کنم</span>
            </a>
        </div>

        <div class="bp-auth-divider-sm"><span>اطلاعات حساب</span></div>

        {{-- Alerts --}}
        @if(session('project_saved'))
            <div class="bp-auth-alert-ok">
                <i class="ri-checkbox-circle-line me-1"></i>
                اطلاعات پروژه شما ذخیره شد. ثبت‌نام را تکمیل کنید.
            </div>
        @endif
        @if($errors->any())
            <div class="bp-auth-alert-err">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            {{-- Name row --}}
            <div class="bp-auth-field-row">
                <div class="bp-auth-field mb-3">
                    <label class="bp-auth-label" for="first_name">نام <span style="color:#E14B4B;">*</span></label>
                    <div class="bp-auth-input-box">
                        <i class="ri-user-3-line bp-auth-ico"></i>
                        <input type="text" id="first_name" name="first_name"
                               class="bp-auth-inp @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}"
                               placeholder="علی" required minlength="2" maxlength="255">
                    </div>
                    <div class="invalid-feedback @error('first_name') d-block @enderror">
                        <span>@error('first_name'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="bp-auth-field mb-3">
                    <label class="bp-auth-label" for="last_name">نام خانوادگی <span style="color:#E14B4B;">*</span></label>
                    <div class="bp-auth-input-box">
                        <i class="ri-user-3-line bp-auth-ico"></i>
                        <input type="text" id="last_name" name="last_name"
                               class="bp-auth-inp @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}"
                               placeholder="رضایی" required minlength="2" maxlength="255">
                    </div>
                    <div class="invalid-feedback @error('last_name') d-block @enderror">
                        <span>@error('last_name'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>

            {{-- Mobile --}}
            <div class="bp-auth-field mb-3">
                <label class="bp-auth-label" for="mobile">شماره موبایل <span style="color:#E14B4B;">*</span></label>
                <div class="bp-auth-input-box">
                    <i class="ri-smartphone-line bp-auth-ico"></i>
                    <input type="text" id="mobile" name="mobile"
                           class="bp-auth-inp @error('mobile') is-invalid @enderror"
                           value="{{ old('mobile') }}"
                           placeholder="09123456789" required pattern="^09\d{9}$">
                </div>
                <div class="invalid-feedback @error('mobile') d-block @enderror">
                    <span>@error('mobile'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Email --}}
            <div class="bp-auth-field mb-3">
                <label class="bp-auth-label" for="useremail">ایمیل <span style="color:#E14B4B;">*</span></label>
                <div class="bp-auth-input-box">
                    <i class="ri-mail-line bp-auth-ico"></i>
                    <input type="email" id="useremail" name="email"
                           class="bp-auth-inp @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="email@example.com" required autocomplete="email">
                </div>
                <div class="invalid-feedback @error('email') d-block @enderror">
                    <span>@error('email'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Password --}}
            <div class="bp-auth-field mb-3">
                <label class="bp-auth-label" for="password">رمز عبور <span style="color:#E14B4B;">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password" id="password"
                           class="bp-auth-inp @error('password') is-invalid @enderror"
                           placeholder="حداقل ۸ کاراکتر" required minlength="8" autocomplete="new-password"
                           style="padding-left:45px;">
                    <button type="button" class="bp-pwd-toggle toggle-password" data-target="#password" aria-label="نمایش رمز عبور" aria-pressed="false">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                <div class="invalid-feedback @error('password') d-block @enderror">
                    <span>@error('password'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Password confirmation --}}
            <div class="bp-auth-field mb-3">
                <label class="bp-auth-label" for="password_confirmation">تایید رمز عبور <span style="color:#E14B4B;">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="bp-auth-inp @error('password_confirmation') is-invalid @enderror"
                           placeholder="••••••••" required minlength="8" autocomplete="new-password"
                           style="padding-left:45px;">
                    <button type="button" class="bp-pwd-toggle toggle-password" data-target="#password_confirmation" aria-label="نمایش رمز عبور" aria-pressed="false">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                <div class="invalid-feedback @error('password_confirmation') d-block @enderror">
                    <span>@error('password_confirmation'){{ $message }}@enderror</span>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="bp-btn bp-btn--primary ajax-submit" style="width:100%; justify-content:center; margin-bottom:0.9rem; margin-top:0.4rem;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                <i class="ri-user-add-line"></i>
                ثبت نام رایگان
            </button>

        </form>

        {{-- Sign in link --}}
        <p class="bp-auth-signin">
            قبلاً ثبت‌نام کرده‌اید؟&nbsp;
            <a href="{{ route('login') }}">ورود به حساب</a>
        </p>

    </div>{{-- /bp-auth-form --}}


    {{-- ════════════ SHOWCASE PANEL — left in RTL ════════════ --}}
    <div class="bp-auth-showcase">
        <div class="grid-bg bp-grid"></div>
        <div class="glow g1"></div>
        <div class="glow g2"></div>

        <div class="bp-auth-showcase-inner">

            {{-- Brand --}}
            <div style="margin-bottom:1.8rem;">
                <a href="{{ route('root') }}" style="text-decoration:none;">
                    <span style="font-size:1.9rem;font-weight:900;color:white;letter-spacing:-0.5px;">
                        <span style="color:var(--bp-blue-l);">Eng</span>Pis
                    </span>
                </a>
            </div>

            {{-- Heading --}}
            <h2 class="bp-auth-sc-title">
                به بزرگترین پلتفرم<br>
                <span class="hl">مهندسی ایران</span> بپیوندید
            </h2>
            <p class="bp-auth-sc-sub">
                ثبت‌نام رایگان است. کارفرمایان پروژه‌هایشان را ثبت می‌کنند و متخصصان بهترین فرصت‌های مهندسی را می‌یابند.
            </p>

            {{-- Stats --}}
            <div class="bp-auth-stats">
                <div class="bp-auth-stat">
                    <div class="bp-auth-stat-num">+۵۰۰</div>
                    <div class="bp-auth-stat-lbl">پروژه فعال</div>
                </div>
                <div class="bp-auth-stat">
                    <div class="bp-auth-stat-num">+۱۲۰۰</div>
                    <div class="bp-auth-stat-lbl">متخصص</div>
                </div>
                <div class="bp-auth-stat">
                    <div class="bp-auth-stat-num">+۱۵</div>
                    <div class="bp-auth-stat-lbl">حوزه تخصصی</div>
                </div>
            </div>

            {{-- Trust badges --}}
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-shield-check-line" style="color:var(--bp-teal);"></i> ثبت‌نام رایگان
                </div>
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-lock-line" style="color:var(--bp-teal);"></i> اطلاعات محفوظ
                </div>
                <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,0.6);font-size:0.82rem;">
                    <i class="ri-rocket-line" style="color:var(--bp-teal);"></i> شروع فوری
                </div>
            </div>

        </div>

        {{-- Floating cards --}}
        <div class="bp-auth-float bp-auth-float-1">
            <div class="bp-auth-float-ic" style="background:var(--bp-tint-blue);">
                <i class="ri-briefcase-line" style="color:var(--bp-blue);"></i>
            </div>
            <div>
                <div class="bp-auth-float-num">+۵۰۰</div>
                <div class="bp-auth-float-lbl">پروژه ثبت‌شده</div>
            </div>
        </div>
        <div class="bp-auth-float bp-auth-float-2">
            <div class="bp-auth-float-ic" style="background:var(--bp-tint-teal);">
                <i class="ri-user-star-line" style="color:var(--bp-teal);"></i>
            </div>
            <div>
                <div class="bp-auth-float-num">+۱۲۰۰</div>
                <div class="bp-auth-float-lbl">متخصص فعال</div>
            </div>
        </div>

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
