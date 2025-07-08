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
  <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
    <?php echo e(session('success')); ?>

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


<div class="bg-white rounded-md shadow p-6 mb-6">
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
      <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 text-sm">Simpan</button>
    </div>
  </form>

</div>



<div class="bg-white rounded-md shadow p-6">
  <h2 class="text-xl font-semibold mb-4">Daftar Fasilitas per Gedung</h2>

  <?php $__empty_1 = true; $__currentLoopData = $gedungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="mb-6 border rounded">
      <div class="bg-gray-100 px-4 py-2 font-semibold">
        <?php echo e($gedung->nama); ?>

      </div>

      <?php if($gedung->fasilitas->isEmpty()): ?>
        <div class="px-4 py-2 text-sm text-gray-500">Belum ada fasilitas.</div>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="w-full table-auto text-sm">
<thead class="bg-gray-100 text-left">
  <tr>
    <th class="px-4 py-2 w-12">No.</th>
    <th class="px-4 py-2">Nama Fasilitas</th>
    <th class="px-4 py-2">Stok</th>
    <th class="px-4 py-2">Aksi</th>
  </tr>
</thead>
<tbody>
  <?php $__currentLoopData = $gedung->fasilitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
      $isEditing = request('edit') == $item->id;
      $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-gray-50'; // warna selang-seling
    ?>

    <?php if($isEditing): ?>
      <form action="<?php echo e(route('admin.fasilitas.update', $item->id)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <input type="hidden" name="gedung_id" value="<?php echo e($item->gedung_id); ?>">
        <tr class="<?php echo e($rowClass); ?> border-t border-gray-200">
          <td class="px-4 py-2 align-middle"><?php echo e($index + 1); ?></td>
          <td class="px-4 py-2 align-middle">
            <input type="text" name="nama_barang" value="<?php echo e(old('nama_barang', $item->nama_barang)); ?>" class="border px-2 py-1 rounded text-sm w-full" required>
          </td>
          <td class="px-4 py-2 align-middle">
            <input type="number" name="stok" value="<?php echo e(old('stok', $item->stok)); ?>" class="border px-2 py-1 rounded text-sm w-full" required>
          </td>
          <td class="px-4 py-2 align-middle">
            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-500">SIMPAN</button>
            <a href="<?php echo e(url()->current()); ?>" class="text-gray-600 text-xs ml-2 hover:underline">Batal</a>
          </td>
        </tr>
      </form>
    <?php else: ?>
      <tr class="<?php echo e($rowClass); ?> border-t border-gray-200">
        <td class="px-4 py-2 align-middle"><?php echo e($index + 1); ?></td>
        <td class="px-4 py-2 align-middle"><?php echo e($item->nama_barang); ?></td>
        <td class="px-4 py-2 align-middle"><?php echo e($item->stok); ?></td>
        <td class="px-4 py-2 align-middle">
          <div class="flex space-x-2">
            <a href="<?php echo e(url()->current()); ?>?edit=<?php echo e($item->id); ?>" class="text-blue-600 hover:text-blue-800">
              
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#0071ff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.587 1.413T19 21zm4-6v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4t-.137.738t-.438.662L13.25 15zM21.025 4.4l-1.4-1.4zM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575zm6.5-6.5l-.725-.7zl.7.7z"/></svg>
            </a>
            <form action="<?php echo e(route('admin.fasilitas.destroy', $item->id)); ?>" method="POST" class="inline-block">
              <?php echo csrf_field(); ?>
              <?php echo method_field('DELETE'); ?>
              <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-500 hover:text-red-700">
                
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                    <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
                  </svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
    <?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="text-sm text-gray-500">Tidak ada gedung yang tersedia.</p>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/pages/admin/fasilitas.blade.php ENDPATH**/ ?>