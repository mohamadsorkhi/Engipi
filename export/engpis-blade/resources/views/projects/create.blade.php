@extends('layouts.base')
@section('title', "EngPis — ثبت پروژه")

@push('head')
@verbatim
<style>
  body { background: var(--bp-surface); }
  .app { display: flex; min-height: 100vh; }

  /* sidebar (shared with dashboard) */
  .sidebar { width: 256px; flex: none; background: var(--bp-navy); color: rgba(255,255,255,.62); position: sticky; top: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
  .sidebar .grid-bg { position: absolute; inset: 0; opacity: .5; pointer-events: none; }
  .sb-brand { position: relative; padding: 22px 24px; font-size: 1.5rem; font-weight: 900; color: #fff; }
  .sb-brand .a { color: var(--bp-blue-l); }
  .sb-sec { position: relative; padding: 16px 24px 8px; font-size: .68rem; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.34); font-weight: 700; }
  .sb-nav { position: relative; list-style: none; margin: 0; padding: 4px 14px; display: flex; flex-direction: column; gap: 3px; }
  .sb-item { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: var(--bp-r); color: rgba(255,255,255,.66); font-size: .93rem; font-weight: 500; cursor: pointer; transition: all .2s; }
  .sb-item i { font-size: 1.2rem; }
  .sb-item:hover { background: rgba(255,255,255,.07); color: #fff; }
  .sb-item.active { background: var(--bp-blue); color: #fff; box-shadow: var(--bp-sh-blue); }
  .sb-badge { margin-inline-start: auto; background: var(--bp-teal); color: #fff; font-size: .7rem; font-weight: 700; border-radius: var(--bp-r); padding: 2px 8px; font-family: ui-monospace, monospace; }

  .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
  .topbar { height: 66px; background: #fff; border-bottom: 1px solid var(--bp-border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; position: sticky; top: 0; z-index: 20; }
  .topbar .pt { font-size: 1.1rem; font-weight: 800; color: var(--bp-ink); display: flex; align-items: center; gap: 9px; }
  .topbar .pt .crumb { color: var(--bp-muted); font-weight: 500; font-size: .92rem; }
  .tb-act { display: flex; align-items: center; gap: 14px; }
  .iconbtn { width: 42px; height: 42px; border-radius: var(--bp-r); border: 1px solid var(--bp-border); background: #fff; color: var(--bp-muted); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; position: relative; transition: all .2s; }
  .iconbtn:hover { border-color: var(--bp-blue); color: var(--bp-blue); background: var(--bp-tint-blue); }
  .iconbtn .dot { position: absolute; top: 9px; inset-inline-end: 10px; width: 8px; height: 8px; border-radius: 50%; background: var(--bp-c-red); border: 2px solid #fff; }
  .tb-user { display: flex; align-items: center; gap: 10px; cursor: pointer; padding-inline-start: 14px; border-inline-start: 1px solid var(--bp-border); }
  .tb-user .av { width: 40px; height: 40px; border-radius: var(--bp-r); background: var(--bp-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; }
  .tb-user .nm { font-size: .9rem; font-weight: 700; color: var(--bp-ink); }
  .tb-user .rl { font-size: .74rem; color: var(--bp-muted); }
  .content { padding: 28px; }

  /* form layout */
  .form-wrap { max-width: 860px; margin: 0 auto; }
  .form-head { margin-bottom: 22px; }
  .form-head h2 { font-size: 1.5rem; font-weight: 900; margin-bottom: 6px; }
  .form-head p { color: var(--bp-muted); }

  /* stepper */
  .stepper { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
  .stp { display: flex; align-items: center; gap: 9px; }
  .stp .b { width: 30px; height: 30px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; background: #fff; border: 1px solid var(--bp-border); color: var(--bp-muted); font-family: ui-monospace, monospace; }
  .stp.done .b { background: var(--bp-blue); color: #fff; border-color: var(--bp-blue); }
  .stp .lab { font-size: .9rem; font-weight: 600; color: var(--bp-muted); }
  .stp.done .lab { color: var(--bp-ink); }
  .stp .line { width: 36px; height: 2px; background: var(--bp-border); border-radius: 2px; }

  .fcard { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
  .fcard .fh { padding: 16px 24px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; gap: 10px; }
  .fcard .fh i { width: 34px; height: 34px; border-radius: var(--bp-r); background: var(--bp-tint-blue); color: var(--bp-blue); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
  .fcard .fh h5 { font-size: 1rem; }
  .fcard .fb { padding: 24px; }

  .field { margin-bottom: 20px; }
  .field:last-child { margin-bottom: 0; }
  .field > label { display: block; font-size: .88rem; font-weight: 700; color: var(--bp-ink); margin-bottom: 8px; }
  .field .req { color: var(--bp-c-red); }
  .field .hint { font-size: .76rem; color: var(--bp-muted); margin-top: 6px; }
  .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  @media (max-width: 640px) { .row2 { grid-template-columns: 1fr; } }

  .inp, .sel, .ta { width: 100%; box-sizing: border-box; font-family: inherit; font-size: .94rem; color: var(--bp-ink); background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r); padding: 12px 14px; transition: border-color .2s, box-shadow .2s; }
  .inp::placeholder, .ta::placeholder { color: #aab3c0; }
  .inp:focus, .sel:focus, .ta:focus { outline: none; border-color: var(--bp-blue); box-shadow: 0 0 0 3px var(--bp-tint-blue); }
  .ta { resize: vertical; min-height: 110px; line-height: 1.8; }
  .sel { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%235A6B80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: left 14px center; padding-inline-start: 40px; cursor: pointer; }
  .inp-icon { position: relative; }
  .inp-icon i { position: absolute; inset-inline-start: 14px; top: 50%; transform: translateY(-50%); color: var(--bp-muted); font-size: 1.15rem; }
  .inp-icon .inp { padding-inline-start: 42px; }

  /* work-type radio cards */
  .wt-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
  @media (max-width: 640px) { .wt-grid { grid-template-columns: 1fr; } }
  .wt { border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 16px; cursor: pointer; transition: all .2s; display: flex; gap: 11px; align-items: flex-start; position: relative; }
  .wt:hover { border-color: var(--bp-blue); }
  .wt.sel { border-color: var(--bp-blue); background: var(--bp-tint-blue); box-shadow: 0 0 0 1px var(--bp-blue) inset; }
  .wt .ic { width: 38px; height: 38px; border-radius: var(--bp-r); background: var(--bp-surface); color: var(--bp-muted); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex: none; transition: all .2s; }
  .wt.sel .ic { background: var(--bp-blue); color: #fff; }
  .wt .t { font-weight: 700; font-size: .92rem; color: var(--bp-ink); }
  .wt .s { font-size: .76rem; color: var(--bp-muted); }
  .wt .check { position: absolute; top: 12px; inset-inline-end: 12px; color: var(--bp-blue); font-size: 1.1rem; opacity: 0; transition: opacity .2s; }
  .wt.sel .check { opacity: 1; }

  /* chips */
  .chips { display: flex; flex-wrap: wrap; gap: 8px; padding: 8px; border: 1px solid var(--bp-border); border-radius: var(--bp-r); min-height: 50px; align-items: center; }
  .chips:focus-within { border-color: var(--bp-blue); box-shadow: 0 0 0 3px var(--bp-tint-blue); }
  .chip { display: inline-flex; align-items: center; gap: 6px; background: var(--bp-tint-blue); color: var(--bp-blue); font-weight: 700; font-size: .82rem; padding: 6px 11px; border-radius: var(--bp-r); }
  .chip i { cursor: pointer; font-size: .95rem; opacity: .7; }
  .chip i:hover { opacity: 1; }
  .chip-input { border: none; outline: none; font-family: inherit; font-size: .9rem; padding: 6px; flex: 1; min-width: 120px; background: transparent; color: var(--bp-ink); }
  .suggest { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 10px; }
  .suggest button { font-family: inherit; font-size: .8rem; font-weight: 600; color: var(--bp-muted); background: var(--bp-surface); border: 1px dashed var(--bp-border); border-radius: var(--bp-r); padding: 5px 11px; cursor: pointer; transition: all .2s; }
  .suggest button:hover { border-style: solid; border-color: var(--bp-blue); color: var(--bp-blue); }

  /* upload */
  .drop { border: 1.5px dashed var(--bp-border); border-radius: var(--bp-r-lg); padding: 30px; text-align: center; color: var(--bp-muted); cursor: pointer; transition: all .2s; }
  .drop:hover { border-color: var(--bp-blue); background: var(--bp-tint-blue); color: var(--bp-blue); }
  .drop i { font-size: 2rem; }
  .drop .big { font-weight: 700; color: var(--bp-ink); margin-top: 8px; }
  .drop .sm { font-size: .8rem; margin-top: 3px; }

  .form-foot { display: flex; align-items: center; justify-content: space-between; margin-top: 22px; gap: 12px; flex-wrap: wrap; }
  .form-foot .note { font-size: .82rem; color: var(--bp-muted); display: flex; align-items: center; gap: 6px; }
  .form-foot .acts { display: flex; gap: 10px; }
</style>
@endverbatim
@endpush

@section('content')
@verbatim
<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="grid-bg bp-grid"></div>
    <div class="sb-brand"><span class="a">Eng</span>Pis</div>
    <div class="sb-sec">منوی اصلی</div>
    <ul class="sb-nav">
      <li><a class="sb-item" href="/dashboard"><i class="ri-dashboard-2-line"></i><span>داشبورد</span></a></li>
      <li><a class="sb-item" href="/dashboard?v=matched"><i class="ri-lightbulb-flash-line"></i><span>پروژه‌های پیشنهادی</span><span class="sb-badge">۴</span></a></li>
      <li><a class="sb-item active" href="/projects/show"><i class="ri-briefcase-line"></i><span>پروژه‌های من</span></a></li>
      <li><a class="sb-item" href="/requests"><i class="ri-inbox-line"></i><span>درخواست‌ها</span></a></li>
      <li><a class="sb-item" href="/profile"><i class="ri-star-line"></i><span>مهارت‌ها</span></a></li>
      <li><a class="sb-item" href="/tickets"><i class="ri-customer-service-2-line"></i><span>تیکت‌ها</span></a></li>
    </ul>
  </aside>
  <div class="main">
    <header class="topbar">
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button><span class="crumb">پروژه‌های من /</span> ثبت پروژه جدید</div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <div class="tb-user"><div class="av">ع</div><div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div><i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i></div>
      </div>
    </header>
    <div class="content">
      <div class="form-wrap">
        <div class="form-head">
          <h2>ثبت پروژه جدید</h2>
          <p>پروژه‌ات را دقیق توصیف کن تا بهترین متخصص‌ها با آن تطبیق یابند.</p>
        </div>

        <div class="stepper">
          <div class="stp done"><span class="b">۱</span><span class="lab">جزئیات پروژه</span></div>
          <div class="line"></div>
          <div class="stp"><span class="b">۲</span><span class="lab">بودجه و زمان</span></div>
          <div class="line"></div>
          <div class="stp"><span class="b">۳</span><span class="lab">بازبینی</span></div>
        </div>

        <div class="fcard" style="margin-bottom:20px">
          <div class="fh"><i class="ri-file-text-line"></i><h5>اطلاعات پروژه</h5></div>
          <div class="fb">
            <div class="field">
              <label>عنوان پروژه <span class="req">*</span></label>
              <input class="inp" type="text" placeholder="مثلاً: شبیه‌سازی سیستم کنترل سرعت موتور با MATLAB">
              <div class="hint">یک عنوان روشن و فنی بنویس تا متخصص‌ها سریع موضوع را بفهمند.</div>
            </div>
            <div class="row2">
              <div class="field">
                <label>حوزه تخصصی <span class="req">*</span></label>
                <select class="sel">
                  <option>مهندسی برق</option><option>مهندسی مکانیک</option><option>مهندسی عمران</option>
                  <option>مهندسی کامپیوتر</option><option>مهندسی شیمی</option><option>مهندسی صنایع</option>
                </select>
              </div>
              <div class="field">
                <label>سطح پروژه</label>
                <select class="sel"><option>مقدماتی</option><option>متوسط</option><option>پیشرفته / تخصصی</option></select>
              </div>
            </div>
            <div class="field">
              <label>توضیحات پروژه <span class="req">*</span></label>
              <textarea class="ta" placeholder="شرح کامل خواسته‌ها، خروجی مورد انتظار، استانداردها و محدودیت‌های فنی پروژه..."></textarea>
            </div>
            <div class="field">
              <label>نوع همکاری</label>
              <div class="wt-grid">
                <div class="wt sel" data-wt><i class="ri-check-line check"></i><div class="ic"><i class="ri-home-wifi-line"></i></div><div><div class="t">دورکاری</div><div class="s">انجام از راه دور</div></div></div>
                <div class="wt" data-wt><i class="ri-check-line check"></i><div class="ic"><i class="ri-building-line"></i></div><div><div class="t">حضوری</div><div class="s">در محل کارفرما</div></div></div>
                <div class="wt" data-wt><i class="ri-check-line check"></i><div class="ic"><i class="ri-contrast-2-line"></i></div><div><div class="t">ترکیبی</div><div class="s">حضوری + دورکاری</div></div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="fcard" style="margin-bottom:20px">
          <div class="fh"><i class="ri-tools-line"></i><h5>مهارت‌ها و ابزار مورد نیاز</h5></div>
          <div class="fb">
            <div class="field" style="margin-bottom:0">
              <label>ابزار و نرم‌افزارها</label>
              <div class="chips" id="chips">
                <span class="chip">MATLAB <i class="ri-close-line" data-rm></i></span>
                <span class="chip">ANSYS <i class="ri-close-line" data-rm></i></span>
                <input class="chip-input" id="chipInput" placeholder="افزودن مهارت و Enter...">
              </div>
              <div class="suggest" id="suggest">
                <button data-add>Python</button><button data-add>SolidWorks</button>
                <button data-add>ETAP</button><button data-add>COMSOL</button><button data-add>AutoCAD</button>
              </div>
            </div>
          </div>
        </div>

        <div class="fcard">
          <div class="fh"><i class="ri-attachment-2"></i><h5>فایل‌های ضمیمه</h5></div>
          <div class="fb">
            <div class="drop">
              <i class="ri-upload-cloud-2-line"></i>
              <div class="big">فایل‌ها را اینجا رها کن یا کلیک کن</div>
              <div class="sm">PDF، Word، تصویر یا فایل پروژه — حداکثر ۲۵ مگابایت</div>
            </div>
          </div>
        </div>

        <div class="form-foot">
          <span class="note"><i class="ri-shield-check-line" style="color:var(--bp-teal)"></i>اطلاعات پروژه شما محفوظ و امن نگه‌داری می‌شود.</span>
          <div class="acts">
            <button class="bp-btn bp-btn--ghost">ذخیره پیش‌نویس</button>
            <button class="bp-btn bp-btn--primary"><i class="ri-arrow-left-line"></i>ادامه به بودجه و زمان</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endverbatim
@endsection

@push('scripts')
@verbatim
<script>
  // work-type radio
  document.querySelectorAll('[data-wt]').forEach(w => w.addEventListener('click', () => {
    document.querySelectorAll('[data-wt]').forEach(x => x.classList.remove('sel'));
    w.classList.add('sel');
  }));

  // chips
  const chips = document.getElementById('chips');
  const input = document.getElementById('chipInput');
  function addChip(name) {
    name = name.trim(); if (!name) return;
    const el = document.createElement('span');
    el.className = 'chip';
    el.innerHTML = name + ' <i class="ri-close-line" data-rm></i>';
    chips.insertBefore(el, input);
    input.value = '';
  }
  input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addChip(input.value); } });
  chips.addEventListener('click', e => { if (e.target.matches('[data-rm]')) e.target.closest('.chip').remove(); });
  document.getElementById('suggest').addEventListener('click', e => {
    if (e.target.matches('[data-add]')) { addChip(e.target.textContent); e.target.remove(); }
  });
</script>
@endverbatim
@endpush
