<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>EngPis — مارکت‌پلیس تخصصی پروژه‌های مهندسی</title>
  <link rel="stylesheet" href="{{ asset('vendor/engpis/fonts/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/engpis/fonts/vazirmatn.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/engpis/css/blueprint.css') }}">
  <style>
  /* ── Navbar ── */
  .nav { position: fixed; inset-inline: 0; top: 0; z-index: 100; transition: background .3s, box-shadow .3s, border-color .3s; border-bottom: 1px solid transparent; }
  .nav .row { height: var(--bp-nav-h); display: flex; align-items: center; justify-content: space-between; gap: 16px; }
  .nav.pinned { background: rgba(255,255,255,.96); backdrop-filter: blur(10px); box-shadow: var(--bp-sh-sm); border-color: var(--bp-hair); }
  .brand { font-size: 1.6rem; font-weight: 900; color: #fff; letter-spacing: -.5px; }
  .nav.pinned .brand { color: var(--bp-ink); }
  .brand .a { color: var(--bp-blue-l); }
  .nav.pinned .brand .a { color: var(--bp-blue); }
  .nav-links { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; }
  .nav-links a { color: rgba(255,255,255,.82); font-size: .95rem; font-weight: 500; padding: 8px 14px; border-radius: var(--bp-r); transition: all .2s; }
  .nav.pinned .nav-links a { color: var(--bp-text); }
  .nav-links a:hover { color: #fff; background: rgba(255,255,255,.1); }
  .nav.pinned .nav-links a:hover { color: var(--bp-blue); background: var(--bp-tint-blue); }
  .nav-act { display: flex; align-items: center; gap: 12px; }
  .nav-login { color: #fff; font-weight: 600; font-size: .95rem; cursor: pointer; }
  .nav.pinned .nav-login { color: var(--bp-ink); }
  @media (max-width: 880px) { .nav-links { display: none; } }

  /* ── Hero ── */
  .hero { position: relative; background: var(--bp-navy); color: #fff; overflow: hidden; padding-top: var(--bp-nav-h); }
  .hero .grid-bg { position: absolute; inset: 0; opacity: .8; }
  .hero .glow { position: absolute; border-radius: 50%; filter: blur(10px); }
  .hero .g1 { top: -120px; inset-inline-start: -80px; width: 380px; height: 380px; background: radial-gradient(circle, rgba(31,111,235,.35), transparent 70%); }
  .hero .g2 { bottom: -140px; inset-inline-end: 10%; width: 360px; height: 360px; background: radial-gradient(circle, rgba(0,184,169,.22), transparent 70%); }
  .hero-grid { position: relative; z-index: 2; display: grid; grid-template-columns: 1.05fr .95fr; gap: 48px; align-items: center; padding: 72px 0 96px; }
  @media (max-width: 920px) { .hero-grid { grid-template-columns: 1fr; } .hero-art { display: none; } }
  .hero h1 { font-size: clamp(2.1rem, 4.2vw, 3.1rem); font-weight: 900; line-height: 1.32; color: #fff; margin: 22px 0 18px; }
  .hero h1 .hl { color: var(--bp-blue-l); position: relative; }
  .hero .lead { font-size: 1.12rem; color: rgba(255,255,255,.74); max-width: 500px; margin-bottom: 30px; }
  .hero-cta { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 34px; }
  .trust { display: flex; flex-wrap: wrap; gap: 24px; }
  .trust span { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,.62); font-size: .9rem; }
  .trust i { font-size: 1.2rem; }

  /* hero technical panel */
  .hero-art { position: relative; }
  .panel { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.12); border-radius: var(--bp-r-xl); padding: 20px; backdrop-filter: blur(8px); }
  .panel-top { display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,.1); margin-bottom: 14px; }
  .panel-top .dot { width: 9px; height: 9px; border-radius: 50%; background: var(--bp-teal); box-shadow: 0 0 0 4px rgba(0,184,169,.18); }
  .panel-top .lbl { font-size: .8rem; color: rgba(255,255,255,.6); font-family: ui-monospace, monospace; }
  .match { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: var(--bp-r-lg); background: rgba(255,255,255,.05); margin-bottom: 10px; transition: background .2s; }
  .match:hover { background: rgba(31,111,235,.18); }
  .match .ic { width: 40px; height: 40px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex: none; }
  .match .t { font-size: .9rem; font-weight: 700; color: #fff; }
  .match .s { font-size: .74rem; color: rgba(255,255,255,.55); }
  .match .pct { margin-inline-start: auto; font-family: ui-monospace, monospace; font-weight: 700; color: var(--bp-teal); font-size: .95rem; }

  /* ── Stats ── */
  .stats { position: relative; z-index: 3; margin-top: -44px; }
  .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; }
  @media (max-width: 760px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
  .statbox { background: #fff; border: 1px solid var(--bp-border); border-top: 3px solid var(--bp-blue); border-radius: 0 0 var(--bp-r-lg) var(--bp-r-lg); padding: 22px 24px; box-shadow: var(--bp-sh-sm); }
  .statbox:nth-child(2) { border-top-color: var(--bp-teal); }
  .statbox:nth-child(3) { border-top-color: var(--bp-c-amber); }
  .statbox:nth-child(4) { border-top-color: var(--bp-c-purple); }
  .statbox .n { font-size: 2.1rem; font-weight: 900; color: var(--bp-ink); font-feature-settings: "tnum"; }
  .statbox .l { color: var(--bp-muted); font-size: .9rem; }

  /* ── How it works ── */
  .tabs { display: flex; justify-content: center; margin-bottom: 44px; }
  .tabnav { display: inline-flex; gap: 4px; background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 5px; }
  .tabbtn { border: none; background: transparent; font-family: inherit; font-weight: 700; font-size: .95rem; color: var(--bp-muted); padding: 10px 24px; border-radius: var(--bp-r); cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 7px; }
  .tabbtn.active { background: var(--bp-blue); color: #fff; box-shadow: var(--bp-sh-blue); }
  .steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 22px; }
  @media (max-width: 860px) { .steps { grid-template-columns: 1fr; } }
  .step { position: relative; padding: 30px 26px; }
  .step .num { position: absolute; top: 22px; inset-inline-end: 24px; font-family: ui-monospace, monospace; font-size: 2.4rem; font-weight: 900; color: var(--bp-hair); line-height: 1; }
  .step:hover { transform: translateY(-4px); box-shadow: var(--bp-sh-md); border-color: var(--bp-blue); }
  .step h4 { font-size: 1.15rem; margin: 18px 0 8px; }
  .step p { color: var(--bp-muted); font-size: .92rem; }

  /* ── Domains ── */
  .dgrid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
  @media (max-width: 900px) { .dgrid { grid-template-columns: repeat(2,1fr); } }
  .domain { padding: 24px 20px; text-align: center; cursor: pointer; }
  .domain:hover { transform: translateY(-4px); box-shadow: var(--bp-sh-md); border-color: var(--bp-blue); }
  .domain .bp-icon-tile { margin: 0 auto 14px; }
  .domain h5 { font-size: 1.02rem; margin-bottom: 4px; }
  .domain small { color: var(--bp-muted); }

  /* ── Features ── */
  .fgrid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
  @media (max-width: 900px) { .fgrid { grid-template-columns: 1fr; } }
  .feature { padding: 28px; }
  .feature:hover { transform: translateY(-3px); box-shadow: var(--bp-sh-md); }
  .feature .bp-icon-tile { margin-bottom: 18px; }
  .feature h5 { font-size: 1.1rem; margin-bottom: 8px; }
  .feature p { color: var(--bp-muted); font-size: .92rem; }

  /* ── Testimonials ── */
  .tgrid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
  @media (max-width: 900px) { .tgrid { grid-template-columns: 1fr; } }
  .testi { padding: 28px; }
  .testi .stars { color: #E0930B; letter-spacing: 2px; margin-bottom: 14px; }
  .testi p { color: var(--bp-text); font-size: .95rem; line-height: 1.85; margin-bottom: 20px; }
  .person { display: flex; align-items: center; gap: 12px; }
  .person .av { width: 46px; height: 46px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; }
  .person .nm { font-weight: 700; color: var(--bp-ink); font-size: .92rem; }
  .person .rl { color: var(--bp-muted); font-size: .8rem; }

  /* ── CTA ── */
  .cta-wrap { position: relative; background: var(--bp-navy); border-radius: var(--bp-r-xl); padding: 60px 40px; text-align: center; color: #fff; overflow: hidden; }
  .cta-wrap .grid-bg { position: absolute; inset: 0; opacity: .7; }
  .cta-wrap h2 { position: relative; font-size: clamp(1.7rem,3vw,2.3rem); font-weight: 900; color: #fff; margin-bottom: 12px; }
  .cta-wrap p { position: relative; color: rgba(255,255,255,.72); font-size: 1.1rem; margin-bottom: 28px; }
  .cta-row { position: relative; display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

  /* ── Footer ── */
  .footer { background: var(--bp-navy); color: rgba(255,255,255,.6); padding: 60px 0 26px; }
  .fgrid2 { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 28px; }
  @media (max-width: 800px) { .fgrid2 { grid-template-columns: 1fr 1fr; } }
  .footer .brand2 { font-size: 1.5rem; font-weight: 900; color: #fff; }
  .footer .brand2 .a { color: var(--bp-blue-l); }
  .footer p { font-size: .9rem; line-height: 1.8; margin: 14px 0; }
  .footer h6 { color: #fff; font-size: .95rem; margin-bottom: 14px; }
  .footer ul { list-style: none; padding: 0; margin: 0; }
  .footer li { margin-bottom: 10px; }
  .footer li a { font-size: .88rem; transition: color .2s; }
  .footer li a:hover { color: #fff; }
  .socials { display: flex; gap: 8px; margin-top: 8px; }
  .socials a { width: 38px; height: 38px; border-radius: var(--bp-r); background: rgba(255,255,255,.08); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.7); transition: all .2s; }
  .socials a:hover { background: var(--bp-blue); color: #fff; }
  .fbottom { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding-top: 22px; margin-top: 36px; border-top: 1px solid rgba(255,255,255,.1); font-size: .84rem; }
  </style>
</head>
<body>
<nav class="nav" id="nav">
  <div class="bp-container row">
    <a class="brand" href="{{ route('root') }}"><span class="a">Eng</span>Pis</a>
    <ul class="nav-links">
      <li><a href="#how">چگونه کار می‌کند</a></li>
      <li><a href="#domains">حوزه‌ها</a></li>
      <li><a href="#features">مزایا</a></li>
      <li><a href="#testi">نظرات</a></li>
    </ul>
    <div class="nav-act">
      <a class="nav-login" href="{{ route('login') }}">ورود</a>
      <a class="bp-btn bp-btn--primary bp-btn--sm" href="{{ route('register') }}">ثبت‌نام رایگان</a>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="grid-bg bp-grid"></div>
  <div class="glow g1"></div>
  <div class="glow g2"></div>
  <div class="bp-container hero-grid">
    <div>
      <span class="bp-eyebrow on-dark"><i class="ri-focus-2-line"></i>بزرگ‌ترین مارکت‌پلیس مهندسی ایران</span>
      <h1>پروژه مهندسی‌ات را به<br><span class="hl">متخصص واقعی</span> بسپار</h1>
      <p class="lead">اتصال دقیق کارفرما و متخصص بر پایه مهارت، ابزار و سطح تجربه — در حوزه‌های برق، مکانیک، عمران، کامپیوتر و بیشتر.</p>
      <div class="hero-cta">
        <a class="bp-btn bp-btn--primary bp-btn--lg" href="{{ route('guest.project') }}"><i class="ri-add-circle-line"></i>ثبت پروژه</a>
        <a class="bp-btn bp-btn--ghost-d bp-btn--lg" href="{{ route('register') }}"><i class="ri-search-line"></i>جستجوی متخصص</a>
      </div>
      <div class="trust">
        <span><i class="ri-shield-check-line" style="color:var(--bp-teal)"></i>پرداخت امن</span>
        <span><i class="ri-verified-badge-line" style="color:var(--bp-blue-l)"></i>متخصصان تأیید شده</span>
        <span><i class="ri-customer-service-2-line" style="color:#E0930B"></i>پشتیبانی ۲۴/۷</span>
      </div>
    </div>
    <div class="hero-art">
      <div class="panel">
        <div class="panel-top">
          <span class="lbl">// تطابق هوشمند</span>
          <span class="dot"></span>
        </div>
        <div class="match">
          <div class="ic" style="background:rgba(31,111,235,.2);color:var(--bp-blue-l)"><i class="ri-flashlight-line"></i></div>
          <div><div class="t">طراحی مدار قدرت سه‌فاز</div><div class="s">MATLAB · ETAP · مهندسی برق</div></div>
          <span class="pct">٪۹۶</span>
        </div>
        <div class="match">
          <div class="ic" style="background:rgba(0,184,169,.2);color:var(--bp-teal)"><i class="ri-settings-4-line"></i></div>
          <div><div class="t">تحلیل ارتعاشات شفت توربین</div><div class="s">ANSYS · SolidWorks · مکانیک</div></div>
          <span class="pct">٪۹۱</span>
        </div>
        <div class="match">
          <div class="ic" style="background:rgba(124,92,219,.2);color:#a48bf0"><i class="ri-code-s-slash-line"></i></div>
          <div><div class="t">مدل پیش‌بینی مصرف انرژی</div><div class="s">Python · TensorFlow · کامپیوتر</div></div>
          <span class="pct">٪۸۸</span>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="stats">
  <div class="bp-container stats-grid">
    <div class="statbox"><div class="n">{{ $projectsCount }}+</div><div class="l">پروژه ثبت شده</div></div>
    <div class="statbox"><div class="n">{{ $specialistsCount }}+</div><div class="l">متخصص فعال</div></div>
    <div class="statbox"><div class="n">{{ $employersCount }}+</div><div class="l">کارفرمای راضی</div></div>
    <div class="statbox"><div class="n">{{ $domainsCount }}+</div><div class="l">حوزه تخصصی</div></div>
  </div>
</section>

<section class="bp-section" id="how">
  <div class="bp-container">
    <div class="bp-sechead">
      <span class="bp-eyebrow"><i class="ri-route-line"></i>روش کار</span>
      <h2>چگونه EngPis کار می‌کند؟</h2>
      <p>فرآیند ساده و شفاف — برای هر دو طرف</p>
    </div>
    @php
        $steps = [
            'employer' => [
                ['ri-file-add-line', '--bp-blue', '--bp-tint-blue', 'پروژه خود را ثبت کنید', 'توضیحات فنی، حوزه تخصصی، بودجه و زمان‌بندی پروژه‌تان را وارد کنید.'],
                ['ri-user-search-line', '--bp-teal', '--bp-tint-teal', 'متخصصان پیشنهادی را ببینید', 'سیستم هوشمند، متخصصانی که با پروژه‌تان مطابقت دارند را معرفی می‌کند.'],
                ['ri-shake-hands-line', '--bp-c-amber', '--bp-tint-amber', 'همکاری را آغاز کنید', 'بهترین متخصص را انتخاب کنید و پروژه‌تان را به سرانجام برسانید.'],
            ],
            'specialist' => [
                ['ri-profile-line', '--bp-blue', '--bp-tint-blue', 'پروفایل تخصصی بسازید', 'مهارت‌ها، حوزه‌ها و سطح توانایی خود را وارد کنید تا پروژه‌های متناسب پیدا شوند.'],
                ['ri-search-eye-line', '--bp-teal', '--bp-tint-teal', 'پروژه‌های مناسب بیابید', 'الگوریتم ما پروژه‌های منطبق با مهارت‌هایتان را خودکار نمایش می‌دهد.'],
                ['ri-send-plane-2-line', '--bp-c-amber', '--bp-tint-amber', 'درخواست همکاری بدهید', 'روی پروژه مورد نظر درخواست ارسال کنید و منتظر تأیید کارفرما باشید.'],
            ],
        ];
        $domainStyles = [
            'مهندسی برق' => ['ri-flashlight-line', '--bp-c-amber', '--bp-tint-amber'],
            'مهندسی مکانیک' => ['ri-settings-4-line', '--bp-blue', '--bp-tint-blue'],
            'مهندسی عمران' => ['ri-building-2-line', '--bp-teal', '--bp-tint-teal'],
            'مهندسی کامپیوتر' => ['ri-code-s-slash-line', '--bp-c-green', '--bp-tint-green'],
            'مهندسی شیمی' => ['ri-flask-line', '--bp-c-red', '--bp-tint-red'],
            'مهندسی صنایع' => ['ri-bar-chart-grouped-line', '--bp-c-purple', '--bp-tint-purple'],
            'مهندسی هوافضا' => ['ri-flight-takeoff-line', '--bp-c-sky', '--bp-tint-sky'],
            'مهندسی معدن' => ['ri-hammer-line', '--bp-c-orange', '--bp-tint-orange'],
            'مهندسی معماری' => ['ri-artboard-2-line', '--bp-blue', '--bp-tint-blue'],
            'مهندسی شهرسازی' => ['ri-community-line', '--bp-teal', '--bp-tint-teal'],
            'مهندسی نفت' => ['ri-drop-line', '--bp-c-amber', '--bp-tint-amber'],
            'مهندسی محیط زیست' => ['ri-leaf-line', '--bp-c-green', '--bp-tint-green'],
            'مهندسی پزشکی (بیومدیکال)' => ['ri-heart-pulse-line', '--bp-c-red', '--bp-tint-red'],
            'مهندسی متالورژی و مواد' => ['ri-magnet-line', '--bp-c-purple', '--bp-tint-purple'],
            'مهندسی نقشه‌برداری' => ['ri-map-2-line', '--bp-teal', '--bp-tint-teal'],
            'میان‌رشته‌ای' => ['ri-shapes-line', '--bp-c-green', '--bp-tint-green'],
        ];
        $domainColorCycle = ['--bp-blue|--bp-tint-blue', '--bp-teal|--bp-tint-teal', '--bp-c-amber|--bp-tint-amber', '--bp-c-green|--bp-tint-green', '--bp-c-red|--bp-tint-red', '--bp-c-purple|--bp-tint-purple', '--bp-c-sky|--bp-tint-sky', '--bp-c-orange|--bp-tint-orange'];
    @endphp
    <div class="tabs">
      <div class="tabnav">
        <button class="tabbtn active" data-tab="employer"><i class="ri-building-4-line"></i>کارفرمایان</button>
        <button class="tabbtn" data-tab="specialist"><i class="ri-user-star-line"></i>متخصصان</button>
      </div>
    </div>
    @foreach ($steps as $tab => $tabSteps)
      <div class="steps" id="steps-{{ $tab }}" @if ($tab !== 'employer') style="display:none" @endif>
        @foreach ($tabSteps as $i => $s)
          <div class="bp-card step">
            <span class="num">۰{{ $i + 1 }}</span>
            <div class="bp-icon-tile" style="background:var({{ $s[2] }});color:var({{ $s[1] }})"><i class="{{ $s[0] }}"></i></div>
            <h4>{{ $s[3] }}</h4><p>{{ $s[4] }}</p>
          </div>
        @endforeach
      </div>
    @endforeach
  </div>
</section>

<section class="bp-section wash" id="domains">
  <div class="bp-container">
    <div class="bp-sechead">
      <span class="bp-eyebrow"><i class="ri-stack-line"></i>حوزه‌های تخصصی</span>
      <h2>در کدام رشته مهندسی فعالیت دارید؟</h2>
      <p>EngPis طیف گسترده‌ای از رشته‌های مهندسی را پوشش می‌دهد</p>
    </div>
    <div class="dgrid" id="dgrid">
      @foreach ($domains as $i => $domain)
        @php
            [$icon, $color, $tint] = $domainStyles[$domain->name] ?? array_merge(['ri-tools-line'], explode('|', $domainColorCycle[$i % count($domainColorCycle)]));
        @endphp
        <div class="bp-card domain">
          <div class="bp-icon-tile" style="background:var({{ $tint }});color:var({{ $color }})"><i class="{{ $icon }}"></i></div>
          <h5>{{ $domain->name }}</h5>
          <small>{{ $domain->subdomains_count }} زیررشته</small>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bp-section" id="features">
  <div class="bp-container">
    <div class="bp-sechead">
      <span class="bp-eyebrow"><i class="ri-shield-star-line"></i>چرا EngPis؟</span>
      <h2>مزایایی که EngPis را متمایز می‌کند</h2>
    </div>
    <div class="fgrid" id="fgrid">
      @foreach ([
          ['ri-cpu-line', '--bp-blue', '--bp-tint-blue', 'تطابق هوشمند', 'الگوریتم ما پروژه‌ها را بر اساس مهارت، سطح تجربه و حوزه تخصصی با دقت بالا تطبیق می‌دهد.'],
          ['ri-shield-keyhole-line', '--bp-teal', '--bp-tint-teal', 'پرداخت امن', 'وجه پروژه تا تأیید نهایی نزد EngPis نگه‌داری می‌شود. بدون ریسک برای هیچ طرفی.'],
          ['ri-user-follow-line', '--bp-c-amber', '--bp-tint-amber', 'متخصصان تأیید شده', 'هویت، مدارک تحصیلی و سابقه کاری متخصصان پیش از فعالیت احراز می‌شود.'],
          ['ri-line-chart-line', '--bp-c-green', '--bp-tint-green', 'رشد مستمر', 'با هر پروژه موفق، پروفایل و اعتبار شما رشد می‌کند و فرصت‌های بهتری باز می‌شود.'],
          ['ri-customer-service-2-line', '--bp-c-red', '--bp-tint-red', 'پشتیبانی تخصصی', 'تیم پشتیبانی از آغاز تا پایان پروژه همراه شماست و در حل اختلافات میانجی است.'],
          ['ri-flashlight-line', '--bp-c-purple', '--bp-tint-purple', 'سرعت و سادگی', 'ثبت پروژه تنها چند دقیقه طول می‌کشد. بدون بروکراسی، بدون پیچیدگی.'],
      ] as $f)
        <div class="bp-card feature">
          <div class="bp-icon-tile" style="background:var({{ $f[2] }});color:var({{ $f[1] }})"><i class="{{ $f[0] }}"></i></div>
          <h5>{{ $f[3] }}</h5><p>{{ $f[4] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bp-section wash" id="testi">
  <div class="bp-container">
    <div class="bp-sechead">
      <span class="bp-eyebrow"><i class="ri-chat-quote-line"></i>نظرات کاربران</span>
      <h2>آنچه کاربران درباره EngPis می‌گویند</h2>
    </div>
    <div class="tgrid" id="tgrid">
      @foreach ([
          ['--bp-blue', '«از طریق EngPis یک متخصص MATLAB عالی برای شبیه‌سازی سیستم کنترل پیدا کردم. فرآیند ساده بود و نتیجه فوق‌العاده.»', 'علی محمدی', 'کارفرما · مهندسی مکانیک', 'ع'],
          ['--bp-teal', '«به عنوان متخصص ANSYS چندین پروژه موفق انجام داده‌ام. سیستم تطابق خیلی دقیق کار می‌کند و پروژه‌های مناسب پیشنهاد می‌دهد.»', 'سارا احمدی', 'متخصص · مهندسی عمران', 'س'],
          ['--bp-c-amber', '«پروژه پایان‌نامه‌ام را با یک متخصص Python تکمیل کردم. سریع، حرفه‌ای و قیمت منصفانه. قطعاً دوباره استفاده می‌کنم.»', 'رضا کریمی', 'کارفرما · دانشجوی دکترا', 'ر'],
      ] as $t)
        <div class="bp-card testi">
          <div class="stars">★★★★★</div><p>{{ $t[1] }}</p>
          <div class="person">
            <div class="av" style="background:var({{ $t[0] }})">{{ $t[4] }}</div>
            <div><div class="nm">{{ $t[2] }}</div><div class="rl">{{ $t[3] }}</div></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="bp-section" style="padding-top:0">
  <div class="bp-container">
    <div class="cta-wrap">
      <div class="grid-bg bp-grid"></div>
      <h2>آماده شروع هستید؟</h2>
      <p>همین حالا رایگان ثبت‌نام کنید و اولین قدم را بردارید</p>
      <div class="cta-row">
        <a class="bp-btn bp-btn--primary bp-btn--lg" href="{{ route('guest.project') }}"><i class="ri-add-circle-line"></i>ثبت پروژه مهندسی</a>
        <a class="bp-btn bp-btn--ghost-d bp-btn--lg" href="{{ route('register') }}"><i class="ri-briefcase-4-line"></i>جستجوی کار مهندسی</a>
      </div>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="bp-container">
    <div class="fgrid2">
      <div>
        <div class="brand2"><span class="a">Eng</span>Pis</div>
        <p>بزرگ‌ترین مارکت‌پلیس تخصصی پروژه‌های فنی و مهندسی ایران. جایی که کارفرمایان و متخصصان مهندسی به هم وصل می‌شوند.</p>
        <div class="socials">
          <a href="#"><i class="ri-instagram-line"></i></a>
          <a href="#"><i class="ri-linkedin-box-line"></i></a>
          <a href="#"><i class="ri-telegram-line"></i></a>
          <a href="#"><i class="ri-twitter-x-line"></i></a>
        </div>
      </div>
      <div><h6>EngPis</h6><ul><li><a href="{{ route('about') }}">درباره ما</a></li><li><a href="{{ route('terms') }}">قوانین</a></li><li><a href="#">تماس</a></li><li><a href="#">وبلاگ</a></li></ul></div>
      <div><h6>کارفرمایان</h6><ul><li><a href="{{ route('guest.project') }}">ثبت پروژه</a></li><li><a href="#how">نحوه کار</a></li><li><a href="#">تعرفه‌ها</a></li><li><a href="#">سوالات</a></li></ul></div>
      <div><h6>متخصصان</h6><ul><li><a href="{{ route('register') }}">ثبت‌نام</a></li><li><a href="{{ route('register') }}">یافتن پروژه</a></li><li><a href="#">راهنما</a></li><li><a href="#">سوالات</a></li></ul></div>
    </div>
    <div class="fbottom">
      <span>© ۱۴۰۴ EngPis. تمامی حقوق محفوظ است.</span>
      <span>ساخته‌شده برای جامعه مهندسی ایران</span>
    </div>
  </div>
</footer>

<script>
  const nav = document.getElementById('nav');
  const onScroll = () => nav.classList.toggle('pinned', window.scrollY > 50);
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();

  document.querySelectorAll('.tabbtn').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('.tabbtn').forEach(x => x.classList.remove('active'));
    b.classList.add('active');
    document.querySelectorAll('.steps').forEach(el => el.style.display = (el.id === 'steps-' + b.dataset.tab) ? '' : 'none');
  }));
</script>
</body>
</html>
