@extends('layouts.base')
@section('title', "EngPis — جزئیات پروژه")

@push('head')
@verbatim
<style>
  .det-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
  @media (max-width: 980px) { .det-grid { grid-template-columns: 1fr; } }

  .hero-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
  .hero-card .band { background: var(--bp-navy); position: relative; overflow: hidden; padding: 24px 24px; }
  .hero-card .band .grid-bg { position: absolute; inset: 0; opacity: .55; }
  .hero-card .band .tags { position: relative; display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
  .hero-card .band h1 { position: relative; color: #fff; font-size: 1.45rem; font-weight: 900; line-height: 1.4; }
  .hero-card .band .meta { position: relative; display: flex; flex-wrap: wrap; gap: 18px; margin-top: 14px; color: rgba(255,255,255,.7); font-size: .85rem; }
  .hero-card .band .meta span { display: flex; align-items: center; gap: 6px; }
  .hero-card .body { padding: 24px; }
  .hero-card .body h5 { font-size: 1rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
  .hero-card .body h5 i { color: var(--bp-blue); }
  .hero-card .body p { color: var(--bp-text); line-height: 1.9; font-size: .95rem; }
  .hr { height: 1px; background: var(--bp-hair); margin: 22px 0; }

  .kv { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; }
  @media (max-width: 560px) { .kv { grid-template-columns: 1fr; } }
  .kv .item { display: flex; align-items: center; gap: 12px; padding: 14px; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); }
  .kv .item .ic { width: 40px; height: 40px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex: none; }
  .kv .item .l { font-size: .76rem; color: var(--bp-muted); }
  .kv .item .v { font-weight: 800; color: var(--bp-ink); font-size: .95rem; }

  .req-item { display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--bp-hair); }
  .req-item:last-child { border-bottom: 0; }
  .req-item .av { width: 46px; height: 46px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; flex: none; }
  .req-item .nm { font-weight: 800; color: var(--bp-ink); font-size: .95rem; display: flex; align-items: center; gap: 7px; }
  .req-item .role { color: var(--bp-muted); font-size: .8rem; }
  .req-item .msg { color: var(--bp-text); font-size: .88rem; line-height: 1.7; margin: 8px 0; }
  .req-item .rate { display: flex; align-items: center; gap: 4px; color: #E0930B; font-size: .82rem; font-weight: 700; }
  .req-item .acts { display: flex; gap: 8px; margin-top: 8px; }
  .req-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
  .price { font-family: ui-monospace, monospace; font-weight: 800; color: var(--bp-teal); font-size: .95rem; }

  /* aside */
  .aside-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 20px; margin-bottom: 18px; }
  .aside-card h6 { font-size: .92rem; margin-bottom: 14px; }
  .emp-row { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
  .emp-row .av { width: 52px; height: 52px; border-radius: var(--bp-r-lg); background: var(--bp-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; }
  .emp-row .nm { font-weight: 800; color: var(--bp-ink); }
  .emp-row .s { font-size: .8rem; color: var(--bp-muted); }
  .mini-stat { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid var(--bp-hair); font-size: .85rem; }
  .mini-stat:last-child { border-bottom: 0; }
  .mini-stat .k { color: var(--bp-muted); }
  .mini-stat .v { font-weight: 700; color: var(--bp-ink); }
</style>
@endverbatim
@endpush

@section('content')
@verbatim
<div class="app">
  <!-- SIDEBAR -->
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
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button><span class="crumb">پروژه‌های من /</span> جزئیات پروژه</div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <a class="tb-user" href="/profile"><div class="av">ع</div><div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div><i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i></a>
      </div>
    </header>

    <div class="content">
      <div class="det-grid">
        <!-- MAIN -->
        <div>
          <div class="hero-card" style="margin-bottom:20px">
            <div class="band">
              <div class="grid-bg bp-grid"></div>
              <div class="tags">
                <span class="bp-badge bp-badge--teal">دورکاری</span>
                <span class="bp-badge" style="background:rgba(255,255,255,.12);color:#fff">باز برای درخواست</span>
              </div>
              <h1>طراحی و شبیه‌سازی مدار قدرت سه‌فاز برای خط تولید صنعتی</h1>
              <div class="meta">
                <span><i class="ri-stack-line"></i>مهندسی برق</span>
                <span><i class="ri-calendar-line"></i>ثبت: ۱۴۰۴/۰۳/۱۰</span>
                <span><i class="ri-time-line"></i>مهلت: ۲۰ روز</span>
                <span><i class="ri-group-line"></i>۳ درخواست</span>
              </div>
            </div>
            <div class="body">
              <h5><i class="ri-file-text-line"></i>شرح پروژه</h5>
              <p>نیازمند طراحی و شبیه‌سازی کامل یک مدار قدرت سه‌فاز با در نظر گرفتن سیستم حفاظت، کیفیت توان و هماهنگی رله‌ها برای یک خط تولید صنعتی هستیم. خروجی مورد انتظار شامل مدل شبیه‌سازی‌شده، گزارش فنی و نمودارهای تحلیل است. آشنایی با استانداردهای IEC الزامی است.</p>
              <div class="hr"></div>
              <h5 style="margin-bottom:12px"><i class="ri-price-tag-3-line"></i>مشخصات</h5>
              <div class="kv">
                <div class="item"><div class="ic" style="background:var(--bp-tint-teal);color:var(--bp-teal)"><i class="ri-wallet-3-line"></i></div><div><div class="l">بودجه</div><div class="v">۸٬۰۰۰٬۰۰۰ تومان</div></div></div>
                <div class="item"><div class="ic" style="background:var(--bp-tint-blue);color:var(--bp-blue)"><i class="ri-bar-chart-box-line"></i></div><div><div class="l">سطح</div><div class="v">پیشرفته</div></div></div>
                <div class="item"><div class="ic" style="background:var(--bp-tint-amber);color:var(--bp-c-amber)"><i class="ri-home-wifi-line"></i></div><div><div class="l">نوع همکاری</div><div class="v">دورکاری</div></div></div>
                <div class="item"><div class="ic" style="background:var(--bp-tint-purple);color:var(--bp-c-purple)"><i class="ri-time-line"></i></div><div><div class="l">زمان تحویل</div><div class="v">۲۰ روز</div></div></div>
              </div>
              <div class="hr"></div>
              <h5 style="margin-bottom:12px"><i class="ri-tools-line"></i>ابزار و مهارت‌ها</h5>
              <div style="display:flex; flex-wrap:wrap; gap:8px">
                <span class="bp-badge bp-badge--soft">MATLAB / Simulink</span>
                <span class="bp-badge bp-badge--soft-teal">ETAP</span>
                <span class="bp-badge bp-badge--soft-teal">استاندارد IEC</span>
                <span class="bp-badge bp-badge--soft-teal">حفاظت سیستم قدرت</span>
              </div>
            </div>
          </div>

          <!-- REQUESTS -->
          <div class="panel2">
            <div class="ph"><h5><i class="ri-inbox-line"></i>درخواست‌های دریافتی</h5><span class="bp-badge bp-badge--soft">۳ متخصص</span></div>
            <div class="pb" id="reqs"></div>
          </div>
        </div>

        <!-- ASIDE -->
        <div>
          <div class="aside-card">
            <h6>کارفرما</h6>
            <div class="emp-row">
              <div class="av">پ</div>
              <div><div class="nm">شرکت پایا صنعت</div><div class="s">تهران · عضو از ۱۴۰۲</div></div>
            </div>
            <div class="mini-stat"><span class="k">پروژه‌های منتشرشده</span><span class="v">۱۴</span></div>
            <div class="mini-stat"><span class="k">نرخ استخدام</span><span class="v">٪۸۶</span></div>
            <div class="mini-stat"><span class="k">میانگین امتیاز</span><span class="v">۴.۸ / ۵</span></div>
          </div>
          <div class="aside-card">
            <h6>اقدام سریع</h6>
            <button class="bp-btn bp-btn--primary" style="width:100%; justify-content:center; margin-bottom:10px"><i class="ri-send-plane-line"></i>ارسال درخواست همکاری</button>
            <button class="bp-btn bp-btn--ghost" style="width:100%; justify-content:center; margin-bottom:10px"><i class="ri-bookmark-line"></i>ذخیره پروژه</button>
            <button class="bp-btn bp-btn--ghost" style="width:100%; justify-content:center"><i class="ri-share-line"></i>اشتراک‌گذاری</button>
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
  const REQS = [
    ['س','var(--bp-teal)','سارا احمدی','متخصص مهندسی برق','۴.۹','«با بیش از ۶ سال تجربه در شبیه‌سازی سیستم‌های قدرت و آشنایی کامل با ETAP و استاندارد IEC، آماده‌ی انجام این پروژه در بازه‌ی درخواستی هستم.»','۷٬۵۰۰٬۰۰۰ تومان','new'],
    ['م','var(--bp-blue)','مهدی رضایی','متخصص الکترونیک قدرت','۴.۷','«نمونه‌کارهای مشابهی در طراحی مدار قدرت صنعتی دارم و می‌توانم مدل Simulink کامل به همراه گزارش فنی ارائه دهم.»','۸٬۲۰۰٬۰۰۰ تومان',''],
    ['ر','var(--bp-c-amber)','رضا کریمی','مهندس برق - دانشجوی دکترا','۴.۸','«تمرکز پژوهشی من روی کیفیت توان است. پیشنهاد می‌کنم علاوه بر شبیه‌سازی، تحلیل هارمونیک‌ها هم اضافه شود.»','۶٬۹۰۰٬۰۰۰ تومان',''],
  ];
  document.getElementById('reqs').innerHTML = REQS.map(r => `
    <div class="req-item">
      <div class="av" style="background:${r[1]}">${r[0]}</div>
      <div style="flex:1; min-width:0">
        <div class="req-top">
          <div><div class="nm">${r[2]} ${r[7]==='new'?'<span class="bp-badge bp-badge--teal" style="font-size:.66rem">جدید</span>':''}</div><div class="role">${r[3]}</div></div>
          <div style="text-align:left"><div class="rate"><i class="ri-star-fill"></i>${r[4]}</div></div>
        </div>
        <p class="msg">${r[5]}</p>
        <div class="req-top">
          <span class="price">پیشنهاد: ${r[6]}</span>
          <div class="acts">
            <button class="bp-btn bp-btn--ghost bp-btn--sm"><i class="ri-chat-1-line"></i>گفتگو</button>
            <button class="bp-btn bp-btn--primary bp-btn--sm"><i class="ri-check-line"></i>پذیرش</button>
          </div>
        </div>
      </div>
    </div>`).join('');
</script>
@endverbatim
@endpush
