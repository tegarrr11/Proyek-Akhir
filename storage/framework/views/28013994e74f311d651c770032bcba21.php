<?php if (isset($component)) { $__componentOriginal4f7bc4b16f510eaf51034cbc9bd53997 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f7bc4b16f510eaf51034cbc9bd53997 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-wrapper','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <table class="min-w-full bg-white border rounded-lg">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th class="px-4 py-2 text-left">No</th>
            <th class="px-4 py-2 text-left">Judul Kegiatan</th>
            <th class="px-4 py-2 text-left">Ruangan</th> 
            <th class="px-4 py-2 text-left">Tanggal</th>
            <th class="px-4 py-2 text-left">Waktu</th>
            <th class="px-4 py-2 text-left">Penanggung Jawab</th>
        </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td class="px-4 py-2"><?php echo e($index + 1); ?></td>
            <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
            <td class="px-4 py-2"><?php echo e($item->gedung->nama ?? '-'); ?></td> 
            <td class="px-4 py-2"><?php echo e($item->tgl_kegiatan); ?></td>
            <td class="px-4 py-2"><?php echo e($item->waktu_mulai); ?> - <?php echo e($item->waktu_berakhir); ?></td>
            <td class="px-4 py-2"><?php echo e($item->penanggung_jawab ?? '-'); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada pengajuan.</td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f7bc4b16f510eaf51034cbc9bd53997)): ?>
<?php $attributes = $__attributesOriginal4f7bc4b16f510eaf51034cbc9bd53997; ?>
<?php unset($__attributesOriginal4f7bc4b16f510eaf51034cbc9bd53997); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f7bc4b16f510eaf51034cbc9bd53997)): ?>
<?php $component = $__componentOriginal4f7bc4b16f510eaf51034cbc9bd53997; ?>
<?php unset($__componentOriginal4f7bc4b16f510eaf51034cbc9bd53997); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/riwayat/table-riwayat-dosen.blade.php ENDPATH**/ ?>