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
      <tr>
        <th class="px-4 py-2">No.</th>
        <th class="px-4 py-2">Pengajuan</th>
        <th class="px-4 py-2">Tanggal Pengajuan</th>
        <th class="px-4 py-2">Verifikasi BEM</th>
        <th class="px-4 py-2">Verifikasi Sarpras</th>
        <th class="px-4 py-2">Organisasi</th>
        <th class="px-4 py-2">Status Peminjaman</th>
        <th class="px-4 py-2">Status Pengembalian</th>
        <th class="px-4 py-2 hidden status-kembali-col">Status Pengembalian</th>
        <th class="px-4 py-2"></th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php if($item->status_pengembalian === 'selesai'): ?>
      <?php continue; ?>
      <?php endif; ?>

      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?></td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded <?php echo e($item->verifikasi_bem === 'diterima' ? 'bg-green-500 text-white text-xs px-3 py-1 rounded' : 'bg-gray-200 text-gray-600'); ?>">
            <?php echo e(ucfirst($item->verifikasi_bem)); ?>

          </span>
        </td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            <?php if($item->verifikasi_sarpras === 'diterima'): ?>
              bg-green-500 text-white text-xs px-3 py-1 rounded
            <?php elseif($item->verifikasi_sarpras === 'proses'): ?>
              bg-yellow-100 text-yellow-800
            <?php else: ?>
              bg-gray-200 text-gray-600
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_sarpras)); ?>

          </span>
        </td>

        <td class="px-4 py-2"><?php echo e($item->organisasi); ?></td>
        <td class="px-4 py-2">
          <?php if($item->verifikasi_sarpras === 'diterima'): ?>
          <?php if($item->status_peminjaman === 'kembalikan' && $item->status_pengembalian === 'proses'): ?>
          <form method="POST" action="<?php echo e(route('mahasiswa.peminjaman.kembalikan', $item->id)); ?>" onsubmit="return confirm('Yakin ingin mengembalikan barang ini?')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <button class="bg-yellow-500 text-white px-3 py-1 text-xs rounded hover:bg-yellow-600 flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Kembalikan
          </form>
          <?php elseif(is_null($item->status_peminjaman)): ?>
          <form method="POST" action="<?php echo e(route('mahasiswa.peminjaman.ambil', $item->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <button class="bg-blue-600 text-white px-3 py-1 text-xs rounded hover:bg-blue-700">Ambil</button>
          </form>
          <?php else: ?>
          <?php echo e(ucfirst($item->status_peminjaman)); ?>

          <?php endif; ?>
          <?php else: ?>
          <span class="text-gray-400 text-xs italic">-</span>
          <?php endif; ?>
        </td>

        <td class="px-4 py-2">
          <?php if($item->status_peminjaman === 'kembalikan'): ?>
          <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">Belum</span>
          <?php else: ?>
          <span class="text-gray-400 text-xs italic">-</span>
          <?php endif; ?>
        </td>
        <td class="px-4 py-2 hidden status-kembali-col"><?php echo e(ucfirst($item->status_pengembalian ?? '-')); ?></td>

        <td class="px-4 py-2">
          <div class="flex items-center gap-2">
            <button onclick="showDetail(<?php echo e($item->id); ?>)" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
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

<?php $__env->startPush('scripts'); ?>
<script>
  console.log('[DEBUG] Script chat loaded');

  function tampilkanKolomKembali(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('tr');
    row.querySelector('.status-kembali-col').classList.remove('hidden');
    form.submit();
  }

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

  document.addEventListener('DOMContentLoaded', function() {
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
          body: JSON.stringify({
            peminjaman_id: currentPeminjamanId,
            pesan
          })
        })
        .then(res => res.json())
        .then(resp => {
          if (resp.success) {
            showDetail(currentPeminjamanId); // refresh chat
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
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/pengajuan/table-pengajuan-mahasiswa.blade.php ENDPATH**/ ?>