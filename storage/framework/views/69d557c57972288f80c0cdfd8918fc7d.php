<table class="w-full text-sm">
  <thead class="bg-gray-100">
    <tr class="font-semibold">
      <th class="px-4 py-2 text-left">No.</th>
      <th class="px-4 py-2">Judul Kegiatan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2">Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e($item->created_at->format('d/m/Y')); ?></td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            <?php if($item->verifikasi_bem === 'diterima'): ?>
              bg-green-500 text-white
            <?php elseif($item->verifikasi_bem === 'ditolak'): ?>
              bg-red-100 text-red-600
            <?php else: ?>
              bg-yellow-500 text-white
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_bem)); ?>

          </span>
        </td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            <?php if($item->verifikasi_sarpras === 'diterima'): ?>
              bg-green-500 text-white
            <?php elseif($item->verifikasi_sarpras === 'ditolak'): ?>
              bg-red-100 text-red-600
            <?php else: ?>
              bg-yellow-500 text-white
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_sarpras)); ?>

          </span>
        </td>
        <td class="px-4 py-2"><?php echo e($item->organisasi); ?></td>
        <td class="px-4 py-2">
          <button onclick="showDetail(<?php echo e($item->id); ?>)" class="text-blue-600 hover:text-blue-800 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </button>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada riwayat.</td></tr>
    <?php endif; ?>
  </tbody>
</table>



<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/table-riwayat-admin.blade.php ENDPATH**/ ?>