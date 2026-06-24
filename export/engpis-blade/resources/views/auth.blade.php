@extends('layouts.base')
@section('title', "EngPis — ورود / ثبت‌نام")

@push('head')
@verbatim
<style>
  body { background: var(--bp-surface); }
  .auth { display: grid; grid-template-columns: 1.05fr .95fr; min-height: 100vh; }
  @media (max-width: 860px) { .auth { grid-template-columns: 1fr; } .auth-aside { display: none; } }

  /* brand panel */
  .auth-aside { position: relative; background: var(--bp-navy); color: #fff; overflow: hidden; padding: 56px 52px; display: flex; flex-direction: column; }
  .auth-aside .grid-bg { position: absolute; inset: 0; opacity: .6; }
  .auth-aside .glow { position: absolute; border-radius: 50%; filter: blur(10px); }
  .auth-aside .g1 { top: -100px; inset-inline-end: -60px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(31,111,235,.4), transparent 70%); }
  .auth-aside .g2 { bottom: -120px; inset-inline-start: -40px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,184,169,.25), transparent 70%); }
  .auth-aside .brand { position: relative; font-size: 1.7rem; font-weight: 900; color: #fff; }
  .auth-aside .brand .a { color: var(--bp-blue-l); }
  .auth-aside .mid { position: relative; margin-top: auto; }
  .auth-aside h1 { position: relative; color: #fff; font-size: 2rem; font-weight: 900; line-height: 1.4; margin-bottom: 16px; }
  .auth-aside .lead { position: relative; color: rgba(255,255,255,.72); font-size: 1.02rem; max-width: 420px; margin-bottom: 32px; }
  .auth-feat { position: relative; display: flex; flex-direction: column; gap: 14px; margin-bottom: auto; }
  .auth-feat .f { display: flex; align-items: center; gap: 12px; }
  .auth-feat .f .ic { width: 42px; height: 42px; border-radius: var(--bp-r); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--bp-blue-l); flex: none; }
  .auth-feat .f .t { font-weight: 700; font-size: .95rem; }
  .auth-feat .f .s { font-size: .8rem; color: rgba(255,255,255,.55); }
  .auth-aside .foot { position: relative; color: rgba(255,255,255,.4); font-size: .82rem; margin-top: 36px; }

  /* form side */
  .auth-main { display: flex; align-items: center; justify-content: center; padding: 48px 32px; }
  .auth-box { width: 100%; max-width: 400px; }
  .auth-box .top-mobile { display: none; }
  @media (max-width: 860px) { .auth-box .top-mobile { display: block; text-align: center; margin-bottom: 28px; font-size: 1.6rem; font-weight: 900; color: var(--bp-ink); } .auth-box .top-mobile .a { color: var(--bp-blue); } }
  .auth-box h2 { font-size: 1.5rem; font-weight: 900; margin-bottom: 6px; }
  .auth-box .sub { color: var(--bp-muted); margin-bottom: 26px; }

  .seg.full { display: flex; width: 100%; margin-bottom: 24px; }
  .seg.full button { flex: 1; justify-content: center; }

  .pane { display: none; }
  .pane.on { display: block; }

  .auth-foot { text-align: center; margin-top: 22px; font-size: .9rem; color: var(--bp-muted); }
  .auth-foot a { color: var(--bp-blue); font-weight: 700; cursor: pointer; }
  .divider { display: flex; align-items: center; gap: 12px; color: var(--bp-muted); font-size: .82rem; margin: 22px 0; }
  .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--bp-border); }
  .check-row { display: flex; align-items: center; justify-content: space-between; margin-top: -6px; }
  .chk { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: var(--bp-text); cursor: pointer; }
  .chk input { width: 16px; height: 16px; accent-color: var(--bp-blue); }
  .forgot { font-size: .85rem; color: var(--bp-blue); font-weight: 600; cursor: pointer; }
</style>
@endverbatim
@endpush

@section('content')
@verbatim
<div class="auth">
  <aside class="auth-aside">
    <div class="grid-bg bp-grid"></div>
    <div class="glow g1"></div>
    <div class="glow g2"></div>
    <div class="brand"><span class="a">Eng</span>Pis</div>
    <div class="mid">
      <h1>پروژه‌های مهندسی،<br>به متخصص واقعی سپرده می‌شود.</h1>
      <p class="lead">به بزرگ‌ترین مارکت‌پلیس تخصصی پروژه‌های فنی و مهندسی ایران بپیوند.</p>
    </div>
    <div class="auth-feat">
      <div class="f"><div class="ic"><i class="ri-cpu-line"></i></div><div><div class="t">تطابق هوشمند</div><div class="s">پروژه و متخصص بر پایه مهارت و ابزار</div></div></div>
      <div class="f"><div class="ic"><i class="ri-shield-keyhole-line"></i></div><div><div class="t">پرداخت امن</div><div class="s">وجه تا تأیید نهایی نزد EngPis می‌ماند</div></div></div>
      <div class="f"><div class="ic"><i class="ri-verified-badge-line"></i></div><div><div class="t">متخصصان تأیید شده</div><div class="s">احراز هویت و سابقه‌ی همه‌ی متخصص‌ها</div></div></div>
    </div>
    <div class="foot">© ۱۴۰۴ EngPis — ساخته‌شده برای جامعه مهندسی ایران</div>
  </aside>

  <main class="auth-main">
    <div class="auth-box">
      <div class="top-mobile"><span class="a">Eng</span>Pis</div>

      <div class="seg full">
        <button class="active" data-pane="login" onclick="switchPane('login', this)">ورود</button>
        <button data-pane="register" onclick="switchPane('register', this)">ثبت‌نام</button>
      </div>

      <!-- LOGIN -->
      <div class="pane on" id="login">
        <h2>خوش آمدی 👋</h2>
        <p class="sub">برای ادامه وارد حساب کاربری‌ات شو.</p>
        <div class="field">
          <label>ایمیل یا شماره موبایل</label>
          <div class="inp-icon"><i class="ri-user-line"></i><input class="inp" type="text" placeholder="example@mail.com"></div>
        </div>
        <div class="field">
          <label>رمز عبور</label>
          <div class="inp-icon"><i class="ri-lock-2-line"></i><input class="inp" type="password" placeholder="••••••••"></div>
        </div>
        <div class="check-row">
          <label class="chk"><input type="checkbox" checked>مرا به خاطر بسپار</label>
          <span class="forgot">فراموشی رمز؟</span>
        </div>
        <button class="bp-btn bp-btn--primary" style="width:100%; justify-content:center; margin-top:22px"><i class="ri-login-circle-line"></i>ورود به حساب</button>
        <div class="auth-foot">حساب نداری؟ <a onclick="switchPane('register', document.querySelector('[data-pane=register]'))">همین حالا ثبت‌نام کن</a></div>
      </div>

      <!-- REGISTER -->
      <div class="pane" id="register">
        <h2>ساخت حساب جدید</h2>
        <p class="sub">در کمتر از یک دقیقه عضو شو.</p>
        <div class="field">
          <label>نام و نام خانوادگی</label>
          <div class="inp-icon"><i class="ri-user-line"></i><input class="inp" type="text" placeholder="مثلاً: علی محمدی"></div>
        </div>
        <div class="field">
          <label>ایمیل</label>
          <div class="inp-icon"><i class="ri-mail-line"></i><input class="inp" type="email" placeholder="example@mail.com"></div>
        </div>
        <div class="field">
          <label>رمز عبور</label>
          <div class="inp-icon"><i class="ri-lock-2-line"></i><input class="inp" type="password" placeholder="حداقل ۸ کاراکتر"></div>
        </div>
        <label class="chk" style="margin-bottom:4px"><input type="checkbox" checked><span>با <a class="forgot" style="text-decoration:none">قوانین و مقررات</a> موافقم</span></label>
        <button class="bp-btn bp-btn--primary" style="width:100%; justify-content:center; margin-top:22px"><i class="ri-user-add-line"></i>ثبت‌نام رایگان</button>
        <div class="auth-foot">حساب داری؟ <a onclick="switchPane('login', document.querySelector('[data-pane=login]'))">وارد شو</a></div>
      </div>

    </div>
  </main>
</div>
@endverbatim
@endsection

@push('scripts')
@verbatim
<script>
  function switchPane(name, btn) {
    document.querySelectorAll('.seg.full button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.pane').forEach(p => p.classList.remove('on'));
    document.getElementById(name).classList.add('on');
  }
</script>
@endverbatim
@endpush
