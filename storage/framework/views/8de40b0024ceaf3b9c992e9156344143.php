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
  <table class="w-full text-sm text-left text-gray-700">
    <thead class="bg-gray-100 text-black border-b">
      <tr class="text-sm font-semibold">
        <th class="px-4 py-2">No.</th>
        <th class="px-4 py-2">Pengajuan</th>
        <th class="px-4 py-2">Tanggal Pengajuan</th>
        <th class="px-4 py-2">Verifikasi BEM</th>
        <th class="px-4 py-2">Verifikasi Sarpras</th>
        <th class="px-4 py-2">Organisasi</th>
        <th class="px-4 py-2">Status Pengembalian</th>
        <th class="px-4 py-2 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e(\Carbon\Carbon::parse($item->tgl_kegiatan)->format('d/m/Y')); ?></td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full
            <?php if($item->verifikasi_bem === 'diterima'): ?>
              bg-green-100 text-green-700 font-medium
            <?php elseif($item->verifikasi_bem === 'ditolak'): ?>
              bg-red-100 text-red-600
            <?php else: ?>
              bg-yellow-500 text-white
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_bem)); ?>

          </span>
        </td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full
            <?php if($item->verifikasi_sarpras === 'diterima'): ?>
              bg-green-100 text-green-700 font-medium
            <?php elseif($item->verifikasi_sarpras === 'ditolak'): ?>
              bg-red-100 text-red-600
            <?php else: ?>
              bg-grey-100 text-grey-500 font-medium
            <?php endif; ?>">
            <?php echo e(ucfirst($item->verifikasi_sarpras)); ?>

          </span>
        </td>
        <td class="px-4 py-2"><?php echo e($item->organisasi); ?></td>
        <td class="px-4 py-2">
          <?php if($item->status_peminjaman === 'ambil'): ?>
            <form method="POST" action="<?php echo e(route('admin.peminjaman.kembalikan', $item->id)); ?>" onsubmit="return confirm('Yakin ingin menandai sebagai dikembalikan?')">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PATCH'); ?>
              <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Selesai</button>
            </form>
          <?php elseif($item->status_pengembalian === 'selesai'): ?>
            <span class="text-xs text-gray-600 italic">Selesai</span>
          <?php else: ?>
            <span class="text-xs text-gray-500 italic">-</span>
          <?php endif; ?>
        </td>
        <td class="px-4 py-2 text-center">
          <div class="flex items-center gap-2 justify-center">
            <?php if($item->verifikasi_sarpras !== 'diterima'): ?>
              <form method="POST" action="<?php echo e(route('admin.peminjaman.verifikasi', $item->id)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="verifikasi_sarpras" value="diterima">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">Terima</button>
              </form>
            <?php endif; ?>

            <button onclick="showTangguhkanPopup(<?php echo e($item->id); ?>)"
              class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">
              Tangguhkan
            </button>

            <button onclick="showDetail(<?php echo e($item->id); ?>)" class="text-gray-600 hover:text-blue-700" title="Detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>            </button>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
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
  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('#btnKirimDiskusi');
    const input = modal.querySelector('#inputDiskusi');
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

        const formatTanggal = (tgl) => {
          const d = new Date(tgl);
          const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
        };

        const formatJam = (jam) => jam?.substring(0, 5) || '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('waktuKegiatan').textContent = `${formatTanggal(data.tgl_kegiatan)} ${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('organisasi').textContent = data.organisasi || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

        // Update dokumen link to use secure download route if dokumen exists
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
          el('linkDokumen').classList.remove('pointer-events-none', 'text-gray-400');
          el('dokumenNotFound').classList.add('hidden');
        } else {
          el('linkDokumen').href = '#';
          el('linkDokumen').onclick = null;
          el('linkDokumen').classList.add('pointer-events-none', 'text-gray-400');
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

        // Diskusi
        let diskusiHtml = 'belum ada diskusi';
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
          });
        }
        document.getElementById('diskusiArea').innerHTML = diskusiHtml;
        document.getElementById('inputDiskusi').value = '';
        document.getElementById('inputDiskusi').removeAttribute('disabled');
        document.getElementById('btnKirimDiskusi').removeAttribute('disabled');
        document.getElementById('btnKirimDiskusi').classList.remove('bg-gray-300', 'cursor-not-allowed');
        document.getElementById('btnKirimDiskusi').classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
        document.getElementById('detailModal').classList.remove('hidden');
        bindDiskusiHandler(); // <--- re-bind setiap modal dibuka
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }

  let tangguhPeminjamanId = null;

  function showTangguhkanPopup(id) {
    tangguhPeminjamanId = id;
    document.getElementById('alasanTangguh').value = '';
    document.getElementById('tangguhkanModal').classList.remove('hidden');
  }

  function closeTangguhkan() {
    document.getElementById('tangguhkanModal').classList.add('hidden');
  }

  function submitTangguhkan() {
    const alasan = document.getElementById('alasanTangguh').value.trim();
    if (!alasan || !tangguhPeminjamanId) return alert('Alasan tidak boleh kosong.');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/diskusi', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({
        peminjaman_id: tangguhPeminjamanId,
        pesan: `<span class="text-red-600 font-semibold">DITANGGUHKAN:</span> ${alasan}`
      })
    })
    .then(res => res.json())
    .then(resp => {
      if (resp.success) {
        closeTangguhkan();
        showDetail(tangguhPeminjamanId); // refresh diskusi
      } else {
        alert(resp.error || 'Gagal mengirim alasan.');
      }
    })
    .catch(() => alert('Terjadi kesalahan saat mengirim alasan.'));
  }

</script>
<?php $__env->stopPush(); ?>

<!-- Modal Popup Tangguhkan -->
<div id="tangguhkanModal" class="fixed inset-0 z-[999] hidden bg-black/40 px-4 flex items-center justify-center">
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow" onclick="event.stopPropagation()">
    <h2 class="text-md font-semibold mb-3 text-gray-800">Berikan alasan menangguhkan peminjaman</h2>
    <textarea id="alasanTangguh" class="w-full border border-gray-300 rounded px-3 py-2 text-sm mb-4" rows="3" placeholder="Tulis alasan Anda..."></textarea>
    <div class="flex justify-end gap-2">
      <button onclick="closeTangguhkan()" class="text-gray-500 hover:underline text-sm">Batal</button>
      <button onclick="submitTangguhkan()" class="bg-green-600 hover:bg-green-800 text-white px-4 py-1 text-sm rounded">Kirim</button>
    </div>
  </div>
</div>


<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/pengajuan/table-pengajuan-admin.blade.php ENDPATH**/ ?>