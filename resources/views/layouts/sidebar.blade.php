<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('root') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="27">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('root') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="27">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                @if (Auth::user()->is_admin)
                    {{-- =================== ADMIN MENU =================== --}}
                    <li class="menu-title"><span>منوی ادمین</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="ri-dashboard-2-line"></i> <span>داشبورد</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="ri-group-line"></i> <span>مدیریت کاربران</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                            <i class="ri-briefcase-4-line"></i> <span>مدیریت پروژه‌ها</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}" href="{{ route('admin.skills.index') }}">
                            <i class="ri-tools-line"></i> <span>مدیریت مهارت‌ها</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}" href="{{ route('admin.domains.index') }}">
                            <i class="ri-stack-line"></i> <span>حوزه‌های تخصصی</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}">
                            <i class="ri-customer-service-2-line"></i> <span>تیکت‌ها</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.ticket-departments.*') ? 'active' : '' }}" href="{{ route('admin.ticket-departments.index') }}">
                            <i class="ri-organization-chart"></i> <span>دپارتمان‌های تیکت</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.processes.*') ? 'active' : '' }}" href="{{ route('admin.processes.index') }}">
                            <i class="ri-flow-chart"></i> <span>پردازش‌ها</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.profiles.*') ? 'active' : '' }}" href="{{ route('admin.profiles.index') }}">
                            <i class="ri-user-settings-line"></i> <span>پروفایل‌ها</span>
                        </a>
                    </li>

                @else
                    {{-- =================== UNIFIED USER MENU =================== --}}
                    @php
                        $profiles = Auth::user()->profiles;
                        $hasEmployerProfile = $profiles->where('type', 'employer')->isNotEmpty();
                        $hasSpecialistProfile = $profiles->where('type', 'specialist')->isNotEmpty();
                    @endphp
                    <li class="menu-title"><span>منوی کاربر</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('root') }}">
                            <i class="ri-dashboard-2-line"></i> <span>داشبورد</span>
                        </a>
                    </li>

                    {{-- Profile Management --}}
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('profile.select') ? 'active' : '' }}" href="{{ route('profile.select') }}">
                            <i class="ri-user-settings-line"></i> <span>مدیریت پروفایل</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('user.tickets.index') }}">
                            <i class="ri-customer-service-2-line"></i> <span>تیکت‌ها</span>
                        </a>
                    </li>


                    @if($hasEmployerProfile)
                        <li class="menu-title"><span>بخش کارفرما</span></li>

                        {{-- Projects Management --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.projects.*') ? 'active' : '' }}" href="#sidebarProjects" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ request()->routeIs('user.projects.*') ? 'true' : 'false' }}" aria-controls="sidebarProjects">
                                <i class="ri-briefcase-line"></i> <span>پروژه‌های من</span>
                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('user.projects.*') ? 'show' : '' }}" id="sidebarProjects">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('user.projects.index') }}" class="nav-link {{ request()->routeIs('user.projects.index') ? 'active' : '' }}">لیست پروژه‌ها</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.projects.create') }}" class="nav-link {{ request()->routeIs('user.projects.create') ? 'active' : '' }}">ثبت پروژه جدید</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Received Requests (as employer) --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.requests.received') ? 'active' : '' }}" href="{{ route('user.requests.received') }}">
                                <i class="ri-inbox-line"></i> <span>درخواست‌های دریافتی</span>
                            </a>
                        </li>
                    @endif

                    @if($hasSpecialistProfile)
                        <li class="menu-title"><span>بخش متخصص</span></li>

                        {{-- Skills Management --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.skills.*') ? 'active' : '' }}" href="{{ route('user.skills.index') }}">
                                <i class="ri-star-line"></i> <span>مهارت‌های من</span>
                            </a>
                        </li>

                        {{-- Matched Projects --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.matched-projects.*') ? 'active' : '' }}" href="{{ route('user.matched-projects.index') }}">
                                <i class="ri-lightbulb-flash-line"></i> <span>پروژه‌های پیشنهادی</span>
                            </a>
                        </li>

                        {{-- Sent Requests (as specialist) --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('user.requests.sent') ? 'active' : '' }}" href="{{ route('user.requests.sent') }}">
                                <i class="ri-send-plane-2-line"></i> <span>درخواست‌های ارسالی</span>
                            </a>
                        </li>
                    @endif

                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
