@extends('layouts.master-without-nav')

@section('title', 'ثبت پروژه — قبل از عضویت')

@section('body')
<body class="bp-project-page">
@endsection

@section('css')
<style>
html, body { min-height: 100%; margin: 0; background: #fff !important; color: var(--bp-text) !important; }
.bp-project-page { overflow-x: hidden; }
.bp-auth-wrap { display: flex; min-height: 100vh; direction: rtl; }
.bp-auth-form {
    width: 48%; flex-shrink: 0; display: flex; flex-direction: column; justify-content: center;
    padding: 2rem clamp(1.5rem, 4vw, 4rem); background: #fff; border-left: 1px solid var(--bp-hair);
    box-shadow: -12px 0 40px rgba(14,27,44,.05); position: relative; z-index: 2;
}
.bp-auth-form-inner { width: 100%; max-width: 620px; margin-inline: auto; padding-block: 1rem; }
.bp-auth-logo { margin-bottom: 1.35rem; }
.bp-auth-logo a { display: inline-block; text-decoration: none; }
.bp-auth-logo-word { color: var(--bp-ink); font-size: 1.65rem; font-weight: 900; letter-spacing: -.5px; line-height: 1; }
.bp-auth-logo-word .a { color: var(--bp-blue); }
.bp-auth-step {
    display: inline-flex; align-items: center; gap: .4rem; margin-bottom: .8rem; padding: .35rem .7rem;
    border-radius: 999px; color: var(--bp-blue); background: var(--bp-tint-blue); font-size: .72rem; font-weight: 700;
}
.bp-auth-heading { margin: 0 0 .35rem; color: var(--bp-ink); font-size: 1.45rem; font-weight: 800; }
.bp-auth-subhead { margin: 0 0 1.35rem; color: var(--bp-muted); font-size: .84rem; line-height: 1.8; }
.bp-auth-alert-err {
    margin-bottom: 1rem; padding: 10px 14px; border: 1px solid #E14B4B55; border-radius: var(--bp-r);
    color: #a32f2f; background: var(--bp-tint-red); font-size: .8rem;
}
.bp-auth-field { margin-bottom: .9rem; }
.bp-auth-field-row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; }
.bp-auth-label { display: block; margin-bottom: .35rem; color: var(--bp-text); font-size: .8rem; font-weight: 600; }
.bp-auth-required { color: #E14B4B; }
.bp-auth-optional { color: var(--bp-muted); font-size: .68rem; font-weight: 400; }
.bp-auth-input-box { position: relative; display: flex; align-items: center; }
.bp-auth-ico { position: absolute; right: 13px; z-index: 2; color: var(--bp-muted); font-size: 1rem; pointer-events: none; }
.bp-auth-inp {
    width: 100%; min-height: 45px; padding: 10px 42px 10px 12px; border: 1.5px solid var(--bp-border);
    border-radius: var(--bp-r); outline: none; color: var(--bp-ink); background: var(--bp-surface);
    font: inherit; font-size: .875rem; text-align: right; direction: rtl;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
textarea.bp-auth-inp { min-height: 105px; resize: vertical; line-height: 1.75; }
select.bp-auth-inp { cursor: pointer; appearance: auto; }
input[type="number"].bp-auth-inp { direction: ltr; text-align: right; }
.bp-auth-inp::placeholder { color: #aebbc8; }
.bp-auth-inp:focus { border-color: var(--bp-blue); background: #fff; box-shadow: 0 0 0 3.5px var(--bp-tint-blue); }
.bp-auth-inp.is-invalid { border-color: #E14B4B; }
.bp-auth-inp.is-invalid:focus { box-shadow: 0 0 0 3px rgba(225,75,75,.12); }
.bp-auth-error { display: block; margin-top: 4px; padding-right: 2px; color: #E14B4B; font-size: .74rem; }
.bp-auth-submit { width: 100%; justify-content: center; margin-top: .25rem; }
.bp-auth-back { margin: 1rem 0 0; text-align: center; color: var(--bp-muted); font-size: .8rem; }
.bp-auth-back a { color: var(--bp-blue); font-weight: 700; text-decoration: none; }
.bp-auth-back a:hover { color: var(--bp-blue-d); text-decoration: underline; }
.bp-auth-showcase {
    flex: 1; display: flex; align-items: center; justify-content: center; padding: 3rem; position: relative; overflow: hidden;
    background-image: linear-gradient(rgba(10,20,40,.62), rgba(10,20,40,.72)), url('{{ asset('images/login-bg.jpg') }}');
    background-position: center; background-size: cover;
}
.bp-auth-showcase-inner { width: 100%; max-width: 480px; position: relative; z-index: 2; }
.bp-auth-sc-kicker { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1rem; color: #8db8ff; font-size: .8rem; font-weight: 700; }
.bp-auth-sc-title { margin: 0 0 .7rem; color: #fff; font-size: clamp(1.5rem, 2.5vw, 2.15rem); font-weight: 900; line-height: 1.55; }
.bp-auth-sc-title span { color: #8db8ff; }
.bp-auth-sc-sub { max-width: 410px; margin: 0 0 1.8rem; color: rgba(255,255,255,.76); font-size: .88rem; line-height: 1.9; }
.bp-auth-benefits { display: grid; gap: .7rem; }
.bp-auth-benefit {
    display: flex; align-items: center; gap: .75rem; padding: .85rem 1rem; border: 1px solid rgba(255,255,255,.14);
    border-radius: var(--bp-r-lg); color: rgba(255,255,255,.88); background: rgba(255,255,255,.07);
    backdrop-filter: blur(8px); font-size: .8rem;
}
.bp-auth-benefit i { display: grid; width: 32px; height: 32px; place-items: center; flex-shrink: 0; border-radius: var(--bp-r); color: #8db8ff; background: rgba(31,111,235,.2); font-size: 1rem; }
@media (max-width: 1050px) { .bp-auth-form { width: 55%; } }
@media (max-width: 900px) {
    .bp-auth-form { width: 100%; min-height: 100vh; padding: 1.5rem clamp(1.2rem, 5vw, 2.5rem); border-left: 0; box-shadow: none; }
    .bp-auth-showcase { display: none; }
}
@media (max-width: 560px) {
    .bp-auth-form { justify-content: flex-start; padding-block: 1.4rem; }
    .bp-auth-form-inner { padding-block: 0; }
    .bp-auth-field-row { grid-template-columns: 1fr; gap: 0; }
    .bp-auth-heading { font-size: 1.25rem; }
    .bp-auth-logo { margin-bottom: 1.1rem; }
}
</style>
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
            <div class="bp-auth-sc-kicker"><i class="ri-flashlight-line"></i> شروع سریع و رایگان</div>
            <h2 class="bp-auth-sc-title">سریع‌ترین راه برای یافتن<br><span>متخصص مهندسی</span></h2>
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
