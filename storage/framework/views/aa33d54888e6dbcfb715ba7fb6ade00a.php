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
        <?php echo $__env->make('components.pengajuan.table-pengajuan-mahasiswa', ['items' => $pengajuans], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div id="riwayatTab" class="hidden">
        <?php echo $__env->make('components.riwayat.table-riwayat-mahasiswa', ['items' => $riwayats], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
  function tampilkanKolomKembali(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('tr');
    row.querySelector('.status-kembali-col')?.classList.remove('hidden');
    form.submit();
  }

  function showTab(tab) {
    const tabs = ['pengajuan', 'riwayat'];

    tabs.forEach(id => {
      const tabEl = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);

      if (id === tab) {
        tabEl?.classList.remove('text-gray-500');
        tabEl?.classList.add('text-[#003366]');
        underline?.classList.add('scale-x-100');
        underline?.classList.remove('scale-x-0');
        document.getElementById(`${id}Tab`)?.classList.remove('hidden');
      } else {
        tabEl?.classList.add('text-gray-500');
        tabEl?.classList.remove('text-[#003366]');
        underline?.classList.add('scale-x-0');
        underline?.classList.remove('scale-x-100');
        document.getElementById(`${id}Tab`)?.classList.add('hidden');
      }
    });
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    showTab('pengajuan');
  });

  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('.btnKirimDiskusi');
    const input = modal.querySelector('.inputDiskusi');
    if (!btn || !input) return;

    btn.onclick = function() {
      const pesan = input.value.trim();
      if (!pesan || !currentPeminjamanId) return;
      btn.setAttribute('disabled', true);
      let csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (!csrf) {
        const tokenInput = document.querySelector('input[name=_token]');
        if (tokenInput) csrf = tokenInput.value;
      }

      fetch('/diskusi', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ peminjaman_id: currentPeminjamanId, pesan })
      })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          showDetail(currentPeminjamanId);
        } else {
          alert(resp.error || 'Gagal mengirim pesan.');
        }
      })
      .catch(() => alert('Gagal mengirim pesan.'));
    };
  }

  function showDetail(id) {
    currentPeminjamanId = id;

    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);

        const formatTanggal = (tgl) => {
          const date = new Date(tgl);
          return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
          });
        };

        const formatJam = (jamStr) => jamStr ? jamStr.slice(0, 5) : '-';

        if (el('judulKegiatan')) el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        if (el('tglKegiatan')) el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        if (el('jamKegiatan')) el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        if (el('aktivitas')) el('aktivitas').textContent = data.aktivitas || '-';
        if (el('organisasi')) el('organisasi').textContent = data.organisasi || '-';
        if (el('penanggungJawab')) el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        if (el('keterangan')) el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        if (el('ruangan')) el('ruangan').textContent = data.nama_ruangan || '-';

        const linkDokumen = el('linkDokumen');
        const dokumenNotFound = el('dokumenNotFound');
        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin','mahasiswa','bem','dosen','staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;

          if (linkDokumen) {
            linkDokumen.href = downloadUrl;
            linkDokumen.onclick = function(e) {
              e.preventDefault();
              fetch(downloadUrl)
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
            linkDokumen.classList.remove('hidden');
          }
          dokumenNotFound?.classList.add('hidden');
        } else {
          if (linkDokumen) {
            linkDokumen.href = '#';
            linkDokumen.onclick = null;
            linkDokumen.classList.add('hidden');
          }
          dokumenNotFound?.classList.remove('hidden');
        }

        const perlengkapanList = el('perlengkapan');
        if (perlengkapanList) {
          perlengkapanList.innerHTML = '';
          if (Array.isArray(data.perlengkapan) && data.perlengkapan.length > 0) {
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
        }

        let diskusiHtml = 'belum ada diskusi';
        let adaChatAdminBem = false;
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
            if (["admin", "bem"].includes((d.role || '').toLowerCase())) {
              adaChatAdminBem = true;
            }
          });
        }
        if (el('diskusiArea')) el('diskusiArea').innerHTML = diskusiHtml;

        const userRole = "<?php echo e(auth()->user()->role); ?>";
        let enableDiskusi = false;
        if (userRole !== 'dosen') {
          if (userRole === 'mahasiswa' && adaChatAdminBem) {
            enableDiskusi = true;
          } else if (userRole !== 'mahasiswa') {
            enableDiskusi = true;
          }
        }

        const modal = document.getElementById('detailModal');
        const inputDiskusi = modal?.querySelector('.inputDiskusi');
        const btnKirimDiskusi = modal?.querySelector('.btnKirimDiskusi');

        if (inputDiskusi && btnKirimDiskusi) {
          if (enableDiskusi) {
            inputDiskusi.removeAttribute('disabled');
            btnKirimDiskusi.removeAttribute('disabled');
            btnKirimDiskusi.classList.remove('bg-gray-300', 'cursor-not-allowed');
            btnKirimDiskusi.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
          } else {
            inputDiskusi.setAttribute('disabled', true);
            btnKirimDiskusi.setAttribute('disabled', true);
            btnKirimDiskusi.classList.add('bg-gray-300', 'cursor-not-allowed');
            btnKirimDiskusi.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
          }

          inputDiskusi.value = '';
        }

        modal?.classList.remove('hidden');
        bindDiskusiHandler();
      })
      .catch(err => {
        console.error('Gagal fetch detail:', err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Kuliah\Proyek Akhir\peminjaman-fasilitas\resources\views/pages/mahasiswa/peminjaman.blade.php ENDPATH**/ ?>