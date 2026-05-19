<header id="page-topbar">
    <div class="layout-width">

        <div class="navbar-header">

            <div class="d-flex">

                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">

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

                </div>



                <button
                    type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon"
                >
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>



                <!-- Primary Action Buttons -->
                <div class="ms-1 header-item d-none d-sm-flex gap-2">

                    @auth

                        @if(Auth::user()->is_admin)

                            <span class="fw-bold text-primary">
                                پنل مدیریت {{ config('app.name') }}
                            </span>

                        @else

                            @php

                                $profiles =
                                    Auth::user()->profiles;

                                $hasEmployerProfile =
                                    $profiles->where('type', 'employer')->isNotEmpty();

                                $hasSpecialistProfile =
                                    $profiles->where('type', 'specialist')->isNotEmpty();

                            @endphp



                            @if($hasEmployerProfile)

                                <a
                                    href="{{ route('user.projects.create') }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    <i class="ri-add-line align-middle me-1"></i>

                                    ثبت پروژه
                                </a>

                            @endif



                            @if($hasSpecialistProfile)

                                <a
                                    href="{{ route('user.matched-projects.index') }}"
                                    class="btn btn-success btn-sm"
                                >
                                    <i class="ri-lightbulb-flash-line align-middle me-1"></i>

                                    پروژه‌های پیشنهادی
                                </a>

                            @endif

                        @endif

                    @endauth

                </div>

            </div>



            <div class="d-flex align-items-center">

                @auth

                    @if(Auth::user()->is_admin)

                        <!-- Admin Quick Actions -->

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                            title="مدیریت کاربران"
                        >
                            <i class="ri-group-line fs-22"></i>
                        </a>



                        <a
                            href="{{ route('admin.projects.index') }}"
                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                            title="مدیریت پروژه‌ها"
                        >
                            <i class="ri-briefcase-4-line fs-22"></i>
                        </a>



                        <a
                            href="{{ route('admin.skills.index') }}"
                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                            title="مدیریت مهارت‌ها"
                        >
                            <i class="ri-tools-line fs-22"></i>
                        </a>

                    @else

                        @php

                            $profiles =
                                Auth::user()->profiles;

                            $hasEmployerProfile =
                                $profiles->where('type', 'employer')->isNotEmpty();

                            $hasSpecialistProfile =
                                $profiles->where('type', 'specialist')->isNotEmpty();

                        @endphp



                        @if($hasEmployerProfile)

                            <a
                                href="{{ route('user.projects.index') }}"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                                title="پروژه‌های من"
                            >
                                <i class="ri-briefcase-2-line fs-22"></i>
                            </a>



                            <a
                                href="{{ route('user.requests.received') }}"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                                title="درخواست‌های دریافتی"
                            >
                                <i class="ri-inbox-line fs-22"></i>
                            </a>

                        @endif



                        @if($hasSpecialistProfile)

                            <a
                                href="{{ route('user.skills.index') }}"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2"
                                title="مهارت‌های من"
                            >
                                <i class="ri-star-line fs-22"></i>
                            </a>

                        @endif

                    @endif



                    <!-- User Menu -->
                    <div class="dropdown ms-sm-3 header-item topbar-user">

                        <button
                            type="button"
                            class="btn"
                            id="page-header-user-dropdown"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >

                            <span class="d-flex align-items-center">

                                @if(Auth::user()->avatar)

                                    <img
                                        class="rounded-circle header-profile-user"
                                        src="{{ URL::asset('images/' . Auth::user()->avatar) }}"
                                        alt="Header Avatar"
                                    >

                                @else

                                    <div class="avatar-xs">

                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                            {{ mb_substr(Auth::user()->name, 0, 1) }}

                                        </div>

                                    </div>

                                @endif



                                <span class="text-start ms-xl-2">

                                    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">

                                        {{ Auth::user()->name }}

                                    </span>



                                    <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">

                                        @if(Auth::user()->is_admin)
                                            ادمین
                                        @else
                                            کاربر
                                        @endif

                                    </span>

                                </span>

                            </span>

                        </button>



                        <div class="dropdown-menu dropdown-menu-end">

                            <h6 class="dropdown-header">

                                خوش آمدید {{ Auth::user()->name }}!

                            </h6>



                            <a
                                class="dropdown-item"
                                href="javascript:void();"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                <i class="bx bx-power-off text-muted fs-16 align-middle me-1"></i>

                                <span class="align-middle">
                                    خروج
                                </span>
                            </a>



                            <form
                                id="logout-form"
                                action="{{ route('logout') }}"
                                method="POST"
                                style="display:none;"
                            >
                                @csrf
                            </form>

                        </div>

                    </div>

                @endauth

            </div>

        </div>

    </div>
</header>