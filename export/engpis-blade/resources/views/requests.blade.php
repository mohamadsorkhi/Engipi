@extends('layouts.base')
@section('title', "EngPis — درخواست‌ها")

@push('head')
@verbatim
<style>
  .rq { display: flex; gap: 14px; padding: 18px; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); background: #fff; transition: all .2s; margin-bottom: 14px; }
  .rq:last-child { margin-bottom: 0; }
  .rq:hover { border-color: var(--bp-blue); box-shadow: var(--bp-sh-sm); }
  .rq .av { width: 48px; height: 48px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; flex: none; }
  .rq .main2 { flex: 1; min-width: 0; }
  .rq .top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
  .rq .pname { font-weight: 800; color: var(--bp-ink); font-size: .98rem; cursor: pointer; }
  .rq .pname:hover { color: var(--bp-blue); }
  .rq .who { color: var(--bp-muted); font-size: .82rem; margin-top: 2px; display: flex; align-items: center; gap: 6px; }
  .rq .msg { color: var(--bp-text); font-size: .88rem; line-height: 1.7; margin: 10px 0; }
  .rq .foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
  .rq .price { font-family: ui-monospace, monospace; font-weight: 800; color: var(--bp-teal); font-size: .9rem; }
  .rq .acts { display: flex; gap: 8px; }
  .rq .date { color: var(--bp-muted); font-size: .78rem; display: flex; align-items: center; gap: 5px; }

  .st { display: inline-flex; align-items: center; gap: 5px; font-size: .76rem; font-weight: 700; padding: 4px 11px; border-radius: var(--bp-r); }
  .st.pending { background: var(--bp-tint-amber); color: var(--bp-c-amber); }
  .st.accepted { background: var(--bp-tint-green); color: var(--bp-c-green); }
  .st.rejected { background: var(--bp-tint-red); color: var(--bp-c-red); }

  .filters { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 20px; flex-wrap: wrap; }
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
      <li><a class="sb-item active" href="/requests"><i class="ri-inbox-line"></i><span>درخواست‌ها</span></a></li>
      <li><a class="sb-item" href="/profile"><i class="ri-star-line"></i><span>مهارت‌ها</span></a></li>
      <li><a class="sb-item" href="/tickets"><i class="ri-customer-service-2-line"></i><span>تیکت‌ها</span></a></li>
    </ul>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button>درخواست‌ها</div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <a class="tb-user" href="/profile"><div class="av">ع</div><div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div><i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i></a>
      </div>
    </header>

    <div class="content">
      <div class="content-narrow">
        <div class="page-head">
          <div><h2>درخواست‌ها</h2><p>درخواست‌های دریافتی پروژه‌هایت و درخواست‌هایی که برای دیگران فرستاده‌ای.</p></div>
        </div>

        <div class="filters">
          <div class="seg">
            <button class="active" data-tab="received" onclick="switchTab('received', this)"><i class="ri-inbox-line"></i>دریافتی <span class="bp-badge bp-badge--soft" style="margin-inline-start:4px">۳</span></button>
            <button data-tab="sent" onclick="switchTab('sent', this)"><i class="ri-send-plane-line"></i>ارسالی <span class="bp-badge bp-badge--soft" style="margin-inline-start:4px">۲</span></button>
          </div>
          <select class="sel" style="max-width:200px"><option>همه وضعیت‌ها</option><option>در انتظار</option><option>پذیرفته‌شده</option><option>رد شده</option></select>
        </div>

        <div id="list"></div>
      </div>
    </div>
  </div>
</div>
@endverbatim
@endsection

@push('scripts')
@verbatim
<script>
  const RECEIVED = [
    ['س','var(--bp-teal)','طراحی مدار قدرت سه‌فاز','سارا احمدی · متخصص مهندسی برق','«با ۶ سال تجربه در شبیه‌سازی سیستم‌های قدرت آماده‌ی انجام این پروژه هستم.»','۷٬۵۰۰٬۰۰۰ تومان','pending','۲ ساعت پیش'],
    ['م','var(--bp-blue)','تحلیل ارتعاشات شفت توربین','مهدی رضایی · متخصص مکانیک','«نمونه‌کارهای مشابه دارم و مدل کامل ANSYS ارائه می‌دهم.»','۹٬۰۰۰٬۰۰۰ تومان','accepted','دیروز'],
    ['ر','var(--bp-c-amber)','مدل پیش‌بینی مصرف انرژی','رضا کریمی · متخصص داده','«پیشنهاد می‌کنم از مدل LSTM استفاده شود؛ دقت بالاتری دارد.»','۵٬۵۰۰٬۰۰۰ تومان','pending','۳ روز پیش'],
  ];
  const SENT = [
    ['پ','var(--bp-c-purple)','بهینه‌سازی چیدمان خط تولید','گروه صنعتی آرمان · کارفرما','«با تخصص در مهندسی صنایع و شبیه‌سازی Arena، آماده‌ی همکاری هستم.»','۶٬۰۰۰٬۰۰۰ تومان','pending','۱ روز پیش'],
    ['د','var(--bp-c-sky)','طراحی سیستم تهویه ساختمان','دانشگاه صنعتی · کارفرما','«تجربه‌ی طراحی HVAC برای پروژه‌های مشابه را دارم.»','۴٬۲۰۰٬۰۰۰ تومان','rejected','۵ روز پیش'],
  ];
  const ST = { pending:['در انتظار','ri-time-line'], accepted:['پذیرفته‌شده','ri-check-line'], rejected:['رد شده','ri-close-line'] };

  function rowHTML(r, sent) {
    return `<div class="rq">
      <div class="av" style="background:${r[1]}">${r[0]}</div>
      <div class="main2">
        <div class="top">
          <div><div class="pname">${r[2]}</div><div class="who"><i class="ri-user-line"></i>${r[3]}</div></div>
          <span class="st ${r[6]}"><i class="${ST[r[6]][1]}"></i>${ST[r[6]][0]}</span>
        </div>
        <p class="msg">${r[4]}</p>
        <div class="foot">
          <div style="display:flex; gap:16px; align-items:center">
            <span class="price">${sent?'پیشنهاد شما':'پیشنهاد'}: ${r[5]}</span>
            <span class="date"><i class="ri-time-line"></i>${r[7]}</span>
          </div>
          <div class="acts">
            ${sent
              ? `<button class="bp-btn bp-btn--ghost bp-btn--sm"><i class="ri-eye-line"></i>مشاهده پروژه</button>`
              : (r[6]==='pending'
                  ? `<button class="bp-btn bp-btn--ghost bp-btn--sm"><i class="ri-close-line"></i>رد</button><button class="bp-btn bp-btn--primary bp-btn--sm"><i class="ri-check-line"></i>پذیرش</button>`
                  : `<button class="bp-btn bp-btn--ghost bp-btn--sm"><i class="ri-chat-1-line"></i>گفتگو</button>`)}
          </div>
        </div>
      </div>
    </div>`;
  }
  function render(tab) {
    const data = tab==='received'?RECEIVED:SENT;
    document.getElementById('list').innerHTML = data.map(r => rowHTML(r, tab==='sent')).join('');
  }
  function switchTab(tab, btn) {
    document.querySelectorAll('.seg button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active'); render(tab);
  }
  render('received');
</script>
@endverbatim
@endpush
