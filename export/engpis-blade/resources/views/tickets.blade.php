@extends('layouts.base')
@section('title', "EngPis — تیکت‌های پشتیبانی")

@push('head')
@verbatim
<style>
  .tk-grid { display: grid; grid-template-columns: 340px 1fr; gap: 20px; align-items: start; }
  @media (max-width: 940px) { .tk-grid { grid-template-columns: 1fr; } }

  .tk-list { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
  .tk-list .lh { padding: 16px 18px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; justify-content: space-between; }
  .tk-list .lh h5 { font-size: 1rem; }
  .tk { padding: 15px 18px; border-bottom: 1px solid var(--bp-hair); cursor: pointer; transition: background .15s; border-inline-start: 3px solid transparent; }
  .tk:last-child { border-bottom: 0; }
  .tk:hover { background: var(--bp-surface); }
  .tk.active { background: var(--bp-tint-blue); border-inline-start-color: var(--bp-blue); }
  .tk .t1 { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 5px; }
  .tk .subj { font-weight: 700; font-size: .9rem; color: var(--bp-ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .tk .prev { font-size: .8rem; color: var(--bp-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .tk .t2 { display: flex; align-items: center; justify-content: space-between; margin-top: 8px; }
  .tk .id { font-family: ui-monospace, monospace; font-size: .72rem; color: var(--bp-muted); }

  .st { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: var(--bp-r); flex: none; }
  .st.open { background: var(--bp-tint-green); color: var(--bp-c-green); }
  .st.wait { background: var(--bp-tint-amber); color: var(--bp-c-amber); }
  .st.closed { background: var(--bp-surface); color: var(--bp-muted); border: 1px solid var(--bp-border); }

  /* conversation */
  .conv { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); display: flex; flex-direction: column; min-height: 560px; }
  .conv .ch { padding: 18px 22px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
  .conv .ch h4 { font-size: 1.05rem; font-weight: 800; }
  .conv .ch .sub { font-size: .8rem; color: var(--bp-muted); font-family: ui-monospace, monospace; margin-top: 3px; }
  .conv .body { padding: 22px; flex: 1; display: flex; flex-direction: column; gap: 18px; background: var(--bp-surface); }
  .msg { display: flex; gap: 12px; max-width: 80%; }
  .msg .av { width: 38px; height: 38px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; flex: none; font-size: .9rem; }
  .msg .bub { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 12px 15px; }
  .msg .bub .nm { font-size: .78rem; font-weight: 700; color: var(--bp-ink); margin-bottom: 5px; display: flex; align-items: center; gap: 6px; }
  .msg .bub .tx { font-size: .9rem; color: var(--bp-text); line-height: 1.8; }
  .msg .bub .tm { font-size: .7rem; color: var(--bp-muted); margin-top: 6px; font-family: ui-monospace, monospace; }
  .msg.me { margin-inline-start: auto; flex-direction: row-reverse; }
  .msg.me .av { background: var(--bp-blue); }
  .msg.me .bub { background: var(--bp-blue); border-color: var(--bp-blue); }
  .msg.me .bub .nm, .msg.me .bub .tx { color: #fff; }
  .msg.me .bub .tm { color: rgba(255,255,255,.7); }
  .msg.agent .av { background: var(--bp-teal); }

  .composer { padding: 16px 20px; border-top: 1px solid var(--bp-hair); display: flex; gap: 10px; align-items: center; }
  .composer .inp { flex: 1; }
  .agent-tag { display: inline-flex; align-items: center; gap: 5px; font-size: .72rem; color: var(--bp-teal); font-weight: 700; }
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
      <li><a class="sb-item" href="/profile"><i class="ri-star-line"></i><span>مهارت‌ها</span></a></li>
      <li><a class="sb-item active" href="/tickets"><i class="ri-customer-service-2-line"></i><span>تیکت‌ها</span></a></li>
    </ul>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="pt"><button class="iconbtn burger" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="ri-menu-line"></i></button>تیکت‌های پشتیبانی</div>
      <div class="tb-act">
        <button class="iconbtn"><i class="ri-search-line"></i></button>
        <button class="iconbtn"><i class="ri-notification-3-line"></i><span class="dot"></span></button>
        <a class="tb-user" href="/profile"><div class="av">ع</div><div><div class="nm">علی محمدی</div><div class="rl">کارفرما + متخصص</div></div><i class="ri-arrow-down-s-line" style="color:var(--bp-muted)"></i></a>
      </div>
    </header>

    <div class="content">
      <div class="page-head">
        <div><h2>تیکت‌های پشتیبانی</h2><p>سؤال یا مشکلی داری؟ تیم EngPis همراه توست.</p></div>
        <button class="bp-btn bp-btn--primary"><i class="ri-add-line"></i>تیکت جدید</button>
      </div>

      <div class="tk-grid">
        <!-- list -->
        <div class="tk-list">
          <div class="lh"><h5>تیکت‌های من</h5><span class="bp-badge bp-badge--soft">۴</span></div>
          <div id="tklist"></div>
        </div>

        <!-- conversation -->
        <div class="conv">
          <div class="ch" id="convHead"></div>
          <div class="body" id="convBody"></div>
          <div class="composer">
            <div class="inp-icon" style="flex:1"><i class="ri-chat-1-line"></i><input class="inp" placeholder="پاسخت را بنویس..."></div>
            <button class="iconbtn"><i class="ri-attachment-2"></i></button>
            <button class="bp-btn bp-btn--primary"><i class="ri-send-plane-2-fill"></i>ارسال</button>
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
  const TICKETS = [
    ['TK-۱۰۲۴','مشکل در تأیید پرداخت پروژه','پرداخت من تأیید شده اما وضعیت پروژه تغییر نکرده...','open','پشتیبانی مالی'],
    ['TK-۱۰۱۹','سؤال درباره نحوه‌ی تطابق','الگوریتم تطابق بر چه اساسی پروژه‌ها را...','wait','راهنمایی عمومی'],
    ['TK-۱۰۰۵','درخواست تغییر حوزه تخصصی','می‌خواهم حوزه‌ی تخصصی پروفایلم را به...','closed','حساب کاربری'],
    ['TK-۰۹۸۷','عدم نمایش نمونه‌کار','نمونه‌کاری که آپلود کردم در پروفایل دیده...','closed','فنی'],
  ];
  const ST = { open:['باز','ri-checkbox-blank-circle-fill'], wait:['در انتظار پاسخ','ri-time-line'], closed:['بسته‌شده','ri-checkbox-circle-line'] };

  const CONV = {
    'TK-۱۰۲۴': {
      title: 'مشکل در تأیید پرداخت پروژه', dept: 'پشتیبانی مالی', status: 'open',
      msgs: [
        ['me','ع','علی محمدی','سلام، من برای پروژه‌ی «طراحی مدار قدرت» پرداخت را انجام دادم و رسید هم دارم، اما وضعیت پروژه هنوز «در انتظار پرداخت» است. ممکنه بررسی کنید؟','۱۴۰۴/۰۳/۱۸ — ۱۰:۲۴'],
        ['agent','پ','تیم پشتیبانی EngPis','سلام علی عزیز، ممنون از گزارشت. کد پیگیری پرداخت را بررسی کردیم؛ تراکنش با موفقیت ثبت شده و در حال هماهنگی با درگاه هستیم. حداکثر تا ۲ ساعت آینده وضعیت پروژه به‌روزرسانی می‌شود.','۱۴۰۴/۰۳/۱۸ — ۱۰:۴۱'],
        ['me','ع','علی محمدی','عالیه، ممنون از پیگیری سریع‌تون 🙏','۱۴۰۴/۰۳/۱۸ — ۱۰:۴۵'],
      ],
    },
  };

  function renderList(activeId) {
    document.getElementById('tklist').innerHTML = TICKETS.map(t => `
      <div class="tk ${t[0]===activeId?'active':''}" onclick="openTicket('${t[0]}')">
        <div class="t1"><span class="subj">${t[1]}</span><span class="st ${t[3]}"><i class="${ST[t[3]][1]}"></i></span></div>
        <div class="prev">${t[2]}</div>
        <div class="t2"><span class="id">${t[0]}</span><span class="bp-badge bp-badge--mono">${t[4]}</span></div>
      </div>`).join('');
  }
  function openTicket(id) {
    renderList(id);
    const c = CONV[id] || { title: TICKETS.find(t=>t[0]===id)[1], dept: TICKETS.find(t=>t[0]===id)[4], status: TICKETS.find(t=>t[0]===id)[3], msgs: [['agent','پ','تیم پشتیبانی EngPis','این تیکت بسته شده است. در صورت نیاز تیکت جدیدی ثبت کنید.','—']] };
    document.getElementById('convHead').innerHTML = `
      <div><h4>${c.title}</h4><div class="sub">${id} · ${c.dept}</div></div>
      <span class="st ${c.status}"><i class="${ST[c.status][1]}"></i>${ST[c.status][0]}</span>`;
    document.getElementById('convBody').innerHTML = c.msgs.map(m => `
      <div class="msg ${m[0]}">
        <div class="av">${m[1]}</div>
        <div class="bub"><div class="nm">${m[2]} ${m[0]==='agent'?'<span class="agent-tag"><i class="ri-customer-service-2-fill"></i>پشتیبانی</span>':''}</div><div class="tx">${m[3]}</div><div class="tm">${m[4]}</div></div>
      </div>`).join('');
  }
  openTicket('TK-۱۰۲۴');
</script>
@endverbatim
@endpush
