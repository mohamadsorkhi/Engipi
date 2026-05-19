<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">

        <a href="{{ route('root') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>

            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="27">
            </span>
        </a>

        <a href="{{ route('root') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>

            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="27">
            </span>
        </a>

        <button
            type="button"
            class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover"
        >
            <i class="ri-record-circle-line"></i>
        </button>

    </div>





    <div id="scrollbar">

        <div class="container-fluid">

            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">




                @auth

                    @if (Auth::user()->is_admin)

                        {{-- =================== ADMIN MENU =================== --}}
                        <li class="menu-title">
                            <span>منوی ادمین</span>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}"
                            >
                                <i class="ri-dashboard-2-line"></i>
                                <span>داشبورد</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}"
                            >
                                <i class="ri-group-line"></i>
                                <span>مدیریت کاربران</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
                                href="{{ route('admin.projects.index') }}"
                            >
                                <i class="ri-briefcase-4-line"></i>
                                <span>مدیریت پروژه‌ها</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}"
                                href="{{ route('admin.skills.index') }}"
                            >
                                <i class="ri-tools-line"></i>
                                <span>مدیریت مهارت‌ها</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}"
                                href="{{ route('admin.domains.index') }}"
                            >
                                <i class="ri-stack-line"></i>
                                <span>حوزه‌های تخصصی</span>
                            </a>
                        </li>





                    @else

                        {{-- =================== USER MENU =================== --}}

                        @php

                            $profiles = Auth::user()->profiles;

                            $hasEmployerProfile =
                                $profiles->where('type', 'employer')->isNotEmpty();

                            $hasSpecialistProfile =
                                $profiles->where('type', 'specialist')->isNotEmpty();

                        @endphp



                        <li class="menu-title">
                            <span>منوی کاربر</span>
                        </li>



                        <li class="nav-item">
                            <a
                                class="nav-link menu-link"
                                href="{{ route('root') }}"
                            >
                                <i class="ri-dashboard-2-line"></i>
                                <span>داشبورد</span>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a
                                class="nav-link menu-link"
                                href="{{ route('profile.select') }}"
                            >
                                <i class="ri-user-settings-line"></i>
                                <span>مدیریت پروفایل</span>
                            </a>
                        </li>




                        @if($hasEmployerProfile)

                            <li class="menu-title">
                                <span>بخش کارفرما</span>
                            </li>

                            <li class="nav-item">
                                <a
                                    class="nav-link menu-link"
                                    href="{{ route('user.projects.index') }}"
                                >
                                    <i class="ri-briefcase-line"></i>
                                    <span>پروژه‌های من</span>
                                </a>
                            </li>

                        @endif




                        @if($hasSpecialistProfile)

                            <li class="menu-title">
                                <span>بخش متخصص</span>
                            </li>

                            <li class="nav-item">
                                <a
                                    class="nav-link menu-link"
                                    href="{{ route('user.skills.index') }}"
                                >
                                    <i class="ri-star-line"></i>
                                    <span>مهارت‌های من</span>
                                </a>
                            </li>

                        @endif

                    @endif

                @endauth




                @guest

                    <li class="menu-title">
                        <span>منوی مهمان</span>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link menu-link"
                            href="{{ route('login') }}"
                        >
                            <i class="ri-login-box-line"></i>
                            <span>ورود</span>
                        </a>
                    </li>

                @endguest



            </ul>

        </div>

    </div>

    <div class="sidebar-background"></div>

</div>

<!-- Left Sidebar End -->

<div class="vertical-overlay"></div>