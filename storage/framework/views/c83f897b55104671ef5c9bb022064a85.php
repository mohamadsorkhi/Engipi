

<?php $__env->startSection('title', 'پروژه‌های پیشنهادی'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌های پیشنهادی</h5>
                    <a href="<?php echo e(route('user.skills.index')); ?>" class="btn btn-soft-primary btn-sm">
                        <i class="ri-settings-3-line me-1"></i> مدیریت مهارت‌ها
                    </a>
                </div>
                <div class="card-body">
                    <?php if($projects->isEmpty()): ?>
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                            <br>
                            <a href="<?php echo e(route('user.skills.index')); ?>" class="alert-link">مهارت‌های خود را به‌روز کنید</a>
                        </div>
                    <?php else: ?>
                        <?php
                            $workTypes = [
                                'remote' => ['name' => 'دورکاری', 'class' => 'bg-success'],
                                'onsite' => ['name' => 'حضوری', 'class' => 'bg-primary'],
                                'hybrid' => ['name' => 'ترکیبی', 'class' => 'bg-info'],
                            ];
                        ?>
                        
                        <div class="row g-4">
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-6">
                                    <div class="card border mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <a href="<?php echo e(route('user.matched-projects.show', $project)); ?>" class="fs-16 fw-medium text-primary">
                                                        <?php echo e($project->title); ?>

                                                    </a>
                                                    <div class="text-muted small mt-1">
                                                        <i class="ri-user-line me-1"></i><?php echo e($project->employer->name ?? '-'); ?>

                                                    </div>
                                                </div>
                                                <?php $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'class' => 'bg-secondary']; ?>
                                                <span class="badge <?php echo e($wt['class']); ?>"><?php echo e($wt['name']); ?></span>
                                            </div>
                                            
                                            <p class="text-muted mb-3"><?php echo e(Str::limit($project->description, 120)); ?></p>
                                            
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                <span class="badge bg-primary-subtle text-primary"><?php echo e($project->domains->first()?->name ?? '-'); ?></span>
                                                <?php $__currentLoopData = $project->processes->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $process): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-info-subtle text-info"><?php echo e($process->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i><?php echo e($project->created_at); ?>

                                                </small>
                                                <a href="<?php echo e(route('user.matched-projects.show', $project)); ?>" class="btn btn-soft-primary btn-sm">
                                                    مشاهده و ارسال درخواست
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-4">
                            <?php echo e($projects->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/engipi/laravel_app/resources/views/user/matched-projects/index.blade.php ENDPATH**/ ?>