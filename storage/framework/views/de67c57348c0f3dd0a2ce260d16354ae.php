

<?php $__env->startSection('title', 'پیام‌ها'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginaldbbc880c47f621cda59b70d6eb356b2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbbc880c47f621cda59b70d6eb356b2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.breadcrumb','data' => ['title' => 'پیام‌ها','parent' => 'داشبورد','parentUrl' => ''.e(route('user.dashboard')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'پیام‌ها','parent' => 'داشبورد','parentUrl' => ''.e(route('user.dashboard')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbbc880c47f621cda59b70d6eb356b2f)): ?>
<?php $attributes = $__attributesOriginaldbbc880c47f621cda59b70d6eb356b2f; ?>
<?php unset($__attributesOriginaldbbc880c47f621cda59b70d6eb356b2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbbc880c47f621cda59b70d6eb356b2f)): ?>
<?php $component = $__componentOriginaldbbc880c47f621cda59b70d6eb356b2f; ?>
<?php unset($__componentOriginaldbbc880c47f621cda59b70d6eb356b2f); ?>
<?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">صندوق پیام‌ها</h5>
        </div>
        <div class="card-body p-0">
            <?php if($conversations->isEmpty()): ?>
                <div class="alert alert-info text-center m-3 mb-0">هنوز پیامی ندارید.</div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item list-group-item-action px-4 py-3">
                            <a href="<?php echo e(route('user.messages.show', $conv->other)); ?>" class="text-decoration-none text-dark d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-18">
                                        <?php echo e(mb_substr($conv->other->first_name ?? '؟', 0, 1)); ?>

                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold"><?php echo e($conv->other->full_name); ?></span>
                                        <span class="text-muted small"><?php echo e($conv->latest->created_at); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <p class="text-muted small mb-0 text-truncate flex-grow-1">
                                            <?php echo e(Str::limit($conv->latest->body, 80)); ?>

                                        </p>
                                        <?php if($conv->unread > 0): ?>
                                            <span class="badge bg-danger rounded-pill flex-shrink-0"><?php echo e($conv->unread); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/engipi/laravel_app/resources/views/user/messages/index.blade.php ENDPATH**/ ?>