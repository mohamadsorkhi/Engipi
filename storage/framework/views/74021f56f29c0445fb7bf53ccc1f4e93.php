<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">

        <a href="<?php echo e(route('root')); ?>" class="logo logo-dark text-decoration-none">
            <span class="logo-sm">
                <span style="font-size:1.1rem;font-weight:900;color:#00d4aa;font-family:'Vazirmatn',sans-serif;">E</span>
            </span>
            <span class="logo-lg">
                <span style="font-size:1.3rem;font-weight:900;font-family:'Vazirmatn',sans-serif;color:white;letter-spacing:-0.5px;">
                    <span style="color:#00d4aa;">Eng</span>Pis
                </span>
            </span>
        </a>

        <a href="<?php echo e(route('root')); ?>" class="logo logo-light text-decoration-none">
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




                <?php if(auth()->guard()->check()): ?>

                    <?php if(Auth::user()->is_admin): ?>

                        
                        <li class="menu-title">
                            <span>منوی ادمین</span>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.dashboard')); ?>"
                            >
                                <i class="ri-dashboard-2-line"></i>
                                <span>داشبورد</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.users.index')); ?>"
                            >
                                <i class="ri-group-line"></i>
                                <span>مدیریت کاربران</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.projects.*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.projects.index')); ?>"
                            >
                                <i class="ri-briefcase-4-line"></i>
                                <span>مدیریت پروژه‌ها</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.skills.*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.skills.index')); ?>"
                            >
                                <i class="ri-tools-line"></i>
                                <span>مدیریت مهارت‌ها</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.domains.*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.domains.index')); ?>"
                            >
                                <i class="ri-stack-line"></i>
                                <span>حوزه‌های تخصصی</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link menu-link <?php echo e(request()->routeIs('admin.subdomains.*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.subdomains.index')); ?>"
                            >
                                <i class="ri-node-tree"></i>
                                <span>زیرحوزه‌ها</span>
                            </a>
                        </li>





                    <?php else: ?>

                        

                        <?php
                            $sidebarProfiles      = Auth::user()->profiles;
                            $activeRole           = session('active_role');
                            $hasEmployerProfile   = $sidebarProfiles->contains('type', 'employer');
                            $hasSpecialistProfile = $sidebarProfiles->contains('type', 'specialist');
                            $showEmployer         = $hasEmployerProfile   && $activeRole === 'employer';
                            $showSpecialist       = $hasSpecialistProfile && $activeRole === 'specialist';
                            $roleLabel            = $activeRole === 'employer' ? 'کارفرما'
                                                  : ($activeRole === 'specialist' ? 'متخصص' : null);
                        ?>

                        
                        <li class="menu-title d-flex align-items-center justify-content-between pe-2">
                            <span>
                                <?php if($roleLabel): ?>
                                    <span class="badge bg-<?php echo e($activeRole === 'employer' ? 'primary' : 'success'); ?>-subtle
                                                          text-<?php echo e($activeRole === 'employer' ? 'primary' : 'success'); ?>

                                                          fs-11 fw-semibold">
                                        <?php echo e($roleLabel); ?>

                                    </span>
                                <?php else: ?>
                                    منوی کاربر
                                <?php endif; ?>
                            </span>
                            <?php if($hasEmployerProfile && $hasSpecialistProfile): ?>
                                <a href="<?php echo e(route('profile.select')); ?>"
                                   class="text-muted fs-11"
                                   title="تغییر نقش">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            <?php endif; ?>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link <?php echo e(request()->routeIs('root') ? 'active' : ''); ?>"
                               href="<?php echo e(route('root')); ?>">
                                <i class="ri-dashboard-2-line"></i>
                                <span>داشبورد</span>
                            </a>
                        </li>

                        <?php if($hasEmployerProfile && $hasSpecialistProfile): ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link <?php echo e(request()->routeIs('profile.select') ? 'active' : ''); ?>"
                               href="<?php echo e(route('profile.select')); ?>">
                                <i class="ri-swap-line"></i>
                                <span>تغییر نقش</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link menu-link <?php echo e(request()->routeIs('user.messages.*') ? 'active' : ''); ?>"
                               href="<?php echo e(route('user.messages.index')); ?>">
                                <i class="ri-message-3-line"></i>
                                <span>پیام‌ها</span>
                            </a>
                        </li>

                        <?php if($showEmployer): ?>

                            <li class="menu-title"><span>کارفرما</span></li>

                            <li class="nav-item">
                                <a class="nav-link menu-link <?php echo e(request()->routeIs('user.projects.*') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('user.projects.index')); ?>">
                                    <i class="ri-briefcase-line"></i>
                                    <span>پروژه‌های من</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link <?php echo e(request()->routeIs('employer.projects.*') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('employer.projects.create')); ?>">
                                    <i class="ri-add-circle-line"></i>
                                    <span>ثبت پروژه جدید</span>
                                </a>
                            </li>

                        <?php endif; ?>

                        <?php if($showSpecialist): ?>

                            <li class="menu-title"><span>متخصص</span></li>

                            <li class="nav-item">
                                <a class="nav-link menu-link <?php echo e(request()->routeIs('skill.select') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('skill.select')); ?>">
                                    <i class="ri-equalizer-line"></i>
                                    <span>انتخاب مهارت</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link <?php echo e(request()->routeIs('user.skills.*') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('user.skills.index')); ?>">
                                    <i class="ri-star-line"></i>
                                    <span>مهارت‌های من</span>
                                </a>
                            </li>

                        <?php endif; ?>

                    <?php endif; ?>

                <?php endif; ?>




                <?php if(auth()->guard()->guest()): ?>

                    <li class="menu-title">
                        <span>منوی مهمان</span>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link menu-link"
                            href="<?php echo e(route('login')); ?>"
                        >
                            <i class="ri-login-box-line"></i>
                            <span>ورود</span>
                        </a>
                    </li>

                <?php endif; ?>



            </ul>

        </div>

    </div>

    <div class="sidebar-background"></div>

</div>

<!-- Left Sidebar End -->

<div class="vertical-overlay"></div><?php /**PATH /home/engipi/laravel_app/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>