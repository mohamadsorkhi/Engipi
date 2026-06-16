

<?php $__env->startSection('title', 'داشبورد'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 overflow-hidden" style="background: linear-gradient(135deg, #0f2340 0%, #1a3a6a 60%, #0f4d3a 100%) !important; min-height: 110px;">
                <div class="card-body ep-welcome-body d-flex align-items-center justify-content-between gap-3 py-4">
                    <div>
                        <h4 class="mb-1" style="font-size:1.25rem; font-weight:700; color:white;">
                            سلام، <?php echo e(Auth::user()->name); ?>! <span style="color:#00d4aa;">👋</span>
                        </h4>
                        <p class="mb-0" style="color:rgba(220,232,245,0.65); font-size:0.9rem;">خلاصه وضعیت حساب کاربری شما</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php if($employerProfile): ?>
                            <a href="<?php echo e(route('user.projects.create')); ?>" class="btn btn-primary btn-sm px-3">
                                <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه
                            </a>
                        <?php endif; ?>
                        <?php if($specialistProfile): ?>
                            <a href="<?php echo e(route('user.skills.index')); ?>" class="btn btn-success btn-sm px-3">
                                <i class="ri-star-line align-bottom me-1"></i> مهارت‌ها
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="position:absolute;top:-30px;left:-30px;width:140px;height:140px;border-radius:50%;background:rgba(0,212,170,0.06);pointer-events:none;"></div>
                <div style="position:absolute;bottom:-40px;right:60px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>
            </div>
        </div>
    </div>

    <?php
        $hasEmployer = !is_null($employerProfile);
        $hasSpecialist = !is_null($specialistProfile);
    ?>

    <?php if(!$hasEmployer || !$hasSpecialist): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">تکمیل حساب کاربری</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            برای دسترسی به امکانات، ابتدا پروفایل‌های مورد نیاز را ایجاد کنید.
                        </p>

                        <div class="row g-3">
                            <?php if(!$hasEmployer): ?>
                                <div class="col-lg-6">
                                    <form action="<?php echo e(route('profiles.store')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="profile_type" value="employer">
                                        <div class="card border border-dashed mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                            <i class="ri-briefcase-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">پروفایل کارفرما</h6>
                                                        <p class="text-muted small mb-0">برای ثبت پروژه و مدیریت درخواست‌ها</p>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">نام شرکت (اختیاری)</label>
                                                    <input type="text" name="company_name" class="form-control">
                                                </div>

                                                <button type="submit" class="btn btn-primary ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل کارفرما
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <?php if(!$hasSpecialist): ?>
                                <div class="col-lg-6">
                                    <form action="<?php echo e(route('profiles.store')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="profile_type" value="specialist">
                                        <div class="card border border-dashed mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                                            <i class="ri-user-star-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">پروفایل متخصص</h6>
                                                        <p class="text-muted small mb-0">برای ثبت مهارت‌ها و ارسال درخواست</p>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">عنوان تخصصی</label>
                                                    <input type="text" name="headline" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">بیوگرافی (اختیاری)</label>
                                                    <textarea name="bio" class="form-control" rows="3"></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-success ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل متخصص
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4">
                            <a href="<?php echo e(route('profile.select')); ?>" class="btn btn-outline-secondary">
                                مدیریت پروفایل‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <?php if($employerProfile): ?>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #00d4aa !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">پروژه‌های من</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-briefcase-line" style="color:#00d4aa;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#00d4aa;"><?php echo e($myProjectsCount); ?></h3>
                    <a href="<?php echo e(route('user.projects.index')); ?>" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #60c8f5 !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">درخواست‌های دریافتی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(3,169,244,0.12);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-inbox-line" style="color:#60c8f5;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="mb-0" style="font-size:2rem;font-weight:800;color:#60c8f5;"><?php echo e($receivedRequestsCount); ?></h3>
                        <?php if($pendingRequestsCount > 0): ?>
                            <span class="badge" style="background:rgba(255,190,0,0.18);color:#ffd43b;font-size:0.7rem;"><?php echo e($pendingRequestsCount); ?> در انتظار</span>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('user.requests.received')); ?>" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($specialistProfile): ?>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #5ddfb0 !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">پروژه‌های پیشنهادی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(26,122,82,0.15);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-lightbulb-flash-line" style="color:#5ddfb0;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#5ddfb0;"><?php echo e($matchedProjectsCount); ?></h3>
                    <a href="<?php echo e(route('user.matched-projects.index')); ?>" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100" style="border-right: 3px solid #ffd43b !important; border-radius: 14px !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="fw-medium mb-0" style="font-size:0.78rem;color:rgba(220,232,245,0.55);text-transform:uppercase;letter-spacing:0.08em;">درخواست‌های ارسالی</p>
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,190,0,0.1);display:flex;align-items:center;justify-content:center;">
                            <i class="ri-send-plane-2-line" style="color:#ffd43b;font-size:1.2rem;"></i>
                        </div>
                    </div>
                    <h3 class="mb-1" style="font-size:2rem;font-weight:800;color:#ffd43b;"><?php echo e($sentRequestsCount); ?></h3>
                    <a href="<?php echo e(route('user.requests.sent')); ?>" style="font-size:0.8rem;color:rgba(220,232,245,0.5);" class="text-decoration-none">مشاهده همه ←</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- My Projects -->
        <?php if($employerProfile): ?>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">آخرین پروژه‌های من</h5>
                    <a href="<?php echo e(route('user.projects.index')); ?>" class="btn btn-soft-primary btn-sm">مشاهده همه</a>
                </div>
                <div class="card-body">
                    <?php if($myProjects->isEmpty()): ?>
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز پروژه‌ای ثبت نکرده‌اید.
                            <a href="<?php echo e(route('user.projects.create')); ?>" class="alert-link">ثبت پروژه جدید</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <tbody>
                                    <?php $__currentLoopData = $myProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('user.projects.show', $project)); ?>" class="fw-medium text-primary">
                                                    <?php echo e(Str::limit($project->title, 40)); ?>

                                                </a>
                                            </td>
                                            <td class="text-muted"><?php echo e($project->created_at); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Matched Projects -->
        <?php if($specialistProfile): ?>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌های پیشنهادی</h5>
                    <a href="<?php echo e(route('user.matched-projects.index')); ?>" class="btn btn-soft-success btn-sm">مشاهده همه</a>
                </div>
                <div class="card-body">
                    <?php if($recentMatchedProjects->isEmpty()): ?>
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <tbody>
                                    <?php $__currentLoopData = $recentMatchedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('user.matched-projects.show', $project)); ?>" class="fw-medium text-success">
                                                    <?php echo e(Str::limit($project->title, 40)); ?>

                                                </a>
                                            </td>
                                            <td><span class="badge bg-primary-subtle text-primary"><?php echo e($project->domain->name ?? '-'); ?></span></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/engipi/laravel_app/resources/views/user/dashboard.blade.php ENDPATH**/ ?>