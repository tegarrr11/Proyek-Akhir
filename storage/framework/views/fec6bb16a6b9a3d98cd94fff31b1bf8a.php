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

  
  <?php if(session('success')): ?>
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
      <?php echo e(session('success')); ?>

    </div>
  <?php endif; ?>

  
  <div class="bg-white rounded-lg shadow p-6">
    
    <div class="flex items-center justify-between mb-6">
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
    </div>

    
    <div id="pengajuanTab">
      <?php if (isset($component)) { $__componentOriginal5349c6c05cc3b6f27427532c80d7384d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5349c6c05cc3b6f27427532c80d7384d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pengajuan-admin','data' => ['items' => $pengajuans]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pengajuan-admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pengajuans)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5349c6c05cc3b6f27427532c80d7384d)): ?>
<?php $attributes = $__attributesOriginal5349c6c05cc3b6f27427532c80d7384d; ?>
<?php unset($__attributesOriginal5349c6c05cc3b6f27427532c80d7384d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5349c6c05cc3b6f27427532c80d7384d)): ?>
<?php $component = $__componentOriginal5349c6c05cc3b6f27427532c80d7384d; ?>
<?php unset($__componentOriginal5349c6c05cc3b6f27427532c80d7384d); ?>
<?php endif; ?>
    </div>

    
    <div id="riwayatTab" class="hidden">
      <div class="mb-4 flex items-center justify-between gap-2">
        <form method="GET" action="" class="flex gap-2 mb-0" onsubmit="setRiwayatTabFlag()">
          <select name="gedung_id" class="border rounded px-2 py-1 text-sm" onchange="setRiwayatTabFlag(); this.form.submit();">
            <option value="">Semua Ruangan</option>
            <?php $__currentLoopData = App\Models\Gedung::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($gedung->id); ?>" <?php echo e(request('gedung_id') == $gedung->id ? 'selected' : ''); ?>><?php echo e($gedung->nama); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <input type="hidden" name="tab" id="tabInput" value="riwayat">
        </form>
        <a href="<?php echo e(route('download.riwayat.admin')); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
          </svg>
          Download Riwayat
        </a>
      </div>
      <?php if (isset($component)) { $__componentOriginal40a1528af7d6066e2b2d8842928a43be = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal40a1528af7d6066e2b2d8842928a43be = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-riwayat-admin','data' => ['items' => $riwayats]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-riwayat-admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($riwayats)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal40a1528af7d6066e2b2d8842928a43be)): ?>
<?php $attributes = $__attributesOriginal40a1528af7d6066e2b2d8842928a43be; ?>
<?php unset($__attributesOriginal40a1528af7d6066e2b2d8842928a43be); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal40a1528af7d6066e2b2d8842928a43be)): ?>
<?php $component = $__componentOriginal40a1528af7d6066e2b2d8842928a43be; ?>
<?php unset($__componentOriginal40a1528af7d6066e2b2d8842928a43be); ?>
<?php endif; ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function setRiwayatTabFlag() {
    document.getElementById('tabInput').value = 'riwayat';
  }

  function showTab(tab) {
    const tabs = ['pengajuan', 'riwayat'];
    tabs.forEach(id => {
      const tabEl = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);
      const tabDiv = document.getElementById(`${id}Tab`);
      if (id === tab) {
        tabEl.classList.remove('text-gray-500');
        tabEl.classList.add('text-[#003366]');
        underline.classList.add('scale-x-100');
        underline.classList.remove('scale-x-0');
        tabDiv.classList.remove('hidden');
      } else {
        tabEl.classList.add('text-gray-500');
        tabEl.classList.remove('text-[#003366]');
        underline.classList.add('scale-x-0');
        underline.classList.remove('scale-x-100');
        tabDiv.classList.add('hidden');
      }
    });
    // Sembunyikan modal detail setiap kali pindah tab
    const modal = document.getElementById('detailModal');
    if (modal) modal.classList.add('hidden');
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'pengajuan';
    showTab(tab);
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/admin/peminjaman.blade.php ENDPATH**/ ?>