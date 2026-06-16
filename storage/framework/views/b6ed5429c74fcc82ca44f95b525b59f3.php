<?php $__env->startComponent('mail::message'); ?>

<div style="background-color: #001f3f; margin: -32px -32px 30px -32px; padding: 30px 0; text-align: center; border-radius: 3px 3px 0 0;">
<a href="<?php echo new \Illuminate\Support\EncodedHtmlString(url('/')); ?>">
<img src="<?php echo new \Illuminate\Support\EncodedHtmlString(URL::asset('build/images/logo-light.png')); ?>" alt="<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?> Logo" style="width: 150px;">
</a>
</div>


<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
<h1 style="text-align: right; font-size: 18px; font-weight: bold; margin-top: 0; color: #3d4852;">
<?php echo app('translator')->get('سلام!'); ?>
</h1>
<?php $__currentLoopData = $introLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096;">
<?php echo new \Illuminate\Support\EncodedHtmlString($line); ?>

</p>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if(isset($actionText)): ?>
<?php
    $color = $color ?? 'primary';
?>
<?php $__env->startComponent('mail::button', ['url' => $actionUrl, 'color' => $color]); ?>
<?php echo new \Illuminate\Support\EncodedHtmlString($actionText); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>


<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
<?php $__currentLoopData = $outroLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096;">
<?php echo new \Illuminate\Support\EncodedHtmlString($line); ?>

</p>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<p style="text-align: right; font-size: 16px; line-height: 1.5; color: #718096; margin-top: 20px;">
<?php echo app('translator')->get('با احترام'); ?>،<br>
<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>

</p>
</div>


<?php if(isset($actionText)): ?>
<?php $__env->startComponent('mail::subcopy'); ?>
<div dir="rtl" style="text-align: right; font-family: Tahoma, Arial, sans-serif;">
<p style="text-align: right; font-size: 12px; color: #718096;">
<?php echo app('translator')->get(
    "اگر در کلیک روی دکمه «:actionText» مشکل دارید، URL زیر را کپی و در مرورگر وب خود جای‌گذاری کنید:",
    [
        'actionText' => $actionText,
    ]
); ?>
<br>
<span class="break-all">
<a href="<?php echo new \Illuminate\Support\EncodedHtmlString($actionUrl); ?>" style="color: #3869d4;"><?php echo new \Illuminate\Support\EncodedHtmlString($actionUrl); ?></a>
</span>
</p>
</div>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/engipi/laravel_app/resources/views/vendor/notifications/email.blade.php ENDPATH**/ ?>