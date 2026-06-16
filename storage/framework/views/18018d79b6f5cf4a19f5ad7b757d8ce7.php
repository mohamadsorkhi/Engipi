

<?php $__env->startSection('title', 'درخواست‌های دریافتی'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">درخواست‌های دریافتی</h5>
                </div>
                <div class="card-body">
                    <?php if($requests->isEmpty()): ?>
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز درخواستی برای پروژه‌های شما ارسال نشده است.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>پروژه</th>
                                        <th>متخصص</th>
                                        <th>پیام</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('user.projects.show', $request->project)); ?>" class="fw-medium text-primary">
                                                    <?php echo e(Str::limit($request->project->title, 30)); ?>

                                                </a>
                                            </td>
                                            <td class="fw-medium"><?php echo e($request->user->name); ?></td>
                                            <td><?php echo e(Str::limit($request->message, 40) ?: '-'); ?></td>
                                            <td>
                                                <?php if (isset($component)) { $__componentOriginal4299458004572dd18b1eae01081d8a0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4299458004572dd18b1eae01081d8a0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.request-status-badge','data' => ['status' => $request->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('request-status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($request->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4299458004572dd18b1eae01081d8a0d)): ?>
<?php $attributes = $__attributesOriginal4299458004572dd18b1eae01081d8a0d; ?>
<?php unset($__attributesOriginal4299458004572dd18b1eae01081d8a0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4299458004572dd18b1eae01081d8a0d)): ?>
<?php $component = $__componentOriginal4299458004572dd18b1eae01081d8a0d; ?>
<?php unset($__componentOriginal4299458004572dd18b1eae01081d8a0d); ?>
<?php endif; ?>
                                            </td>
                                            <td class="text-muted"><?php echo e($request->created_at); ?></td>
                                            <td>
                                                <?php if($request->status === 'pending'): ?>
                                                    <div class="d-flex gap-1">
                                                        <form action="<?php echo e(route('user.requests.accept', $request)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-soft-success btn-sm ajax-submit" title="پذیرش">
                                                                <i class="ri-check-line"></i> پذیرش
                                                            </button>
                                                        </form>
                                                        <form action="<?php echo e(route('user.requests.reject', $request)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" title="رد">
                                                                <i class="ri-close-line"></i> رد
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <form action="<?php echo e(route('user.requests.revert', $request)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-soft-warning btn-sm ajax-submit" title="بازگردانی">
                                                            <i class="ri-refresh-line"></i> بازگردانی
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <?php echo e($requests->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/engipi/laravel_app/resources/views/user/requests/received.blade.php ENDPATH**/ ?>