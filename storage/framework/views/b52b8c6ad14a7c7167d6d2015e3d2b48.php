

<?php $__env->startSection('title', 'ثبت پروژه — قبل از عضویت'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-page-wrapper pt-5">

    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <div class="auth-page-content">
        <div class="container">

            <?php echo $__env->make('auth.partials.auth-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="row justify-content-center mb-4">
                <div class="col-md-9 col-lg-7 col-xl-6">

                    
                    <div class="text-center mb-3">
                        <span class="badge bg-primary-subtle text-primary fs-12 px-3 py-2">
                            <i class="ri-list-check-2 me-1"></i>
                            مرحله ۱ از ۲ — اطلاعات پروژه
                        </span>
                    </div>

                    <div class="card mt-2">
                        <div class="card-body p-4">

                            <div class="text-center mt-2 mb-4">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-briefcase-line"></i>
                                    </span>
                                </div>
                                <h5 class="text-primary">پروژه‌ام را ثبت می‌کنم</h5>
                                <p class="text-muted small mb-0">
                                    اطلاعات پروژه را وارد کنید. بعد از ثبت‌نام، پروژه شما به صورت خودکار ایجاد می‌شود.
                                </p>
                            </div>

                            <form action="<?php echo e(route('guest.project.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        عنوان پروژه <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('title')); ?>"
                                        placeholder="مثلاً: شبیه‌سازی اجزاء محدود در ANSYS"
                                        required
                                        maxlength="191"
                                    >
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        توضیحات <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="شرح مختصری از پروژه، اهداف و نیازمندی‌ها..."
                                        required
                                    ><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        نوع همکاری <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="work_type"
                                        class="form-select <?php $__errorArgs = ['work_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required
                                    >
                                        <option value="">انتخاب کنید</option>
                                        <option value="remote"  <?php echo e(old('work_type') === 'remote'  ? 'selected' : ''); ?>>دورکاری</option>
                                        <option value="onsite"  <?php echo e(old('work_type') === 'onsite'  ? 'selected' : ''); ?>>حضوری</option>
                                        <option value="hybrid"  <?php echo e(old('work_type') === 'hybrid'  ? 'selected' : ''); ?>>ترکیبی</option>
                                    </select>
                                    <?php $__errorArgs = ['work_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <label class="form-label">حداقل بودجه (تومان)</label>
                                        <input
                                            type="number"
                                            name="budget_min"
                                            class="form-control <?php $__errorArgs = ['budget_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('budget_min')); ?>"
                                            min="0"
                                            placeholder="اختیاری"
                                        >
                                        <?php $__errorArgs = ['budget_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">حداکثر بودجه (تومان)</label>
                                        <input
                                            type="number"
                                            name="budget_max"
                                            class="form-control <?php $__errorArgs = ['budget_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('budget_max')); ?>"
                                            min="0"
                                            placeholder="اختیاری"
                                        >
                                        <?php $__errorArgs = ['budget_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    ادامه و ثبت‌نام
                                </button>

                            </form>

                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('register')); ?>" class="text-muted small">
                            <i class="ri-arrow-right-line me-1"></i>
                            بازگشت به ثبت‌نام معمولی
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php echo $__env->make('auth.partials.auth-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/particles.js/particles.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/particles.app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/engipi/laravel_app/resources/views/employer/guest-project.blade.php ENDPATH**/ ?>