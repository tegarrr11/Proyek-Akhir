<table class="min-w-full bg-white border rounded-lg">
    <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th class="px-4 py-2">Judul Kegiatan</th>
            <th class="px-4 py-2">Tanggal</th>
            <th class="px-4 py-2">Waktu</th>
            <th class="px-4 py-2">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
                <td class="px-4 py-2"><?php echo e($item->tgl_kegiatan); ?></td>
                <td class="px-4 py-2"><?php echo e($item->waktu_mulai); ?> - <?php echo e($item->waktu_berakhir); ?></td>
                <td class="px-4 py-2">
                    <?php if($item->verifikasi_sarpras === 'diterima'): ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Diterima</span>
                    <?php elseif($item->verifikasi_sarpras === 'ditangguhkan'): ?>
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Ditangguhkan</span>
                    <?php else: ?>
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Proses</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" class="text-center py-4 text-gray-500">Belum ada pengajuan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/pengajuan/table-pengajuan-dosen.blade.php ENDPATH**/ ?>