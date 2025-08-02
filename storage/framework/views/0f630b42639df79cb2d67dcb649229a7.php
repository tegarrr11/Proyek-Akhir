    <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Dashboard',
    'breadcrumb' => 'Dashboard > Ruangan'
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
    'title' => 'Dashboard',
    'breadcrumb' => 'Dashboard > Ruangan'
    ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

    <div class="flex items-center justify-between mb-6">
        <!-- Judul dan breadcrumb -->
        <div>
            <h1 class="text-md sm:text-lg font-semibold text-gray-800"><?php echo e($title); ?></h1>
            <p class="text-sm text-gray-500"><?php echo e($breadcrumb); ?></p>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-2">
            <?php
            $avatar = auth()->user()->avatar ?? null;
            ?>

            <?php if($avatar): ?>
            <img src="<?php echo e($avatar); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
            <?php else: ?>
            <div class="bg-[#003366] text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

            </div>
            <?php endif; ?>

            <span class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></span>
        </div>
    </div><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/header.blade.php ENDPATH**/ ?>