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
        <th class="px-4 py-2">Tanggal</th>
        <th class="px-4 py-2">Verifikasi BEM</th>
        <th class="px-4 py-2">Verifikasi Sarpras</th>
        <th class="px-4 py-2">Status Peminjaman</th>
        <th class="px-4 py-2">Status Pengembalian</th>
        <th class="px-4 py-2 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr class="<?php echo e($i % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
        <td class="px-4 py-2"><?php echo e($i + 1); ?></td>
        <td class="px-4 py-2"><?php echo e($item->judul_kegiatan); ?></td>
        <td class="px-4 py-2"><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')); ?></td>
        <td class="px-4 py-2"><?php echo e(ucfirst($item->verifikasi_bem)); ?></td>
        <td class="px-4 py-2"><?php echo e(ucfirst($item->verifikasi_sarpras)); ?></td>
        <td class="px-4 py-2"><?php echo e($item->status_peminjaman ?? '-'); ?></td>
        <td class="px-4 py-2"><?php echo e($item->status_pengembalian ?? '-'); ?></td>
        <td class="px-4 py-2 text-center">
          <div class="flex gap-2 justify-center">
            <button onclick="showDetail(<?php echo e($item->id); ?>)"
              class="bg-indigo-500 text-white px-3 py-1 rounded text-xs hover:bg-indigo-600">
              Diskusi
            </button>
            
            <?php if($item->verifikasi_sarpras !== 'diterima'): ?>
            <form method="POST" action="<?php echo e(route('admin.peminjaman.approve', $item->id)); ?>">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PATCH'); ?>
              <button class="bg-yellow-500 hover:bg-blue-600 text-white px-3 py-1 text-xs rounded">Terima</button>
            </form>
            <?php elseif($item->status_peminjaman !== 'diambil'): ?>
            
            <form method="POST" action="<?php echo e(route('admin.peminjaman.ambil', $item->id)); ?>">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PATCH'); ?>
              <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-xs rounded">Ambil</button>
            </form>
            <?php elseif($item->status_peminjaman === 'diambil' && $item->status_pengembalian !== 'selesai'): ?>
            <button onclick="openModalSelesai(<?php echo e($item->id); ?>)" class="bg-green-600 hover:bg-blue-700 text-white px-3 py-1 text-xs rounded">Selesai</button>
            <?php else: ?>
            <span class="text-gray-400 italic">Selesai</span>
            <?php endif; ?>

            
            <button onclick="showDetail(<?php echo e($item->id); ?>)" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 16v-4" />
                <path d="M12 8h.01" />
              </svg>
            </button>
          </div>
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

<?php echo $__env->make('components.modal-selesai', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin', 'mahasiswa', 'bem', 'dosen', 'staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;
          el('linkDokumen').href = downloadUrl;
          el('linkDokumen').onclick = function(e) {
            e.preventDefault();
            fetch(downloadUrl, {
                method: 'GET',
                credentials: 'same-origin'
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
        bindDiskusiHandler();
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }

  function setujuiPeminjaman(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';

    fetch(`/admin/peminjaman/${id}/setujui`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf
        }
      })
      .then(() => {
        const aksiCell = document.getElementById(`aksi-${id}`);
        const statusCell = document.getElementById(`status-${id}`);

        if (aksiCell) {
          const btnAmbil = document.createElement('button');
          btnAmbil.className = 'bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600';
          btnAmbil.textContent = 'Ambil';
          btnAmbil.setAttribute('onclick', `ambilBarang(${id})`);
          aksiCell.querySelector(`#btn-setujui-${id}`)?.remove();
          aksiCell.appendChild(btnAmbil);
        }

        if (statusCell) {
          statusCell.innerHTML = `<span class="text-xs text-gray-500 italic">-</span>`;
        }

        // ✅ Update kolom Verifikasi Sarpras
        const row = aksiCell.closest('tr');
        if (row) {
          const verifikasiSarprasCell = row.querySelector('td:nth-child(5)');
          if (verifikasiSarprasCell) {
            verifikasiSarprasCell.innerHTML = `
              <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">
                Diterima
              </span>`;
          }
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal menyetujui peminjaman.');
      });
  }

  function ambilBarang(id) {
    fetch(`/admin/peminjaman/${id}/ambil`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
      })
      .then(() => {
        const aksiCell = document.getElementById(`aksi-${id}`);
        const statusCell = document.getElementById(`status-${id}`);

        if (aksiCell) {
          aksiCell.querySelector(`[onclick="ambilBarang(${id})"]`)?.remove();
          const btnSelesai = document.createElement('button');
          btnSelesai.className = 'bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700';
          btnSelesai.textContent = 'Selesai';
          btnSelesai.setAttribute('onclick', `showChecklistModal(${id})`);
          aksiCell.appendChild(btnSelesai);
        }

        if (statusCell) {
          statusCell.innerHTML = `<span class="text-xs text-gray-500 italic">Diambil</span>`;
        }
      });
  }

  function showChecklistModal(id) {
    window.currentPeminjamanId = id;

    fetch(`<?php echo e(url('admin/peminjaman')); ?>/${id}/checklist-html`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('checklistContent').innerHTML = data.html;

        const modal = document.getElementById('showChecklistModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat checklist.');
      });
  }

  function closeChecklistModal() {
    const modal = document.getElementById('showChecklistModal');
    modal.classList.add('hidden'); // sembunyikan kembali
    modal.classList.remove('flex'); // hapus flex
  }

  function submitChecklist() {
    if (!window.currentPeminjamanId) {
      alert('ID peminjaman tidak ditemukan!');
      return;
    }

    const checkedItems = [...document.querySelectorAll('input[name="barang[]"]:checked')].map(el => el.value);

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    checkedItems.forEach(item => formData.append('barang[]', item));

    fetch(`/admin/peminjaman/${window.currentPeminjamanId}/selesai`, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => {
        if (data.status === 'selesai') {
          const aksiCell = document.getElementById(`aksi-${window.currentPeminjamanId}`);
          const statusCell = document.getElementById(`status-${window.currentPeminjamanId}`);
          if (aksiCell) aksiCell.innerHTML = '';
          if (statusCell) statusCell.innerHTML = `<span class="text-green-600 font-semibold text-xs">Selesai</span>`;
          closeChecklistModal();
        } else {
          alert(data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal mengirim data.');
      });
  }
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/pengajuan/table-pengajuan-admin.blade.php ENDPATH**/ ?>