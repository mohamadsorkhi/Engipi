<!doctype html>
<html dir=rtl lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-topbar="light" data-sidebar-image="none">

    <head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('app.name')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('build/images/favicon.ico')); ?>">
    <!-- Vazirmatn Font -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
        <?php echo $__env->make('layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>* { font-family: 'Vazirmatn', sans-serif !important; }</style>
  </head>

    <?php echo $__env->yieldContent('body'); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>
</html>
<?php /**PATH /home/engipi/laravel_app/resources/views/layouts/master-without-nav.blade.php ENDPATH**/ ?>