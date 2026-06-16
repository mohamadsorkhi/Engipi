<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'parent',
    'parentUrl',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title',
    'parent',
    'parentUrl',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0"><?php echo e($title); ?></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <?php if(isset($parent) && isset($parentUrl)): ?>
                        <?php if($parent != "داشبورد"): ?>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('root')); ?>">داشبورد</a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item"><a href="<?php echo e($parentUrl); ?>"><?php echo e($parent); ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                </ol>
            </div>

        </div>
    </div>
</div>
<?php /**PATH /home/engipi/laravel_app/resources/views/components/admin/breadcrumb.blade.php ENDPATH**/ ?>