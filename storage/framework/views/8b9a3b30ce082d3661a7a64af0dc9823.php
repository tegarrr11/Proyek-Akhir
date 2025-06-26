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
    <div class="flex items-center gap-4">
        <button class="text-gray-500 hover:text-black">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
</svg>

        </button>

        <div class="flex items-center gap-2">
            <div class="bg-[#003366] text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

            </div>
            <span class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></span>
            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.584l3.71-4.354a.75.75 0 011.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z"
                      clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
</div>
<?php /**PATH D:\Kuliah\Proyek Akhir\peminjaman-fasilitas\resources\views/components/header.blade.php ENDPATH**/ ?>