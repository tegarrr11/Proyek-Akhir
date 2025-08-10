<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'Sistem Informasi Manajemen Fasilitas PCR'); ?></title>

  <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo-sarpras-no-text.png')); ?>">

  <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->yieldPushContent('head'); ?>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <?php if(Auth::check()): ?>
    <meta name="user-id" content="<?php echo e(Auth::id()); ?>">
  <?php endif; ?>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="bg-[#eaf6fb] text-gray-800">

  
  <div class="flex items-center py-2 ml-8 bg-[#eaf6fb]">
    <img src="<?php echo e(asset('images/sarpras-logo.png')); ?>" alt="Logo Sarpras"
      class="h-12 sm:h-14 md:h-18 w-auto max-w-[100px] sm:max-w-[120px] md:max-w-[140px] object-contain mr-3">
  </div>

  
  <div class="w-full px-4">
    <?php echo $__env->yieldContent('content'); ?>
  </div>

  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/layouts/landing.blade.php ENDPATH**/ ?>