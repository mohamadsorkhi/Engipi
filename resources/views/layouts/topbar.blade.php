@auth
@php
$topUser=Auth::user(); $topRole=session('active_role');
$pageTitle=trim($__env->yieldContent('title')) ?: 'داشبورد';
$quickRoute=null; $quickLabel=null; $quickIcon='ri-add-line';
if(!$topUser->is_admin && $topRole==='employer' && Route::has('user.projects.create')){$quickRoute='user.projects.create';$quickLabel='ثبت پروژه';}
elseif(!$topUser->is_admin && $topRole==='specialist' && Route::has('user.matched-projects.index')){$quickRoute='user.matched-projects.index';$quickLabel='پروژه‌های متناسب';$quickIcon='ri-compass-3-line';}
elseif($topUser->is_admin && Route::has('admin.users.index')){$quickRoute='admin.users.index';$quickLabel='مدیریت کاربران';$quickIcon='ri-team-line';}
$topRoleLabel=$topUser->is_admin?'مدیر سامانه':($topRole==='employer'?'کارفرما':($topRole==='specialist'?'متخصص':'کاربر'));
@endphp
<header class="engipi-topbar" id="page-topbar">
 <button type="button" class="engipi-icon-button engipi-mobile-trigger" id="topnav-hamburger-icon" aria-label="باز کردن منو" aria-controls="engipiSidebar" aria-expanded="false"><i class="ri-menu-3-line"></i></button>
 <div class="engipi-page-heading"><span>فضای کاری EngiPi</span><strong>{{ $pageTitle }}</strong></div>
 <div class="engipi-header-search" aria-label="جستجو به‌زودی"><i class="ri-search-line"></i><input type="search" placeholder="جستجو در پنل" disabled aria-label="جستجو در پنل؛ به‌زودی"><span>به‌زودی</span></div>
 <div class="engipi-topbar-actions">
  @if($quickRoute)<a href="{{ route($quickRoute) }}" class="engipi-quick-action"><i class="{{ $quickIcon }}"></i><span>{{ $quickLabel }}</span></a>@endif
  @if(!$topUser->is_admin && Route::has('user.messages.index'))<a href="{{ route('user.messages.index') }}" class="engipi-icon-button" aria-label="پیام‌ها"><i class="ri-message-3-line"></i></a>@endif
  @if(!$topUser->is_admin && Route::has('user.tickets.index'))<a href="{{ route('user.tickets.index') }}" class="engipi-icon-button" aria-label="تیکت‌ها"><i class="ri-customer-service-2-line"></i></a>@endif
  <div class="engipi-user-menu">
   <button type="button" id="engipiUserMenuButton" class="engipi-user-trigger" aria-expanded="false" aria-controls="engipiUserMenu"><span class="engipi-header-avatar">@if($topUser->avatar)<img src="{{ asset('images/'.$topUser->avatar) }}" alt="">@else{{ mb_substr($topUser->name,0,1) }}@endif</span><span><strong>{{ $topUser->name }}</strong><small>{{ $topRoleLabel }}</small></span><i class="ri-arrow-down-s-line"></i></button>
   <div class="engipi-user-dropdown" id="engipiUserMenu" hidden>
    <div class="engipi-dropdown-identity"><strong>{{ $topUser->name }}</strong><span>{{ $topUser->email }}</span></div>
    @if(!$topUser->is_admin && Route::has('profile.select'))<a href="{{ route('profile.select') }}"><i class="ri-user-settings-line"></i>پروفایل و نقش</a>@endif
    @if(!$topUser->is_admin && $topUser->profiles->count()>1 && Route::has('profile.select'))<a href="{{ route('profile.select') }}"><i class="ri-swap-2-line"></i>تغییر نقش</a>@endif
    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><i class="ri-logout-box-r-line"></i>خروج امن</button></form>
   </div>
  </div>
 </div>
</header>
@endauth