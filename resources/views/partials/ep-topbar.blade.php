@php
    $pageTitle = $pageTitle ?? 'داشبورد';
    $user = auth()->user();
    $userName = $user?->name ?? $user?->mobile ?? 'کاربر';
    $userRole = 'کارفرما + متخصص';
@endphp
<header class="topbar">
    <div class="page-title">{{ $pageTitle }}</div>
    <div class="topbar-actions">
        <button class="icon-btn" aria-label="جستجو">
            <i class="ri-search-line"></i>
        </button>
        <button class="icon-btn" aria-label="اعلان‌ها">
            <i class="ri-notification-3-line"></i>
            <span class="dot"></span>
        </button>
        <div class="topbar-user">
            <div class="av" style="background: var(--ep-primary); color: #fff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem;">
                {{ mb_substr($userName, 0, 1) }}
            </div>
            <div>
                <div class="nm">{{ $userName }}</div>
                <div class="rl">{{ $userRole }}</div>
            </div>
            <i class="ri-arrow-down-s-line" style="color: var(--ep-muted);"></i>
        </div>
    </div>
</header>
