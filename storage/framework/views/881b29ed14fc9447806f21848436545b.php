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
  <h2 class="text-lg font-semibold mb-4">Tambah Fasilitas</h2>
  <form action="<?php echo e(route('admin.fasilitas.store')); ?>" method="POST" class="space-y-4">
    <?php echo csrf_field(); ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="gedung_id" class="block text-sm font-medium text-gray-700">Pilih Gedung</label>
        <select name="gedung_id" id="gedung_id" class="w-full border rounded px-3 py-2" required>
          <option value="">-- Pilih Gedung --</option>
          <?php $__currentLoopData = $gedungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($gedung->id); ?>"><?php echo e($gedung->nama); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div>
        <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
        <input type="text" name="nama_barang" id="nama_barang" class="w-full border rounded px-3 py-2" placeholder="Contoh: Kursi" required>
      </div>
      <div>
        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" name="stok" id="stok" class="w-full border rounded px-3 py-2" placeholder="Contoh: 100" required>
      </div>
    </div>
    <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
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
            <button type="submit" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500">Simpan</button>
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
              
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11.5a.5.5 0 00.5.5H16a2 2 0 002-2v-5m-5-5l5 5m0 0L14 4m5 5L9 15" />
              </svg>
            </a>
            <form action="<?php echo e(route('admin.fasilitas.destroy', $item->id)); ?>" method="POST" class="inline-block">
              <?php echo csrf_field(); ?>
              <?php echo method_field('DELETE'); ?>
              <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-500 hover:text-red-700">
                
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M6 3a1 1 0 00-.894.553L4.382 5H2.5a.5.5 0 000 1h15a.5.5 0 000-1h-1.882l-.724-1.447A1 1 0 0014 3H6zM5 7a.5.5 0 01.5.5V15a2 2 0 002 2h5a2 2 0 002-2V7.5a.5.5 0 011 0V15a3 3 0 01-3 3H7a3 3 0 01-3-3V7.5a.5.5 0 011 0z" clip-rule="evenodd" />
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

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/admin/fasilitas.blade.php ENDPATH**/ ?>