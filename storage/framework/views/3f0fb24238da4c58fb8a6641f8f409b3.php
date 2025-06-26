<table class="w-full text-sm text-left text-gray-700">
  <thead class="bg-gray-100 text-black border-b">
    <tr class="text-sm font-semibold">
      <th class="px-4 py-2">No.</th>
      <th class="px-4 py-2">Pengajuan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($pengajuan->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e(\Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y')); ?></td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded <?php echo e($pengajuan->verifikasi_bem === 'diterima' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600'); ?>">
            <?php echo e(ucfirst($pengajuan->verifikasi_bem)); ?>

          </span>
        </td>

        <td class="px-4 py-2 text-gray-500 text-xs">-</td>
        <td class="px-4 py-2"><?php echo e($pengajuan->organisasi); ?></td>

        <td class="px-4 py-2">
          <div class="flex items-center gap-2 justify-center">
            <form method="POST" action="<?php echo e(route('bem.peminjaman.verifikasi', $pengajuan->id)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="verifikasi_bem" value="diterima">
              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded">
                Terima
              </button>
            </form>

            <button onclick="showDetail(<?php echo e($pengajuan->id); ?>)" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>


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

<?php $__env->startPush('scripts'); ?>
<script>
  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('#btnKirimDiskusi');
    const input = modal.querySelector('#inputDiskusi');
    if (!btn || !input) return;

    btn.onclick = function () {
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
          if (resp.success) showDetail(currentPeminjamanId);
          else alert(resp.error || 'Gagal mengirim pesan.');
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

        const formatTanggal = (tgl) => new Date(tgl).toLocaleDateString('id-ID', {
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        });

        const formatJam = (jamStr) => jamStr ? jamStr.slice(0, 5) : '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

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
          li.className = 'italic text-gray-400';
          li.textContent = 'Tidak ada perlengkapan';
          perlengkapanList.appendChild(li);
        }

        // Dokumen
        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin', 'mahasiswa', 'bem', 'dosen', 'staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;
          el('linkDokumen').href = downloadUrl;
          el('linkDokumen').onclick = function (e) {
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

        // Diskusi
        let diskusiHtml = 'belum ada diskusi';
        let adaChatAdminBem = false;
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
            if (["admin", "bem"].includes((d.role || '').toLowerCase())) adaChatAdminBem = true;
          });
        }
        document.getElementById('diskusiArea').innerHTML = diskusiHtml;

        const userRole = "<?php echo e(auth()->user()->role); ?>";
        let enableDiskusi = false;
        if (userRole !== 'dosen') {
          if (userRole === 'mahasiswa') {
            if (adaChatAdminBem) enableDiskusi = true;
          } else {
            enableDiskusi = true;
          }
        }

        const inputDiskusi = document.getElementById('inputDiskusi');
        const btnKirimDiskusi = document.getElementById('btnKirimDiskusi');
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
        document.getElementById('detailModal').classList.remove('hidden');
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
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/pengajuan/table-pengajuan-bem.blade.php ENDPATH**/ ?>