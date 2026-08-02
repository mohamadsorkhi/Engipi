@auth
@php
$user=Auth::user();$isAdmin=(bool)$user->is_admin;$profiles=$user->profiles;$activeRole=session('active_role');$hasEmployer=$profiles->contains('type','employer');$hasSpecialist=$profiles->contains('type','specialist');$roleLabel=$isAdmin?'مدیر سامانه':($activeRole==='employer'?'کارفرما':($activeRole==='specialist'?'متخصص':'کاربر'));$sections=[];
if($isAdmin){$sections=[
['label'=>'نمای کلی','items'=>[['route'=>'admin.dashboard','label'=>'داشبورد مدیریت','icon'=>'ri-layout-grid-line','active'=>'admin.dashboard']]],
['label'=>'مدیریت','items'=>[['route'=>'admin.users.index','label'=>'کاربران','icon'=>'ri-group-line','active'=>'admin.users.*'],['route'=>'admin.profiles.index','label'=>'پروفایل‌ها','icon'=>'ri-id-card-line','active'=>'admin.profiles.*'],['route'=>'admin.projects.index','label'=>'پروژه‌ها','icon'=>'ri-briefcase-4-line','active'=>'admin.projects.*']]],
['label'=>'داده‌های تخصصی','items'=>[['route'=>'admin.skills.index','label'=>'مهارت‌ها','icon'=>'ri-tools-line','active'=>'admin.skills.*'],['route'=>'admin.skill-suggestions.index','label'=>'پیشنهاد مهارت‌ها','icon'=>'ri-lightbulb-flash-line','active'=>'admin.skill-suggestions.*'],['route'=>'admin.domains.index','label'=>'حوزه‌های تخصصی','icon'=>'ri-stack-line','active'=>'admin.domains.*'],['route'=>'admin.subdomains.index','label'=>'زیرحوزه‌ها','icon'=>'ri-node-tree','active'=>'admin.subdomains.*'],['route'=>'admin.processes.index','label'=>'فرآیندها','icon'=>'ri-flow-chart','active'=>'admin.processes.*']]],
['label'=>'سیستم و پشتیبانی','items'=>[['route'=>'admin.tickets.index','label'=>'تیکت‌ها','icon'=>'ri-customer-service-2-line','active'=>'admin.tickets.*'],['route'=>'admin.ticket-departments.index','label'=>'واحدهای پشتیبانی','icon'=>'ri-building-2-line','active'=>'admin.ticket-departments.*']]]];
}else{
$sections[]=['label'=>'اصلی','items'=>[['route'=>'root','label'=>'داشبورد','icon'=>'ri-layout-grid-line','active'=>'root|user.dashboard|specialist.dashboard|employer.dashboard']]];
if($hasEmployer&&$activeRole==='employer')$sections[]=['label'=>'پروژه‌ها','items'=>[['route'=>'user.projects.index','label'=>'پروژه‌های من','icon'=>'ri-briefcase-line','active'=>'user.projects.index|user.projects.show|user.projects.edit'],['route'=>'user.projects.create','label'=>'ثبت پروژه جدید','icon'=>'ri-add-circle-line','active'=>'user.projects.create'],['route'=>'user.requests.received','label'=>'درخواست‌های دریافتی','icon'=>'ri-inbox-archive-line','active'=>'user.requests.received']]];
if($hasSpecialist&&$activeRole==='specialist'){$sections[]=['label'=>'پروژه‌ها','items'=>[['route'=>'user.matched-projects.index','label'=>'پروژه‌های متناسب','icon'=>'ri-links-line','active'=>'user.matched-projects.*'],['route'=>'user.requests.sent','label'=>'درخواست‌های ارسالی','icon'=>'ri-send-plane-line','active'=>'user.requests.sent']]];$sections[]=['label'=>'مهارت‌ها','items'=>[['route'=>'user.skills.index','label'=>'مهارت‌های من','icon'=>'ri-star-line','active'=>'user.skills.*'],['route'=>'skill.select','label'=>'انتخاب مهارت','icon'=>'ri-equalizer-line','active'=>'skill.select']]];}
$sections[]=['label'=>'ارتباطات','items'=>[['route'=>'user.messages.index','label'=>'پیام‌ها','icon'=>'ri-message-3-line','active'=>'user.messages.*'],['route'=>'user.tickets.index','label'=>'تیکت‌ها','icon'=>'ri-customer-service-2-line','active'=>'user.tickets.*']]];
if(Route::has('profile.select')){$accountLabel=$hasEmployer&&$hasSpecialist?'تغییر نقش و پروفایل':'پروفایل و نقش';$accountIcon=$hasEmployer&&$hasSpecialist?'ri-swap-2-line':'ri-user-settings-line';$sections[]=['label'=>'حساب کاربری','items'=>[['route'=>'profile.select','label'=>$accountLabel,'icon'=>$accountIcon,'active'=>'profile.select']]];}}
$profileHref=!$isAdmin&&Route::has('profile.select')?route('profile.select'):null;
@endphp
<script>try{if(matchMedia('(min-width:992px)').matches&&localStorage.getItem('engipi.sidebar.collapsed')==='1')document.documentElement.classList.add('engipi-shell-collapsed')}catch(e){}</script>
<aside class="engipi-shell-sidebar {{ $isAdmin?'is-admin':'' }}" id="engipiSidebar" aria-label="منوی اصلی" aria-hidden="false">
<div class="engipi-sidebar-brand"><a href="{{ route('root') }}" class="engipi-brand" aria-label="EngiPi"><span class="engipi-brand-mark">E</span><span class="engipi-brand-name">EngiPi<small>{{ $isAdmin?'ADMIN CONSOLE':'ENGINEERING' }}</small></span></a><button type="button" class="engipi-icon-button engipi-collapse-button" id="engipiSidebarCollapse" aria-label="جمع کردن منو" aria-expanded="true"><i class="ri-contract-right-line"></i></button><button type="button" class="engipi-icon-button engipi-drawer-close" id="engipiDrawerClose" aria-label="بستن منو"><i class="ri-close-line"></i></button></div>
<x-sidebar.profile :user="$user" :role-label="$roleLabel" :href="$profileHref" :admin="$isAdmin"/>
<nav class="engipi-sidebar-nav" aria-label="ناوبری حساب">
@foreach($sections as $section)
@php $validItems=collect($section['items'])->filter(fn($item)=>Route::has($item['route'])); @endphp
@if($validItems->isNotEmpty())
<x-sidebar.section :label="$section['label']">
@foreach($validItems as $item)
@php $active=collect(explode('|',$item['active']))->contains(fn($pattern)=>request()->routeIs($pattern)); @endphp
<x-sidebar.item :route="$item['route']" :label="$item['label']" :icon="$item['icon']" :active="$active"/>
@endforeach
</x-sidebar.section>
@endif
@endforeach
</nav>
<div class="engipi-sidebar-footer"><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="engipi-nav-item engipi-logout" data-tooltip="خروج امن"><span class="engipi-nav-icon" aria-hidden="true"><i class="ri-logout-box-r-line"></i></span><span class="engipi-nav-label">خروج امن</span></button></form></div>
</aside>
<div class="engipi-drawer-overlay" id="engipiDrawerOverlay" aria-hidden="true"></div><div class="engipi-sidebar-tooltip" id="engipiSidebarTooltip" role="tooltip" aria-hidden="true"></div>
@endauth