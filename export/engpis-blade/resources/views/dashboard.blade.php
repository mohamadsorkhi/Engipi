@extends('layouts.base')
@section('title', "EngPis — داشبورد")

@push('head')
@verbatim
<style>
  body { background: var(--bp-surface); }
  .app { display: flex; min-height: 100vh; }

  /* ── Sidebar (RTL: right/start) ── */
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
  .topbar .pt { font-size: 1.1rem; font-weight: 800; color: var(--bp-ink); }
  .tb-act { display: flex; align-items: center; gap: 14px; }
  .iconbtn { width: 42px; height: 42px; border-radius: var(--bp-r); border: 1px solid var(--bp-border); background: #fff; color: var(--bp-muted); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; position: relative; transition: all .2s; }
  .iconbtn:hover { border-color: var(--bp-blue); color: var(--bp-blue); background: var(--bp-tint-blue); }
  .iconbtn .dot { position: absolute; top: 9px; inset-inline-end: 10px; width: 8px; height: 8px; border-radius: 50%; background: var(--bp-c-red); border: 2px solid #fff; }
  .tb-user { display: flex; align-items: center; gap: 10px; cursor: pointer; padding-inline-start: 14px; border-inline-start: 1px solid var(--bp-border); }
  .tb-user .av { width: 40px; height: 40px; border-radius: var(--bp-r); background: var(--bp-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; }
  .tb-user .nm { font-size: .9rem; font-weight: 700; color: var(--bp-ink); }
  .tb-user .rl { font-size: .74rem; color: var(--bp-muted); }
  .content { padding: 28px; }

  /* ── Role select gate ── */
  .gate { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; background: var(--bp-surface); }
  .gate-inner { width: 100%; max-width: 760px; }
  .gate-head { text-align: center; margin-bottom: 32px; }
  .gate-head .brand3 { font-size: 1.8rem; font-weight: 900; color: var(--bp-ink); margin-bottom: 16px; }
  .gate-head .brand3 .a { color: var(--bp-blue); }
  .gate-head h3 { font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; }
  .gate-head p { color: var(--bp-muted); }
  .roles { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 620px) { .roles { grid-template-columns: 1fr; } }
  .role { position: relative; background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 30px 26px; text-align: center; cursor: pointer; overflow: hidden; transition: transform .25s var(--bp-ease), box-shadow .25s, border-color .25s; }
  .role::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 4px; }
  .role.emp::before { background: var(--bp-blue); }
  .role.spec::before { background: var(--bp-teal); }
  .role:hover { transform: translateY(-5px); box-shadow: var(--bp-sh-lg); }
  .role.emp:hover { border-color: var(--bp-blue); }
  .role.spec:hover { border-color: var(--bp-teal); }
  .role .chip { width: 66px; height: 66px; border-radius: var(--bp-r-lg); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 6px auto 16px; }
  .role.emp .chip { background: var(--bp-tint-blue); color: var(--bp-blue); }
  .role.spec .chip { background: var(--bp-tint-teal); color: var(--bp-teal); }
  .role h4 { font-size: 1.2rem; font-weight: 800; margin-bottom: 7px; }
  .role p { color: var(--bp-muted); font-size: .88rem; margin-bottom: 22px; line-height: 1.6; }
  .role .bp-btn { width: 100%; justify-content: center; }

  /* ── Welcome ── */
  .welcome { position: relative; overflow: hidden; background: var(--bp-navy); border-radius: var(--bp-r-lg); padding: 28px 30px; color: #fff; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
  .welcome .grid-bg { position: absolute; inset: 0; opacity: .6; }
  .welcome h4 { position: relative; font-size: 1.3rem; color: #fff; margin-bottom: 4px; }
  .welcome p { position: relative; color: rgba(255,255,255,.66); font-size: .92rem; }
  .welcome-act { position: relative; display: flex; gap: 10px; flex-wrap: wrap; }

  /* ── Stat cards ── */
  .sgrid { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; margin-bottom: 22px; }
  @media (max-width: 1000px) { .sgrid { grid-template-columns: repeat(2,1fr); } }
  .statcard { background: #fff; border: 1px solid var(--bp-border); border-top: 3px solid var(--ac); border-radius: 0 0 var(--bp-r-lg) var(--bp-r-lg); padding: 18px 20px; transition: transform .25s var(--bp-ease), box-shadow .25s; }
  .statcard:hover { transform: translateY(-3px); box-shadow: var(--bp-sh-md); }
  .statcard .srow { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
  .statcard .sl { font-size: .76rem; color: var(--bp-muted); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; }
  .statcard .tile { width: 38px; height: 38px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
  .statcard .sn { font-size: 1.9rem; font-weight: 900; color: var(--bp-ink); font-feature-settings: "tnum"; }
  .statcard .slink { font-size: .8rem; color: var(--bp-muted); cursor: pointer; }
  .statcard .slink:hover { color: var(--bp-blue); }

  .twocol { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 900px) { .twocol { grid-template-columns: 1fr; } }
  .panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
  .panel2 .ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
  .panel2 .ph h5 { font-size: 1rem; }
  .panel2 .pb { padding: 8px 20px; }
  .lrow { display: flex; align-items: center; justify-content: space-between; padding: 13px 0; border-bottom: 1px solid var(--bp-hair); }
  .lrow:last-child { border-bottom: 0; }
  .lrow .lt { font-weight: 600; font-size: .92rem; color: var(--bp-ink); cursor: pointer; }
  .lrow .lt:hover { color: var(--bp-blue); }
  .lrow .lm { font-size: .8rem; color: var(--bp-muted); font-family: ui-monospace, monospace; }

  /* ── Project cards (matched) ── */
  .pgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  @media (max-width: 900px) { .pgrid { grid-template-columns: 1fr; } }
  .proj { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 20px; transition: transform .25s var(--bp-ease), box-shadow .25s, border-color .25s; }
  .proj:hover { transform: translateY(-3px); box-shadow: var(--bp-sh-md); border-color: var(--bp-blue); }
  .proj .pt2 { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
  .proj .pname { font-size: 1.05rem; font-weight: 800; color: var(--bp-ink); cursor: pointer; }
  .proj .pname:hover { color: var(--bp-blue); }
  .proj .pemp { color: var(--bp-muted); font-size: .82rem; display: flex; align-items: center; gap: 5px; margin-top: 3px; }
  .proj .pdesc { color: var(--bp-muted); font-size: .9rem; line-height: 1.7; margin-bottom: 14px; }
  .proj .ptags { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 16px; }
  .proj .pfoot { display: flex; align-items: center; justify-content: space-between; }
  .proj .pdate { color: var(--bp-muted); font-size: .8rem; display: flex; align-items: center; gap: 5px; }

  .empty { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 64px 20px; text-align: center; color: var(--bp-muted); }
  .empty i { font-size: 3rem; color: var(--bp-border); }
  .empty p { margin-top: 14px; }
  .hidden { display: none !important; }
</style>
@endverbatim
@endpush

@section('content')
@verbatim
<!-- Role gate -->
<div class="gate" id="gate">
  <div class="gate-inner">
    <div class="gate-head">
      <div class="brand3"><span class="a">Eng</span>Pis</div>
      <h3>به EngPis خوش آمدید</h3>
      <p>با چه نقشی می‌خواهید وارد شوید؟</p>
    </div>
    <div class="roles">
      <div class="role emp" onclick="enterApp()">
        <div class="chip"><i class="ri-briefcase-line"></i></div>
        <h4>کارفرما هستم</h4>
        <p>پروژه‌های خود را ثبت کنید و با متخصصین حرفه‌ای همکاری کنید</p>
        <button class="bp-btn bp-btn--primary" onclick="event.stopPropagation();enterApp()"><i class="ri-arrow-left-line"></i>ورود به عنوان کارفرما</button>
      </div>
      <div class="role spec" onclick="enterApp()">
        <div class="chip"><i class="ri-user-star-line"></i></div>
        <h4>متخصص هستم</h4>
        <p>مهارت‌هایتان را ثبت کنید و با پروژه‌های مناسب تطبیق یابید</p>
        <button class="bp-btn bp-btn--teal" onclick="event.stopPropagation();enterApp()"><i class="ri-arrow-left-line"></i>ورود به عنوان متخصص</button>
      </div>
    </div>
  </div>
</div>

<!-- App -->
<div class="app hidden" id="app">
  <aside class="sidebar" id="sidebar">
    <div class="grid-bg bp-grid"></div>
    <div class="sb-brand"><span class="a">Eng</span>Pis</div>
    <div class="sb-sec">منوی اصلی</div>
    <ul class="sb-nav" id="sbnav"></ul>
  </aside>
  <div class="main">
    <header class="topbar">
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button><span id="pageTitle">داشبورد</span></div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <a class="tb-user" href="/profile">
          <div class="av">ع</div>
          <div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div>
          <i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i>
        </a>
      </div>
    </header>
    <div class="content" id="screen"></div>
  </div>
</div>
@endverbatim
@endsection

@push('scripts')
@verbatim
<script>
  // [id, icon, label, badge, href]  href=null → internal view via go()
  const NAV = [
    ['dashboard','ri-dashboard-2-line','داشبورد',null,null],
    ['matched','ri-lightbulb-flash-line','پروژه‌های پیشنهادی','۴',null],
    ['projects','ri-briefcase-line','پروژه‌های من',null,'/projects/show'],
    ['requests','ri-inbox-line','درخواست‌ها',null,'/requests'],
    ['skills','ri-star-line','مهارت‌ها',null,'/profile'],
    ['tickets','ri-customer-service-2-line','تیکت‌ها',null,'/tickets'],
  ];
  const TITLES = {dashboard:'داشبورد',matched:'پروژه‌های پیشنهادی',projects:'پروژه‌های من',requests:'درخواست‌ها',skills:'مهارت‌ها',tickets:'تیکت‌ها'};

  const STATS = [
    ['پروژه‌های من','ri-briefcase-line','var(--bp-blue)','var(--bp-tint-blue)','۱۲'],
    ['درخواست دریافتی','ri-inbox-line','var(--bp-teal)','var(--bp-tint-teal)','۸'],
    ['پروژه پیشنهادی','ri-lightbulb-flash-line','var(--bp-c-green)','var(--bp-tint-green)','۴'],
    ['درخواست ارسالی','ri-send-plane-2-line','var(--bp-c-amber)','var(--bp-tint-amber)','۵'],
  ];
  const MYPROJ = [['شبیه‌سازی سیستم کنترل با MATLAB','۱۴۰۴/۰۳/۱۲'],['تحلیل تنش سازه با ANSYS','۱۴۰۴/۰۳/۰۹'],['مدل‌سازی داده با Python','۱۴۰۴/۰۲/۲۸']];
  const RECMATCH = [['طراحی مدار قدرت سه‌فاز','مهندسی برق'],['بهینه‌سازی خط تولید','مهندسی صنایع'],['تحلیل ارتعاشات شفت','مهندسی مکانیک']];
  const MATCHED = [
    ['طراحی مدار قدرت سه‌فاز برای کارخانه','شرکت پایا صنعت','دورکاری','teal','نیازمند طراحی و شبیه‌سازی مدار قدرت سه‌فاز با حفاظت و کیفیت توان برای یک خط تولید صنعتی.','مهندسی برق',['MATLAB','ETAP'],'٪۹۶'],
    ['تحلیل ارتعاشات شفت توربین گازی','مهدی رضایی','حضوری','blue','تحلیل مودال و ارتعاشات شفت توربین گازی و ارائه راهکار کاهش ارتعاش در محدوده کاری.','مهندسی مکانیک',['ANSYS','SolidWorks'],'٪۹۱'],
    ['بهینه‌سازی چیدمان خط تولید','گروه صنعتی آرمان','ترکیبی','soft','مدل‌سازی و بهینه‌سازی چیدمان خط تولید با هدف کاهش زمان جابه‌جایی و افزایش بهره‌وری.','مهندسی صنایع',['Python','Arena'],'٪۸۹'],
    ['مدل پیش‌بینی مصرف انرژی ساختمان','دانشگاه صنعتی','دورکاری','teal','ساخت مدل یادگیری ماشین برای پیش‌بینی مصرف انرژی بر اساس داده‌های تاریخی.','مهندسی کامپیوتر',['Python','TensorFlow'],'٪۸۷'],
  ];

  let active = 'dashboard';

  function renderNav() {
    document.getElementById('sbnav').innerHTML = NAV.map(n => {
      const inner = `<i class="${n[1]}"></i><span>${n[2]}</span>${n[3]?`<span class="sb-badge">${n[3]}</span>`:''}`;
      return n[4]
        ? `<li><a class="sb-item" href="${n[4]}">${inner}</a></li>`
        : `<li><a class="sb-item ${n[0]===active?'active':''}" onclick="go('${n[0]}');return false" href="#">${inner}</a></li>`;
    }).join('');
  }
  function go(id) { active = id; document.getElementById('pageTitle').textContent = TITLES[id]; renderNav(); renderScreen(); }

  function dashboardScreen() {
    return `
      <div class="welcome">
        <div class="grid-bg bp-grid"></div>
        <div><h4>سلام، علی محمدی</h4><p>خلاصه وضعیت حساب کاربری شما</p></div>
        <div class="welcome-act">
          <a class="bp-btn bp-btn--primary" href="/projects/create"><i class="ri-add-line"></i>ثبت پروژه</a>
          <a class="bp-btn bp-btn--ghost-d" href="/profile"><i class="ri-star-line"></i>مهارت‌ها</a>
        </div>
      </div>
      <div class="sgrid">
        ${STATS.map(s => `
          <div class="statcard" style="--ac:${s[2]}">
            <div class="srow"><span class="sl">${s[0]}</span><div class="tile" style="background:${s[3]};color:${s[2]}"><i class="${s[1]}"></i></div></div>
            <div class="sn">${s[4]}</div>
            <div class="slink">مشاهده همه ←</div>
          </div>`).join('')}
      </div>
      <div class="twocol">
        <div class="panel2"><div class="ph"><h5>آخرین پروژه‌های من</h5><a class="bp-btn bp-btn--soft bp-btn--sm" href="/projects/show">همه</a></div>
          <div class="pb">${MYPROJ.map(p => `<div class="lrow"><a class="lt" href="/projects/show">${p[0]}</a><span class="lm">${p[1]}</span></div>`).join('')}</div></div>
        <div class="panel2"><div class="ph"><h5>پروژه‌های پیشنهادی</h5><button class="bp-btn bp-btn--soft bp-btn--sm" onclick="go('matched')">همه</button></div>
          <div class="pb">${RECMATCH.map(p => `<div class="lrow"><span class="lt">${p[0]}</span><span class="bp-badge bp-badge--soft">${p[1]}</span></div>`).join('')}</div></div>
      </div>`;
  }
  function matchedScreen() {
    const wt = {teal:'bp-badge--teal',blue:'bp-badge--blue',soft:'bp-badge--soft'};
    return `
      <div class="panel2"><div class="ph"><h5>پروژه‌های پیشنهادی برای شما</h5><button class="bp-btn bp-btn--soft bp-btn--sm"><i class="ri-settings-3-line"></i>مدیریت مهارت‌ها</button></div>
      <div class="pb" style="padding:20px"><div class="pgrid">
        ${MATCHED.map(p => `
          <div class="proj">
            <div class="pt2"><div><div class="pname">${p[0]}</div><div class="pemp"><i class="ri-user-line"></i>${p[1]}</div></div>
              <span class="bp-badge ${wt[p[3]]}">${p[2]}</span></div>
            <p class="pdesc">${p[4]}</p>
            <div class="ptags"><span class="bp-badge bp-badge--soft">${p[5]}</span>${p[6].map(t => `<span class="bp-badge bp-badge--soft-teal">${t}</span>`).join('')}<span class="bp-badge bp-badge--mono">تطابق ${p[7]}</span></div>
            <div class="pfoot"><span class="pdate"><i class="ri-calendar-line"></i>۲ روز پیش</span>
              <a class="bp-btn bp-btn--primary bp-btn--sm" href="/projects/show"><i class="ri-send-plane-line"></i>ارسال درخواست</a></div>
          </div>`).join('')}
      </div></div></div>`;
  }
  function emptyScreen(id) {
    return `<div class="empty"><i class="ri-inbox-line"></i><p>بخش «${TITLES[id]}» در این نمونه نمایش داده نشده است.</p></div>`;
  }
  function renderScreen() {
    const el = document.getElementById('screen');
    if (active === 'dashboard') el.innerHTML = dashboardScreen();
    else if (active === 'matched') el.innerHTML = matchedScreen();
    else el.innerHTML = emptyScreen(active);
  }
  function enterApp() {
    document.getElementById('gate').classList.add('hidden');
    document.getElementById('app').classList.remove('hidden');
    renderNav(); renderScreen();
  }

  // Routing: ?v=role → show role gate; ?v=matched → open matched; default → dashboard overview
  const _v = new URLSearchParams(location.search).get('v');
  if (_v === 'role') {
    // leave gate visible (default markup state)
  } else {
    if (_v === 'matched') active = 'matched';
    document.getElementById('pageTitle').textContent = TITLES[active];
    enterApp();
  }
</script>
@endverbatim
@endpush
