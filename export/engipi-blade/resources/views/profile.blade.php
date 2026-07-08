@extends('layouts.base')
@section('title', "Engipi — پروفایل و مهارت‌ها")

@push('head')
@verbatim
<style>
  /* profile header */
  .prof-head { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; margin-bottom: 20px; }
  .prof-head .band { height: 96px; background: var(--bp-navy); position: relative; overflow: hidden; }
  .prof-head .band .grid-bg { position: absolute; inset: 0; opacity: .55; }
  .prof-head .band .glow { position: absolute; top: -60px; inset-inline-start: 30%; width: 240px; height: 240px; border-radius: 50%; background: radial-gradient(circle, rgba(31,111,235,.4), transparent 70%); filter: blur(6px); }
  .prof-head .body { padding: 0 26px 22px; display: flex; align-items: flex-end; gap: 18px; flex-wrap: wrap; }
  .prof-head .av { width: 96px; height: 96px; border-radius: var(--bp-r-lg); background: var(--bp-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 2.4rem; margin-top: -48px; border: 4px solid #fff; box-shadow: var(--bp-sh-sm); }
  .prof-head .info { flex: 1; min-width: 220px; padding-top: 12px; }
  .prof-head .info h2 { font-size: 1.4rem; font-weight: 900; display: flex; align-items: center; gap: 9px; }
  .prof-head .info .verified { color: var(--bp-teal); font-size: 1.2rem; }
  .prof-head .info .role { color: var(--bp-muted); font-size: .92rem; margin-top: 2px; }
  .prof-head .info .meta { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 10px; font-size: .84rem; color: var(--bp-muted); }
  .prof-head .info .meta span { display: flex; align-items: center; gap: 6px; }
  .prof-head .cta { padding-top: 12px; display: flex; gap: 10px; }

  .pstats { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 20px; }
  @media (max-width: 760px) { .pstats { grid-template-columns: repeat(2,1fr); } }
  .pstat { background: #fff; border: 1px solid var(--bp-border); border-top: 3px solid var(--ac); border-radius: 0 0 var(--bp-r-lg) var(--bp-r-lg); padding: 16px 18px; }
  .pstat .n { font-size: 1.6rem; font-weight: 900; color: var(--bp-ink); font-feature-settings: "tnum"; }
  .pstat .l { font-size: .82rem; color: var(--bp-muted); }

  .pcols { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
  @media (max-width: 980px) { .pcols { grid-template-columns: 1fr; } }

  /* skills with proficiency */
  .skill { margin-bottom: 18px; }
  .skill:last-child { margin-bottom: 0; }
  .skill .top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
  .skill .nm { font-weight: 700; font-size: .92rem; color: var(--bp-ink); display: flex; align-items: center; gap: 8px; }
  .skill .nm .ic { width: 30px; height: 30px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1rem; }
  .skill .lvl { font-size: .78rem; font-weight: 700; color: var(--bp-muted); font-family: ui-monospace, monospace; }
  .bar { height: 8px; background: var(--bp-surface); border-radius: 50px; overflow: hidden; border: 1px solid var(--bp-hair); }
  .bar > span { display: block; height: 100%; border-radius: 50px; background: linear-gradient(90deg, var(--bp-blue), var(--bp-teal)); }

  .skill-add { display: flex; gap: 8px; margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--bp-hair); }
  .skill-add .inp { flex: 1; }

  .chips { display: flex; flex-wrap: wrap; gap: 8px; }
  .chip2 { display: inline-flex; align-items: center; gap: 6px; background: var(--bp-tint-blue); color: var(--bp-blue); font-weight: 700; font-size: .82rem; padding: 7px 12px; border-radius: var(--bp-r); }
  .chip2 i { cursor: pointer; opacity: .65; } .chip2 i:hover { opacity: 1; }

  .port { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  @media (max-width: 560px) { .port { grid-template-columns: 1fr; } }
  .pitem { border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 16px; transition: all .2s; cursor: pointer; }
  .pitem:hover { border-color: var(--bp-blue); box-shadow: var(--bp-sh-sm); transform: translateY(-2px); }
  .pitem .ic { width: 40px; height: 40px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 10px; }
  .pitem h6 { font-size: .92rem; margin-bottom: 4px; }
  .pitem p { font-size: .78rem; color: var(--bp-muted); }

  .aside-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 20px; margin-bottom: 18px; }
  .aside-card h6 { font-size: .92rem; margin-bottom: 14px; display:flex; align-items:center; gap:8px; }
  .aside-card h6 i { color: var(--bp-blue); }
  .badge-row { display: flex; align-items: center; gap: 11px; padding: 10px 0; border-bottom: 1px solid var(--bp-hair); }
  .badge-row:last-child { border-bottom: 0; }
  .badge-row .ic { width: 38px; height: 38px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex: none; }
  .badge-row .t { font-weight: 700; font-size: .86rem; color: var(--bp-ink); }
  .badge-row .s { font-size: .76rem; color: var(--bp-muted); }
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
      <li><a class="sb-item" href="/projects/show"><i class="ri-briefcase-line"></i><span>پروژه‌های من</span></a></li>
      <li><a class="sb-item" href="/requests"><i class="ri-inbox-line"></i><span>درخواست‌ها</span></a></li>
      <li><a class="sb-item active" href="/profile"><i class="ri-star-line"></i><span>مهارت‌ها</span></a></li>
      <li><a class="sb-item" href="/tickets"><i class="ri-customer-service-2-line"></i><span>تیکت‌ها</span></a></li>
    </ul>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button>پروفایل و مهارت‌ها</div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <a class="tb-user" href="/profile"><div class="av">ع</div><div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div><i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i></a>
      </div>
    </header>

    <div class="content">
      <!-- profile header -->
      <div class="prof-head">
        <div class="band"><div class="grid-bg bp-grid"></div><div class="glow"></div></div>
        <div class="body">
          <div class="av">ع</div>
          <div class="info">
            <h2>علی محمدی <i class="ri-verified-badge-fill verified"></i></h2>
            <div class="role">متخصص مهندسی مکانیک · کارشناسی ارشد طراحی کاربردی</div>
            <div class="meta">
              <span><i class="ri-map-pin-line"></i>تهران، ایران</span>
              <span><i class="ri-briefcase-line"></i>۲۸ پروژه موفق</span>
              <span><i class="ri-star-fill" style="color:#E0930B"></i>۴.۹ از ۵</span>
              <span><i class="ri-calendar-line"></i>عضو از ۱۴۰۲</span>
            </div>
          </div>
          <div class="cta">
            <button class="bp-btn bp-btn--ghost bp-btn--sm"><i class="ri-eye-line"></i>نمای عمومی</button>
            <button class="bp-btn bp-btn--primary bp-btn--sm"><i class="ri-edit-line"></i>ویرایش پروفایل</button>
          </div>
        </div>
      </div>

      <!-- stats -->
      <div class="pstats">
        <div class="pstat" style="--ac:var(--bp-blue)"><div class="n">۲۸</div><div class="l">پروژه تکمیل‌شده</div></div>
        <div class="pstat" style="--ac:var(--bp-teal)"><div class="n">٪۹۴</div><div class="l">نرخ موفقیت</div></div>
        <div class="pstat" style="--ac:var(--bp-c-amber)"><div class="n">۴.۹</div><div class="l">میانگین امتیاز</div></div>
        <div class="pstat" style="--ac:var(--bp-c-green)"><div class="n">‹۲ ساعت</div><div class="l">زمان پاسخ</div></div>
      </div>

      <div class="pcols">
        <!-- MAIN -->
        <div>
          <!-- skills with proficiency -->
          <div class="panel2" style="margin-bottom:20px">
            <div class="ph"><h5><i class="ri-bar-chart-2-line"></i>مهارت‌های تخصصی</h5><button class="bp-btn bp-btn--soft bp-btn--sm"><i class="ri-add-line"></i>افزودن</button></div>
            <div class="pb">
              <div id="skills"></div>
              <div class="skill-add">
                <input class="inp" id="skInput" placeholder="نام مهارت جدید...">
                <select class="sel" id="skLevel" style="max-width:150px"><option>پیشرفته</option><option>متوسط</option><option>مقدماتی</option></select>
                <button class="bp-btn bp-btn--primary" onclick="addSkill()"><i class="ri-add-line"></i>افزودن</button>
              </div>
            </div>
          </div>

          <!-- tools -->
          <div class="panel2" style="margin-bottom:20px">
            <div class="ph"><h5><i class="ri-tools-line"></i>ابزار و نرم‌افزارها</h5></div>
            <div class="pb">
              <div class="chips" id="tools"></div>
            </div>
          </div>

          <!-- portfolio -->
          <div class="panel2">
            <div class="ph"><h5><i class="ri-folder-3-line"></i>نمونه‌کارها</h5><span class="bp-badge bp-badge--soft">۴ پروژه</span></div>
            <div class="pb">
              <div class="port" id="port"></div>
            </div>
          </div>
        </div>

        <!-- ASIDE -->
        <div>
          <div class="aside-card">
            <h6><i class="ri-award-line"></i>نشان‌ها و تأییدها</h6>
            <div class="badge-row"><div class="ic" style="background:var(--bp-tint-teal);color:var(--bp-teal)"><i class="ri-verified-badge-fill"></i></div><div><div class="t">هویت تأیید شده</div><div class="s">احراز هویت کامل</div></div></div>
            <div class="badge-row"><div class="ic" style="background:var(--bp-tint-blue);color:var(--bp-blue)"><i class="ri-graduation-cap-fill"></i></div><div><div class="t">مدرک تحصیلی</div><div class="s">کارشناسی ارشد مکانیک</div></div></div>
            <div class="badge-row"><div class="ic" style="background:var(--bp-tint-amber);color:var(--bp-c-amber)"><i class="ri-trophy-fill"></i></div><div><div class="t">متخصص برتر</div><div class="s">سه‌ماهه‌ی بهار ۱۴۰۴</div></div></div>
          </div>
          <div class="aside-card">
            <h6><i class="ri-translate-2"></i>زبان‌ها</h6>
            <div class="badge-row"><div class="t">فارسی</div><div class="s" style="margin-inline-start:auto">بومی</div></div>
            <div class="badge-row"><div class="t">انگلیسی</div><div class="s" style="margin-inline-start:auto">پیشرفته</div></div>
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
  const SKILLS = [
    ['طراحی و مدل‌سازی CAD','ri-ruler-2-line','var(--bp-tint-blue)','var(--bp-blue)',95,'خبره'],
    ['تحلیل المان محدود (FEA)','ri-grid-line','var(--bp-tint-teal)','var(--bp-teal)',90,'پیشرفته'],
    ['دینامیک سیالات (CFD)','ri-windy-line','var(--bp-tint-sky)','var(--bp-c-sky)',80,'پیشرفته'],
    ['طراحی سیستم کنترل','ri-settings-4-line','var(--bp-tint-amber)','var(--bp-c-amber)',72,'متوسط'],
  ];
  const TOOLS = ['ANSYS','SolidWorks','MATLAB','CATIA','AutoCAD','Fluent'];
  const PORT = [
    ['ri-flashlight-line','var(--bp-tint-amber)','var(--bp-c-amber)','تحلیل تنش بازوی رباتیک','ANSYS · کاهش ۳۰٪ وزن'],
    ['ri-windy-line','var(--bp-tint-sky)','var(--bp-c-sky)','شبیه‌سازی جریان هوا','CFD · بهینه‌سازی پروفیل بال'],
    ['ri-settings-4-line','var(--bp-tint-blue)','var(--bp-blue)','طراحی گیربکس صنعتی','SolidWorks · نسبت ۱:۴۰'],
    ['ri-temp-hot-line','var(--bp-tint-red)','var(--bp-c-red)','تحلیل حرارتی مبدل','تحلیل انتقال حرارت پایا'],
  ];

  function levelMeta(p){ return p>=90?'خبره':p>=78?'پیشرفته':p>=60?'متوسط':'مقدماتی'; }
  function renderSkills() {
    document.getElementById('skills').innerHTML = SKILLS.map(s => `
      <div class="skill">
        <div class="top">
          <div class="nm"><span class="ic" style="background:${s[2]};color:${s[3]}"><i class="${s[1]}"></i></span>${s[0]}</div>
          <span class="lvl">${s[5]} · ٪${s[4]}</span>
        </div>
        <div class="bar"><span style="width:${s[4]}%"></span></div>
      </div>`).join('');
  }
  function addSkill() {
    const v = document.getElementById('skInput').value.trim();
    const lvl = document.getElementById('skLevel').value;
    if (!v) return;
    const pct = lvl==='پیشرفته'?82:lvl==='متوسط'?65:45;
    SKILLS.push([v,'ri-focus-2-line','var(--bp-tint-purple)','var(--bp-c-purple)',pct,lvl]);
    document.getElementById('skInput').value=''; renderSkills();
  }
  function renderTools() {
    document.getElementById('tools').innerHTML = TOOLS.map(t => `<span class="chip2">${t} <i class="ri-close-line" onclick="this.closest('.chip2').remove()"></i></span>`).join('');
  }
  document.getElementById('port').innerHTML = PORT.map(p => `
    <div class="pitem"><div class="ic" style="background:${p[1]};color:${p[2]}"><i class="${p[0]}"></i></div><h6>${p[3]}</h6><p>${p[4]}</p></div>`).join('');
  renderSkills(); renderTools();
</script>
@endverbatim
@endpush
