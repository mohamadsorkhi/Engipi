@extends('layouts.master-without-nav')

@section('title', 'ثبت پروژه — قبل از عضویت')

@section('body')
<body class="bp-project-page">
@endsection

@section('css')
<style>
html, body { height: 100%; margin: 0; padding: 0; background: #fff !important; color: var(--bp-text) !important; }
.bp-project-page { overflow-x: hidden; }
.bp-auth-wrap { display: flex; flex-direction: row; min-height: 100vh; }
.bp-auth-form {
    width: 42%; flex-shrink: 0; display: flex; flex-direction: column; justify-content: center;
    padding: 2rem 3rem; background: #fff; position: relative; z-index: 2; overflow-y: auto;
}
[dir="rtl"] .bp-auth-form { border-left: 1px solid var(--bp-hair); box-shadow: -12px 0 40px rgba(14,27,44,.05); }
.bp-auth-form-inner { width: 100%; }
.bp-auth-logo { margin-bottom: 1.6rem; }
.bp-auth-logo a { text-decoration: none; }
.bp-auth-logo-word { color: var(--bp-ink); font-size: 1.55rem; font-weight: 900; letter-spacing: -.5px; }
.bp-auth-logo-word .a { color: var(--bp-blue); }
.bp-auth-step {
    display: inline-flex; align-items: center; gap: .4rem; margin-bottom: .8rem; padding: .35rem .7rem;
    border-radius: 999px; color: var(--bp-blue); background: var(--bp-tint-blue); font-size: .72rem; font-weight: 700;
}
.bp-auth-heading { margin: 0 0 .25rem; color: var(--bp-ink); font-size: 1.4rem; font-weight: 800; }
.bp-auth-subhead { margin: 0 0 1.2rem; color: var(--bp-muted); font-size: .85rem; }
.bp-auth-alert-err {
    margin-bottom: 1rem; padding: 10px 14px; border: 1px solid #E14B4B55; border-radius: var(--bp-r);
    color: #a32f2f; background: var(--bp-tint-red); font-size: .83rem;
}
.bp-auth-field { margin-bottom: .85rem; }
.bp-auth-field-row { display: flex; gap: .75rem; }
.bp-auth-field-row .bp-auth-field { flex: 1; min-width: 0; }
.bp-auth-label { display: block; margin-bottom: .35rem; color: var(--bp-text); font-size: .8rem; font-weight: 600; }
.bp-auth-required { color: #E14B4B; }
.bp-auth-optional { color: var(--bp-muted); font-size: .68rem; font-weight: 400; }
.bp-auth-input-box { position: relative; display: flex; align-items: center; }
.bp-auth-ico {
    position: absolute; z-index: 2; top: 50%; transform: translateY(-50%);
    color: var(--bp-muted); font-size: 1rem; pointer-events: none;
}
[dir="rtl"] .bp-auth-ico { right: 13px; }
.bp-auth-inp {
    width: 100%; padding: 10px 42px; border: 1.5px solid var(--bp-border); border-radius: var(--bp-r);
    outline: none; color: var(--bp-ink); background: var(--bp-surface); font-size: .855rem; direction: ltr;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
[dir="rtl"] .bp-auth-inp { text-align: right; }
textarea.bp-auth-inp { min-height: 104px; resize: vertical; line-height: 1.75; direction: rtl; }
select.bp-auth-inp { cursor: pointer; appearance: auto; direction: rtl; }
input[type="number"].bp-auth-inp { direction: ltr; text-align: right; }
.bp-auth-inp::placeholder { color: #b8c4d0; direction: ltr; text-align: left; }
.bp-auth-inp:focus { border-color: var(--bp-blue); background: #fff; box-shadow: 0 0 0 3.5px var(--bp-tint-blue); }
.bp-auth-inp.is-invalid { border-color: #E14B4B !important; }
.bp-auth-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,75,75,.12) !important; }
.bp-auth-error { display: block; margin-top: 4px; padding-right: 2px; color: #E14B4B; font-size: .77rem; }
.bp-auth-submit { width: 100%; justify-content: center; margin-bottom: .9rem; margin-top: .4rem; }
.bp-auth-back { margin: 0; text-align: center; color: var(--bp-muted); font-size: .82rem; }
.bp-auth-back a { color: var(--bp-blue); font-weight: 700; text-decoration: none; }
.bp-auth-back a:hover { color: var(--bp-blue-d); text-decoration: underline; }
.bp-auth-showcase {
    flex: 1; display: flex; align-items: center; justify-content: center; padding: 3rem;
    position: relative; overflow: hidden; background: var(--bp-navy);
    background-image: linear-gradient(rgba(10,20,40,.5), rgba(10,20,40,.5)), url('{{ asset('images/post-project-bg.jpg') }}');
    background-position: center; background-size: cover;
}
.bp-auth-showcase-inner { width: 100%; max-width: 460px; position: relative; z-index: 2; }
.bp-auth-sc-brand { margin-bottom: 1.8rem; }
.bp-auth-sc-brand a { color: #fff; font-size: 1.9rem; font-weight: 900; letter-spacing: -.5px; text-decoration: none; }
.bp-auth-sc-brand span { color: var(--bp-blue-l); }
.bp-auth-sc-kicker { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1rem; color: var(--bp-blue-l); font-size: .8rem; font-weight: 700; }
.bp-auth-sc-title { margin: 0 0 .7rem; color: #fff; font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 900; line-height: 1.5; }
.bp-auth-sc-title .hl { color: var(--bp-blue-l); }
.bp-auth-sc-sub { max-width: 380px; margin: 0 0 2rem; color: rgba(255,255,255,.72); font-size: .88rem; line-height: 1.75; }
.bp-auth-benefits { display: flex; gap: 1.2rem; flex-wrap: wrap; margin-bottom: 2rem; }
.bp-auth-benefit {
    flex: 1; min-width: 90px; padding: .9rem 1.2rem; border: 1px solid rgba(255,255,255,.15);
    border-radius: var(--bp-r-lg); color: rgba(255,255,255,.72); background: rgba(255,255,255,.08);
    backdrop-filter: blur(10px); font-size: .72rem; line-height: 1.55; text-align: center;
}
.bp-auth-benefit i { display: block; margin-bottom: .35rem; color: var(--bp-blue-l); font-size: 1.3rem; }
@media (max-width: 900px) {
    .bp-auth-form { width: 100%; padding: 2rem 1.8rem; }
}
@media (max-width: 480px) {
    .bp-auth-form { padding: 1.6rem 1.1rem; }
    .bp-auth-heading { font-size: 1.2rem; }
    .bp-auth-field-row { flex-direction: column; gap: 0; }
}
</style>
@include('auth.partials.mobile-visual-panel')
@endsection

@section('content')
<main class="bp-auth-wrap">
    <section class="bp-auth-form" aria-labelledby="post-project-title">
        <div class="bp-auth-form-inner">
            <div class="bp-auth-logo">
                <a href="{{ route('root') }}" aria-label="EngPis — صفحه اصلی">
                    <div class="bp-auth-logo-word"><span class="a">Eng</span>Pis</div>
                </a>
            </div>

            <div class="bp-auth-step"><i class="ri-list-check-2"></i> مرحله ۱ از ۲ — اطلاعات پروژه</div>
            <h1 class="bp-auth-heading" id="post-project-title">پروژه‌تان را ثبت کنید</h1>
            <p class="bp-auth-subhead">اطلاعات اولیه را وارد کنید؛ پس از ساخت حساب، پروژه شما با همین اطلاعات ایجاد می‌شود.</p>

            @if ($errors->any())
                <div class="bp-auth-alert-err" role="alert">
                    لطفاً موارد مشخص‌شده در فرم را بررسی کنید.
                </div>
            @endif

            <form action="{{ route('guest.project.store') }}" method="POST">
                @csrf

                <div class="bp-auth-field">
                    <label class="bp-auth-label" for="title">عنوان پروژه <span class="bp-auth-required">*</span></label>
                    <div class="bp-auth-input-box">
                        <i class="ri-briefcase-line bp-auth-ico"></i>
                        <input type="text" id="title" name="title" class="bp-auth-inp @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="مثلاً: شبیه‌سازی اجزاء محدود در ANSYS"
                            required maxlength="191" autocomplete="off" aria-describedby="title-error">
                    </div>
                    @error('title')<span class="bp-auth-error" id="title-error">{{ $message }}</span>@enderror
                </div>

                <div class="bp-auth-field">
                    <label class="bp-auth-label" for="description">توضیحات <span class="bp-auth-required">*</span></label>
                    <div class="bp-auth-input-box">
                        <i class="ri-file-text-line bp-auth-ico" style="top:15px;"></i>
                        <textarea id="description" name="description" class="bp-auth-inp @error('description') is-invalid @enderror"
                            placeholder="شرح مختصری از پروژه، اهداف و نیازمندی‌ها..." required
                            aria-describedby="description-error">{{ old('description') }}</textarea>
                    </div>
                    @error('description')<span class="bp-auth-error" id="description-error">{{ $message }}</span>@enderror
                </div>

                <div class="bp-auth-field">
                    <label class="bp-auth-label" for="work_type">نوع همکاری <span class="bp-auth-required">*</span></label>
                    <div class="bp-auth-input-box">
                        <i class="ri-map-pin-line bp-auth-ico"></i>
                        <select id="work_type" name="work_type" class="bp-auth-inp @error('work_type') is-invalid @enderror" required aria-describedby="work-type-error">
                            <option value="">انتخاب کنید</option>
                            <option value="remote" {{ old('work_type') === 'remote' ? 'selected' : '' }}>دورکاری</option>
                            <option value="onsite" {{ old('work_type') === 'onsite' ? 'selected' : '' }}>حضوری</option>
                            <option value="hybrid" {{ old('work_type') === 'hybrid' ? 'selected' : '' }}>ترکیبی</option>
                        </select>
                    </div>
                    @error('work_type')<span class="bp-auth-error" id="work-type-error">{{ $message }}</span>@enderror
                </div>

                <div class="bp-auth-field-row">
                    <div class="bp-auth-field">
                        <label class="bp-auth-label" for="budget_min">حداقل بودجه (تومان) <span class="bp-auth-optional">اختیاری</span></label>
                        <div class="bp-auth-input-box">
                            <i class="ri-money-dollar-circle-line bp-auth-ico"></i>
                            <input type="number" id="budget_min" name="budget_min" class="bp-auth-inp @error('budget_min') is-invalid @enderror"
                                value="{{ old('budget_min') }}" min="0" placeholder="۰" inputmode="numeric" aria-describedby="budget-min-error">
                        </div>
                        @error('budget_min')<span class="bp-auth-error" id="budget-min-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="bp-auth-field">
                        <label class="bp-auth-label" for="budget_max">حداکثر بودجه (تومان) <span class="bp-auth-optional">اختیاری</span></label>
                        <div class="bp-auth-input-box">
                            <i class="ri-money-dollar-circle-line bp-auth-ico"></i>
                            <input type="number" id="budget_max" name="budget_max" class="bp-auth-inp @error('budget_max') is-invalid @enderror"
                                value="{{ old('budget_max') }}" min="0" placeholder="۰" inputmode="numeric" aria-describedby="budget-max-error">
                        </div>
                        @error('budget_max')<span class="bp-auth-error" id="budget-max-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <button type="submit" class="bp-btn bp-btn--primary bp-auth-submit">
                    ادامه و ثبت‌نام <i class="ri-arrow-left-line"></i>
                </button>
            </form>

            <p class="bp-auth-back"><a href="{{ route('register') }}"><i class="ri-arrow-right-line"></i> بازگشت به ثبت‌نام معمولی</a></p>
        </div>
    </section>

    <aside class="bp-auth-showcase" aria-label="مزایای ثبت پروژه در EngPis">
        <div class="bp-auth-showcase-inner">
            <div class="bp-auth-sc-brand">
                <a href="{{ route('root') }}"><span>Eng</span>Pis</a>
            </div>
            <div class="bp-auth-sc-kicker"><i class="ri-flashlight-line"></i> شروع سریع و رایگان</div>
            <h2 class="bp-auth-sc-title">سریع‌ترین راه برای یافتن<br><span class="hl">متخصص مهندسی</span></h2>
            <p class="bp-auth-sc-sub">نیاز پروژه را یک‌بار ثبت کنید و پس از تکمیل عضویت، مسیر همکاری با متخصصان EngPis را آغاز کنید.</p>
            <div class="bp-auth-benefits">
                <div class="bp-auth-benefit"><i class="ri-file-list-3-line"></i><span>اطلاعات پروژه پس از ثبت‌نام حفظ می‌شود</span></div>
                <div class="bp-auth-benefit"><i class="ri-user-search-line"></i><span>دسترسی به متخصصان حوزه‌های مختلف مهندسی</span></div>
                <div class="bp-auth-benefit"><i class="ri-shield-check-line"></i><span>فرآیند شفاف و امن از ثبت پروژه تا همکاری</span></div>
            </div>
        </div>
    </aside>
</main>
@endsection
