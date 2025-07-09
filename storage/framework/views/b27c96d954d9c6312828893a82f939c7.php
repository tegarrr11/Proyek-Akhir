<?php $__env->startSection('title', 'Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
  <?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => ['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Pengajuan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Pengajuan']); ?>
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

  <div class="bg-white rounded-md shadow flex-1 p-6 overflow-visible">
    
    <div class="flex items-center justify-between mb-4">
      <div class="flex gap-6 relative">
        <button onclick="showTab('pengajuan')" id="tabPengajuan"
          class="pb-2 relative text-sm font-semibold text-[#003366]">
          <span>Pengajuan</span>
          <span class="absolute left-0 -bottom-0.5 w-full h-[2px] bg-[#003366] scale-x-100 origin-left transition-transform duration-300" id="underlinePengajuan"></span>
        </button>

        <button onclick="showTab('riwayat')" id="tabRiwayat"
          class="pb-2 relative text-sm font-semibold text-gray-500">
          <span>Riwayat</span>
          <span class="absolute left-0 -bottom-0.5 w-full h-[2px] bg-[#003366] scale-x-0 origin-left transition-transform duration-300" id="underlineRiwayat"></span>
        </button>
      </div>

      <div class="relative flex items-center gap-2">
        <!-- Tombol Search (hanya muncul di mobile) -->
        <button id="searchIcon" onclick="toggleSearchInput()" class="md:hidden text-gray-600">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="#777" d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"/>
          </svg>
        </button>

        <!-- Input Search -->
        <input
          type="text"
          id="searchInput"
          placeholder="cari kegiatan ..."
          class="hidden md:block border border-gray-300 rounded px-3 py-1 text-sm bg-white shadow z-20 w-40 md:w-52
                focus:outline-none focus:ring-0 focus:border-gray-300 transition-all"
        />
      </div>

    </div>

    
    <div id="pengajuanTab">
        <div id="tablePengajuan">
          <?php echo $__env->make('components.pengajuan.table-pengajuan-mahasiswa', ['items' => $pengajuans], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    <div id="riwayatTab" class="hidden">
        <div id="tableRiwayat">
          <?php echo $__env->make('components.riwayat.table-riwayat-mahasiswa', ['items' => $riwayats], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
  </div>

  
  <?php if (isset($component)) { $__componentOriginal7a9d86cd1f97d28e5afda3793042d89d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a9d86cd1f97d28e5afda3793042d89d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-detail-peminjaman','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-detail-peminjaman'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a9d86cd1f97d28e5afda3793042d89d)): ?>
<?php $attributes = $__attributesOriginal7a9d86cd1f97d28e5afda3793042d89d; ?>
<?php unset($__attributesOriginal7a9d86cd1f97d28e5afda3793042d89d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a9d86cd1f97d28e5afda3793042d89d)): ?>
<?php $component = $__componentOriginal7a9d86cd1f97d28e5afda3793042d89d; ?>
<?php unset($__componentOriginal7a9d86cd1f97d28e5afda3793042d89d); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    showTab('pengajuan');

    const searchInput = document.getElementById('searchInput');

    searchInput?.addEventListener('input', function () {
      const keyword = this.value.toLowerCase();
      const activeTable = getActiveTableId();
      if (!activeTable) return;
      const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
      rows.forEach(row => {
        const kolomJudul = row.children[1];
        const isi = kolomJudul?.textContent.toLowerCase() || '';
        row.style.display = isi.includes(keyword) ? '' : 'none';
      });
    });


    function getActiveTableId() {
      if (!document.getElementById('pengajuanTab').classList.contains('hidden')) return 'tablePengajuan';
      if (!document.getElementById('riwayatTab').classList.contains('hidden')) return 'tableRiwayat';
      return null;
    }

    window.showTab = function(tab) {
      const tabs = ['pengajuan', 'riwayat'];
      tabs.forEach(id => {
        const tabBtn = document.getElementById(`tab${capitalize(id)}`);
        const underline = document.getElementById(`underline${capitalize(id)}`);
        const tabContent = document.getElementById(`${id}Tab`);

        if (id === tab) {
          tabBtn?.classList.remove('text-gray-500');
          tabBtn?.classList.add('text-[#003366]');
          underline?.classList.add('scale-x-100');
          underline?.classList.remove('scale-x-0');
          tabContent?.classList.remove('hidden');
        } else {
          tabBtn?.classList.add('text-gray-500');
          tabBtn?.classList.remove('text-[#003366]');
          underline?.classList.add('scale-x-0');
          underline?.classList.remove('scale-x-100');
          tabContent?.classList.add('hidden');
        }
      });

      // Reset pencarian dan tampilkan ulang semua baris
      const input = document.getElementById('searchInput');
      input.value = '';
      const activeTable = tab === 'pengajuan' ? 'tablePengajuan' : 'tableRiwayat';
      const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
      rows.forEach(row => row.style.display = '');
    }

    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  });

function toggleSearchInput() {
  const input = document.getElementById('searchInput');
  const icon = document.getElementById('searchIcon');

  input.classList.toggle('hidden');
  if (!input.classList.contains('hidden')) {
    input.focus();
    icon.classList.add('hidden');
  } else {
    icon.classList.remove('hidden');
  }
}

document.addEventListener('click', function (e) {
  const input = document.getElementById('searchInput');
  const icon = document.getElementById('searchIcon');

  if (
    !input.contains(e.target) &&
    !icon.contains(e.target)
  ) {
    input.classList.add('hidden');
    icon.classList.remove('hidden');
  }
});

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/mahasiswa/peminjaman.blade.php ENDPATH**/ ?>