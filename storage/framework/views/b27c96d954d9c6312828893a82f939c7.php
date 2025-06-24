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

  <div class="bg-white rounded-md shadow flex-1 p-6">
    
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

      <div class="flex gap-2">
        <input type="text" placeholder="Cari........"
          class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-[#003366]">
        <button class="border px-3 py-1 rounded text-sm text-[#003366] border-[#003366] hover:bg-[#003366] hover:text-white">
          Filter
        </button>
      </div>
    </div>

    
    <div id="pengajuanTab">
      <?php if (isset($component)) { $__componentOriginalade2fba424f3d7393749019df828893e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalade2fba424f3d7393749019df828893e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pengajuan-mahasiswa','data' => ['items' => $pengajuans]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pengajuan-mahasiswa'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pengajuans)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalade2fba424f3d7393749019df828893e)): ?>
<?php $attributes = $__attributesOriginalade2fba424f3d7393749019df828893e; ?>
<?php unset($__attributesOriginalade2fba424f3d7393749019df828893e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalade2fba424f3d7393749019df828893e)): ?>
<?php $component = $__componentOriginalade2fba424f3d7393749019df828893e; ?>
<?php unset($__componentOriginalade2fba424f3d7393749019df828893e); ?>
<?php endif; ?>
    </div>

    
    <div id="riwayatTab" class="hidden">
      <?php if (isset($component)) { $__componentOriginal6336da076adaf0302b686d53569f89e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6336da076adaf0302b686d53569f89e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-riwayat','data' => ['items' => $riwayats]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-riwayat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($riwayats)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6336da076adaf0302b686d53569f89e2)): ?>
<?php $attributes = $__attributesOriginal6336da076adaf0302b686d53569f89e2; ?>
<?php unset($__attributesOriginal6336da076adaf0302b686d53569f89e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6336da076adaf0302b686d53569f89e2)): ?>
<?php $component = $__componentOriginal6336da076adaf0302b686d53569f89e2; ?>
<?php unset($__componentOriginal6336da076adaf0302b686d53569f89e2); ?>
<?php endif; ?>
    </div>
  </div>

  
  <script>
    function showTab(tab) {
      const tabs = ['pengajuan', 'riwayat'];

      tabs.forEach(id => {
        const tabEl = document.getElementById(`tab${capitalize(id)}`);
        const underline = document.getElementById(`underline${capitalize(id)}`);

        if (id === tab) {
          tabEl.classList.remove('text-gray-500');
          tabEl.classList.add('text-[#003366]');
          underline.classList.add('scale-x-100');
          underline.classList.remove('scale-x-0');
          document.getElementById(`${id}Tab`).classList.remove('hidden');
        } else {
          tabEl.classList.add('text-gray-500');
          tabEl.classList.remove('text-[#003366]');
          underline.classList.add('scale-x-0');
          underline.classList.remove('scale-x-100');
          document.getElementById(`${id}Tab`).classList.add('hidden');
        }
      });
    }

    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }

    document.addEventListener('DOMContentLoaded', function () {
      showTab('pengajuan');
    });

    function showModal(id) {
      fetch(`/admin/peminjaman/${id}`)
        .then(res => res.json())
        .then(data => {
          const el = id => document.getElementById(id);
          el('judulKegiatan').textContent = data.judul_kegiatan || '-';
          el('waktuKegiatan').textContent = `${data.tgl_kegiatan} ${data.waktu_mulai} - ${data.waktu_berakhir}`;
          el('aktivitas').textContent = data.aktivitas || '-';
          el('organisasi').textContent = data.organisasi || '-';
          el('penanggungJawab').textContent = data.penanggung_jawab || '-';
          el('keterangan').textContent = data.deskripsi_kegiatan || '-';
          el('ruangan').textContent = data.nama_ruangan || '-';

          // Ganti link dokumen agar download via route Laravel, bukan direct storage
          if (data.link_dokumen && data.link_dokumen !== '#') {
            let prefix = window.location.pathname.split('/')[1];
            if (!['admin','mahasiswa','bem','dosen','staff'].includes(prefix)) prefix = '';
            let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;
            el('linkDokumen').href = downloadUrl;
            el('linkDokumen').onclick = function(e) {
              e.preventDefault();
              fetch(downloadUrl, {
                method: 'GET',
                credentials: 'same-origin',
              })
              .then(response => {
                if (!response.ok) throw new Error('Gagal download dokumen');
                return response.blob();
              })
              .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'proposal.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
              })
              .catch(() => alert('Gagal download dokumen.'));
            };
            el('linkDokumen').classList.remove('hidden');
            el('dokumenNotFound').classList.add('hidden');
          } else {
            el('linkDokumen').href = '#';
            el('linkDokumen').onclick = null;
            el('linkDokumen').classList.add('hidden');
            el('dokumenNotFound').classList.remove('hidden');
          }

          const perlengkapanList = el('perlengkapan');
          perlengkapanList.innerHTML = '';
          if (data.perlengkapan?.length > 0) {
            data.perlengkapan.forEach(item => {
              const li = document.createElement('li');
              li.textContent = `${item.nama} - ${item.jumlah}`;
              perlengkapanList.appendChild(li);
            });
          } else {
            const li = document.createElement('li');
            li.className = 'italic text-gray-400';
            li.textContent = 'Tidak ada perlengkapan';
            perlengkapanList.appendChild(li);
          }

          el('diskusiArea').textContent = 'belum ada diskusi';
          document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(err => {
          console.error(err);
          alert('Gagal memuat detail peminjaman.');
        });
    }

    function closeModal() {
      document.getElementById('detailModal')?.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
      showTab('pengajuan');
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/mahasiswa/peminjaman.blade.php ENDPATH**/ ?>