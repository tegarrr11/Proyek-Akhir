<?php
  // Pagination manual di Blade
  $perPage = 10;
  $currentPage = request()->get('page', 1);
  $offset = ($currentPage - 1) * $perPage;

  $paginatedItems = $items->slice($offset, $perPage)->values();
  $totalPages = ceil($items->count() / $perPage);
?>

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
      <?php $__empty_1 = true; $__currentLoopData = $paginatedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($offset + $i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e($item->created_at ? $item->created_at->format('d/m/Y') : '-'); ?></td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            <?php if($item->verifikasi_bem === 'diterima'): ?>
              bg-green-500 text-white
            <?php elseif($item->verifikasi_bem === 'ditolak'): ?>
              bg-red-100 text-red-600
            <?php else: ?>
              bg-yellow-500 text-white
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_bem ?? '-')); ?>

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
            <?php echo e(ucfirst($item->verifikasi_sarpras ?? '-')); ?>

          </span>
        </td>
        <td class="px-4 py-2"><?php echo e($item->organisasi ?? '-'); ?></td>
        <td class="px-4 py-2">
          <button
            onclick="showDetail(<?php echo e($item->id); ?>)"
            class="text-blue-600 hover:text-blue-800 text-sm"
            title="Lihat Detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>            </button>
          </button>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada riwayat.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  
  <div class="flex justify-start mt-4 pl-4 pb-4 gap-1">
    <?php for($page = 1; $page <= $totalPages; $page++): ?>
      <a href="<?php echo e(request()->fullUrlWithQuery(['page' => $page, 'tab' => 'riwayat'])); ?>"
        class="px-3 py-1 rounded-md border text-sm shadow-sm transition
                <?php echo e($page == $currentPage
                    ? 'bg-sky-900 text-white '
                    : 'bg-white text-gray-700 hover:bg-gray-100'); ?>">
        <?php echo e($page); ?>

      </a>
    <?php endfor; ?>
  </div>
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
<?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/components/riwayat/table-riwayat-admin.blade.php ENDPATH**/ ?>