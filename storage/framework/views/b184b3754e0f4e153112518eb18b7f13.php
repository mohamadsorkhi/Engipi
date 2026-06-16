<!-- Layout config Js -->
<script src="<?php echo e(URL::asset('build/js/layout.js')); ?>"></script>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/scss/bootstrap.scss',
    'resources/scss/icons.scss',
    'resources/scss/app.scss',
]); ?>

<link rel="stylesheet" href="<?php echo e(URL::asset('build/css/mgh/mgh.css')); ?>">

<?php echo $__env->yieldContent('css'); ?>
<?php /**PATH /home/engipi/laravel_app/resources/views/layouts/head-css.blade.php ENDPATH**/ ?>