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
      <?php if (isset($component)) { $__componentOriginal191be32db1a77a3a45a47b7ee89f9c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal191be32db1a77a3a45a47b7ee89f9c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pengajuan.table-pengajuan-admin','data' => ['items' => $pengajuans]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pengajuan.table-pengajuan-admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pengajuans)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal191be32db1a77a3a45a47b7ee89f9c0a)): ?>
<?php $attributes = $__attributesOriginal191be32db1a77a3a45a47b7ee89f9c0a; ?>
<?php unset($__attributesOriginal191be32db1a77a3a45a47b7ee89f9c0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal191be32db1a77a3a45a47b7ee89f9c0a)): ?>
<?php $component = $__componentOriginal191be32db1a77a3a45a47b7ee89f9c0a; ?>
<?php unset($__componentOriginal191be32db1a77a3a45a47b7ee89f9c0a); ?>
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
      <?php if (isset($component)) { $__componentOriginal0a9083b859d721e0b56476b8248cfa0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a9083b859d721e0b56476b8248cfa0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.riwayat.table-riwayat-admin','data' => ['items' => $riwayats]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('riwayat.table-riwayat-admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($riwayats)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a9083b859d721e0b56476b8248cfa0d)): ?>
<?php $attributes = $__attributesOriginal0a9083b859d721e0b56476b8248cfa0d; ?>
<?php unset($__attributesOriginal0a9083b859d721e0b56476b8248cfa0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a9083b859d721e0b56476b8248cfa0d)): ?>
<?php $component = $__componentOriginal0a9083b859d721e0b56476b8248cfa0d; ?>
<?php unset($__componentOriginal0a9083b859d721e0b56476b8248cfa0d); ?>
<?php endif; ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
  function setRiwayatTabFlag() {
    document.getElementById('tabInput').value = 'riwayat';
  }

  function showTab(tab) {
    ['pengajuan', 'riwayat'].forEach(id => {
      document.getElementById(`tab${capitalize(id)}`)?.classList.toggle('text-[#003366]', id === tab);
      document.getElementById(`tab${capitalize(id)}`)?.classList.toggle('text-gray-500', id !== tab);
      document.getElementById(`underline${capitalize(id)}`)?.classList.toggle('scale-x-100', id === tab);
      document.getElementById(`underline${capitalize(id)}`)?.classList.toggle('scale-x-0', id !== tab);
      document.getElementById(`${id}Tab`)?.classList.toggle('hidden', id !== tab);
    });
    closeModal(); // Tutup modal saat ganti tab
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const tab = new URLSearchParams(window.location.search).get('tab') || 'pengajuan';
    showTab(tab);
  });

  // Fungsi GLOBAL
  window.showDetail = function(id) {
    console.log('[DEBUG] Global showDetail called with id:', id);
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);
        el('judulKegiatan').innerText = data.judul_kegiatan || '-';
        el('waktuKegiatan').innerText = data.tgl_kegiatan + ' (' + data.waktu_mulai?.slice(0,5) + ' - ' + data.waktu_berakhir?.slice(0,5) + ')';
        el('aktivitas').innerText = data.aktivitas || '-';
        el('organisasi').innerText = data.organisasi || '-';
        el('penanggungJawab').innerText = data.penanggung_jawab || '-';
        el('keterangan').innerText = data.deskripsi_kegiatan || '-';
        el('ruangan').innerText = data.nama_ruangan || '-';

        const perlengkapanList = el('perlengkapan');
        perlengkapanList.innerHTML = '';
        if (Array.isArray(data.perlengkapan) && data.perlengkapan.length > 0) {
          data.perlengkapan.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.nama} - ${item.jumlah}`;
            perlengkapanList.appendChild(li);
          });
        } else {
          const li = document.createElement('li');
          li.className = 'text-gray-400 italic';
          li.textContent = 'Tidak ada perlengkapan';
          perlengkapanList.appendChild(li);
        }

        const link = el('linkDokumen');
        const notfound = el('dokumenNotFound');
        if (data.link_dokumen === 'ada') {
          const downloadUrl = `/admin/peminjaman/download-proposal/${data.id}`;
          link.href = downloadUrl;
          link.classList.remove('hidden');
          notfound.classList.add('hidden');
        } else {
          link.href = '#';
          link.classList.add('hidden');
          notfound.classList.remove('hidden');
        }

        const diskusiArea = el('diskusiArea');
        if (Array.isArray(data.diskusi)) {
          let html = '';
          data.diskusi.forEach(d => {
            html += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
          });
          diskusiArea.innerHTML = html || '<p class="text-gray-400 italic">Belum ada diskusi</p>';
        }

        document.getElementById('detailModal').classList.remove('hidden');
        window.currentPeminjamanId = data.id;
      })
      .catch(err => {
        console.error('Gagal memuat detail:', err);
        alert('Gagal memuat detail peminjaman.');
      });
  };

  window.closeModal = function() {
    document.getElementById('detailModal')?.classList.add('hidden');
  };
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/admin/peminjaman.blade.php ENDPATH**/ ?>