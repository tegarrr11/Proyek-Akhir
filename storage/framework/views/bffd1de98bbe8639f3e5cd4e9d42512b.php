<?php $__env->startSection('title', 'Fasilitas'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => ['title' => 'Fasilitas','breadcrumb' => 'Admin > Fasilitas']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fasilitas','breadcrumb' => 'Admin > Fasilitas']); ?>
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

<?php if(session('success')): ?>
  <div   
    id="successToast"
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 2000)"
    x-show="show"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-6 right-6 z-50 flex items-center justify-between gap-4 bg-green-600 text-white px-4 py-2 rounded-md shadow-lg text-sm font-normal"
  >
    <div class="flex items-center gap-2">
      <div class="bg-white text-green-600 rounded-full p-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <span><?php echo e(session('success')); ?></span>
    </div>
    <button @click="show = false" class="text-white hover:text-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
<?php endif; ?>

<?php if($errors->any()): ?>
  <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
    <strong>Validasi Gagal:</strong>
    <ul class="list-disc list-inside">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="bg-white rounded-md shadow !p-6 mb-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
    <h2 class="text-lg font-semibold">Tambah Fasilitas</h2>
    <form id="uploadExcelForm" action="<?php echo e(route('admin.fasilitas.import')); ?>" method="POST" enctype="multipart/form-data" class="mt-4 md:mt-0 flex items-center gap-4">
      <?php echo csrf_field(); ?>
      <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="hidden" onchange="document.getElementById('uploadExcelForm').submit()">
      <button type="button" onclick="document.getElementById('fileInput').click()" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Upload dari Excel</button>
      <a href="<?php echo e(asset('template_fasilitas.xlsx')); ?>" class="text-blue-600 text-sm underline hover:text-blue-800">Download Template</a>
    </form>
  </div>

  <form action="<?php echo e(route('admin.fasilitas.store')); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label for="gedung_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gedung</label>
        <select name="gedung_id" id="gedung_id" class="w-full border rounded px-3 py-2 text-sm" required>
          <option value="">-- Pilih Gedung --</option>
          <?php $__currentLoopData = $gedungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($gedung->id); ?>"><?php echo e($gedung->nama); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div>
        <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
        <input type="text" name="nama_barang" id="nama_barang" class="w-full border rounded px-3 py-2 text-sm" placeholder="Contoh: Kursi" required>
      </div>
      <div>
        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
        <input type="number" name="stok" id="stok" class="w-full border rounded px-3 py-2 text-sm" placeholder="Contoh: 100" required>
      </div>
    </div>
    <div class="pt-2">
      <button type="submit" class="bg-[#003366] text-white px-5 py-2 rounded hover:bg-[#002244] text-sm">Simpan</button>
    </div>
  </form>
</div>


<?php if (isset($component)) { $__componentOriginal04306ad73bc5fdafe5f6c2d66e2619c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal04306ad73bc5fdafe5f6c2d66e2619c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-fasilitas','data' => ['gedungs' => $gedungs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-fasilitas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['gedungs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($gedungs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal04306ad73bc5fdafe5f6c2d66e2619c9)): ?>
<?php $attributes = $__attributesOriginal04306ad73bc5fdafe5f6c2d66e2619c9; ?>
<?php unset($__attributesOriginal04306ad73bc5fdafe5f6c2d66e2619c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal04306ad73bc5fdafe5f6c2d66e2619c9)): ?>
<?php $component = $__componentOriginal04306ad73bc5fdafe5f6c2d66e2619c9; ?>
<?php unset($__componentOriginal04306ad73bc5fdafe5f6c2d66e2619c9); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/pages/admin/fasilitas.blade.php ENDPATH**/ ?>