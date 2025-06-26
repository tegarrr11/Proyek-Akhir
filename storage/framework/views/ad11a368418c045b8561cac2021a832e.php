<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'Sistem Informasi Manajemen Fasilitas PCR'); ?></title>

  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
  <?php echo $__env->yieldPushContent('styles'); ?>
  <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="bg-[#eaf6fb] font-sans text-gray-800">

  
  <div class="flex items-center px-12 py-4 bg-[#eaf6fb]">
    <img src="<?php echo e(asset('images/sarpras-logo.png')); ?>" alt="Logo Sarpras"
      class="h-10 w-auto max-w-[80px] object-contain mr-3">
  </div>

  
  <div class="w-full px-12">
    <?php echo $__env->yieldContent('content'); ?>
  </div>

  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Kuliah\Proyek Akhir\peminjaman-fasilitas\resources\views/layouts/landing.blade.php ENDPATH**/ ?>