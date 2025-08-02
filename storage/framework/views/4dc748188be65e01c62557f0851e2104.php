<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
  
  <?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => ['title' => 'Dashboard','breadcrumb' => 'Dashboard > Ruangan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dashboard','breadcrumb' => 'Dashboard > Ruangan']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>

  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 mb-6">
    
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold">Pengajuan Menunggu Verifikasi</h3>
      <p class="text-2xl text-blue-600 font-bold"><?php echo e($jumlahPengajuanAktif); ?></p>
    </div>

    
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold">Peminjaman Aktif</h3>
      <p class="text-2xl text-green-600 font-bold"><?php echo e($jumlahPeminjamanAktif); ?></p>
    </div>

    
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold mb-2">Ruangan Sering Dipinjam</h3>
      <ul class="text-sm text-gray-800 space-y-1">
        <?php $__empty_1 = true; $__currentLoopData = $ruanganTerbanyak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <li class="flex justify-between border-b pb-1">
            <span><?php echo e($item->nama_gedung); ?></span>
            <span class="text-blue-600 font-semibold"><?php echo e($item->total); ?>x</span>
          </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <li class="italic text-gray-400">Tidak ada data</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  
  <div class="px-6">
    <?php echo $__env->make('components.kalender-mahasiswa', [
      'gedungs' => $gedungs,
      'selectedGedungId' => $selectedGedungId,
      'events' => $events
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/admin/dashboard.blade.php ENDPATH**/ ?>