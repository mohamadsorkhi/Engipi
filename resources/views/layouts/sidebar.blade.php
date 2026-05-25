<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">

        <a href="{{ route('root') }}" class="logo logo-dark text-decoration-none">
            <span class="logo-sm">
                <span style="font-size:1.1rem;font-weight:900;color:#00d4aa;font-family:'Vazirmatn',sans-serif;">E</span>
            </span>
            <span class="logo-lg">
                <span style="font-size:1.3rem;font-weight:900;font-family:'Vazirmatn',sans-serif;color:white;letter-spacing:-0.5px;">
                    <span style="color:#00d4aa;">Eng</span>Pis
                </span>
            </span>
        </a>

        <a href="{{ route('root') }}" class="logo logo-light text-decoration-none">
            <span class="logo-sm">
                <span style="font-size:1.1rem;font-weight:900;color:#00d4aa;font-family:'Vazirmatn',sans-serif;">E</span>
            </span>
            <span class="logo-lg">
                <span style="font-size:1.3rem;font-weight:900;font-family:'Vazirmatn',sans-serif;color:white;letter-spacing:-0.5px;">
                    <span style="color:#00d4aa;">Eng</span>Pis
                </span>
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

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link {{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}"
                                href="{{ route('admin.subdomains.index') }}"
                            >
                                <i class="ri-node-tree"></i>
                                <span>زیرحوزه‌ها</span>
                            </a>
                        </li>





                    @else

                        {{-- =================== USER MENU =================== --}}

                        @php
                            $sidebarProfiles      = Auth::user()->profiles;
                            $activeRole           = session('active_role');
                            $hasEmployerProfile   = $sidebarProfiles->contains('type', 'employer');
                            $hasSpecialistProfile = $sidebarProfiles->contains('type', 'specialist');
                            $showEmployer         = $hasEmployerProfile   && $activeRole === 'employer';
                            $showSpecialist       = $hasSpecialistProfile && $activeRole === 'specialist';
                            $roleLabel            = $activeRole === 'employer' ? 'کارفرما'
                                                  : ($activeRole === 'specialist' ? 'متخصص' : null);
                        @endphp

                        {{-- ── Role badge + switch link ── --}}
                        <li class="menu-title d-flex align-items-center justify-content-between pe-2">
                            <span>
                                @if($roleLabel)
                                    <span class="badge bg-{{ $activeRole === 'employer' ? 'primary' : 'success' }}-subtle
                                                          text-{{ $activeRole === 'employer' ? 'primary' : 'success' }}
                                                          fs-11 fw-semibold">
                                        {{ $roleLabel }}
                                    </span>
                                @else
                                    منوی کاربر
                                @endif
                            </span>
                            @if($hasEmployerProfile && $hasSpecialistProfile)
                                <a href="{{ route('profile.select') }}"
                                   class="text-muted fs-11"
                                   title="تغییر نقش">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            @endif
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('root') ? 'active' : '' }}"
                               href="{{ route('root') }}">
                                <i class="ri-dashboard-2-line"></i>
                                <span>داشبورد</span>
                            </a>
                        </li>

                        @if($hasEmployerProfile && $hasSpecialistProfile)
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('profile.select') ? 'active' : '' }}"
                               href="{{ route('profile.select') }}">
                                <i class="ri-swap-line"></i>
                                <span>تغییر نقش</span>
                            </a>
                        </li>
                        @endif

                        @if($showEmployer)

                            <li class="menu-title"><span>کارفرما</span></li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('user.projects.*') ? 'active' : '' }}"
                                   href="{{ route('user.projects.index') }}">
                                    <i class="ri-briefcase-line"></i>
                                    <span>پروژه‌های من</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('employer.projects.*') ? 'active' : '' }}"
                                   href="{{ route('employer.projects.create') }}">
                                    <i class="ri-add-circle-line"></i>
                                    <span>ثبت پروژه جدید</span>
                                </a>
                            </li>

                        @endif

                        @if($showSpecialist)

                            <li class="menu-title"><span>متخصص</span></li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('skill.select') ? 'active' : '' }}"
                                   href="{{ route('skill.select') }}">
                                    <i class="ri-equalizer-line"></i>
                                    <span>انتخاب مهارت</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('user.skills.*') ? 'active' : '' }}"
                                   href="{{ route('user.skills.index') }}">
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