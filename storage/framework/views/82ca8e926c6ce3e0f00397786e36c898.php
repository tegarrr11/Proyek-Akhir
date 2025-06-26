<div id="detailModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4">
  <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(2px);pointer-events:none;"></div>

  <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]" style="pointer-events:auto;z-index:2;" onclick="event.stopPropagation()">
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>

    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Peminjaman</h2>

    <div class="flex flex-col md:flex-row gap-6">
      <!-- Kolom Kiri -->
      <div class="flex-1 space-y-2 text-sm text-gray-800">
        <div class="flex justify-between gap-4">
          <div>
            <p class="font-semibold text-[#1e2d5e]">Judul Kegiatan</p>
            <p id="judulKegiatan">-</p>
          </div>
          <div>
            <p class="font-semibold text-[#1e2d5e]">Waktu Kegiatan</p>
            <p><span id="tglKegiatan">-</span> &nbsp; <span id="jamKegiatan">-</span></p>
          </div>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Aktivitas</p>
          <p id="aktivitas">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Penanggungjawab Kegiatan</p>
          <p id="penanggungJawab">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Keterangan</p>
          <p id="keterangan">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Ruangan</p>
          <p id="ruangan">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Perlengkapan</p>
          <ul id="perlengkapan" class="list-disc list-inside text-gray-800 space-y-0.5">
            <li class="italic text-gray-400">Tidak ada perlengkapan</li>
          </ul>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Dokumen</p>
          <a id="linkDokumen" href="#" class="text-blue-600 underline text-sm hidden">Lihat Proposal</a>
          <span id="dokumenNotFound" class="text-gray-400 italic">Tidak ada dokumen</span>
        </div>
      </div>

      <!-- Kolom Kanan (Diskusi) -->
      <div class="w-full md:w-1/3 border border-gray-200 rounded-lg p-4 flex flex-col">
        <p class="font-semibold text-[#1e2d5e] mb-1">Diskusi</p>
        <div id="diskusiArea" class="text-sm text-gray-400 italic flex-1">belum ada diskusi</div>
        <div class="mt-4">
          <input class="inputDiskusi w-full border rounded px-3 py-2 text-sm mb-2" id="inputDiskusi" type="text" placeholder="Ketikkan di sini">
          <button class="btnKirimDiskusi bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded w-full" id="btnKirimDiskusi">Kirim</button>
        </div>
      </div>
    </div>

    <div class="mt-6 text-right">
      <button onclick="closeModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">Tutup</button>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  window.currentPeminjamanId = null;

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }

  function showDetail(id) {
    currentPeminjamanId = id;
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);
        const formatTanggal = tgl => new Date(tgl).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        const formatJam = jam => jam?.slice(0, 5) || '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

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

        if (data.link_dokumen === 'ada') {
          const prefix = window.location.pathname.split('/')[1];
          const base = ['admin', 'bem', 'mahasiswa', 'dosen', 'staff'].includes(prefix) ? `/${prefix}` : '';
          const url = `${base}/peminjaman/download-proposal/${data.id}`;
          el('linkDokumen').href = url;
          el('linkDokumen').classList.remove('hidden');
          el('dokumenNotFound').classList.add('hidden');
        } else {
          el('linkDokumen').classList.add('hidden');
          el('dokumenNotFound').classList.remove('hidden');
        }

        // Diskusi
        let html = 'belum ada diskusi';
        let adaAdminBem = false;
        if (data.diskusi?.length > 0) {
          html = '';
          data.diskusi.forEach(d => {
            html += `<div class="mb-1"><span class="font-semibold text-xs text-blue-700">${d.role}:</span> ${d.pesan}</div>`;
            if (['admin', 'bem'].includes((d.role || '').toLowerCase())) adaAdminBem = true;
          });
        }
        el('diskusiArea').innerHTML = html;

        const userRole = "<?php echo e(auth()->user()->role); ?>";
        const enableDiskusi = userRole !== 'dosen' && (userRole !== 'mahasiswa' || adaAdminBem);

        const input = el('inputDiskusi');
        const btn = el('btnKirimDiskusi');
        input.disabled = !enableDiskusi;
        btn.disabled = !enableDiskusi;

        btn.classList.toggle('bg-gray-300', !enableDiskusi);
        btn.classList.toggle('cursor-not-allowed', !enableDiskusi);
        btn.classList.toggle('bg-blue-600', enableDiskusi);
        btn.classList.toggle('hover:bg-blue-700', enableDiskusi);

        input.value = '';
        document.getElementById('detailModal').classList.remove('hidden');
        bindDiskusiHandler();
      });
  }

  function bindDiskusiHandler() {
    const btn = document.getElementById('btnKirimDiskusi');
    const input = document.getElementById('inputDiskusi');
    if (!btn || !input) return;

    btn.onclick = () => {
      const pesan = input.value.trim();
      if (!pesan || !currentPeminjamanId) return;
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      btn.disabled = true;
      fetch('/diskusi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
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
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/modal-detail-peminjaman.blade.php ENDPATH**/ ?>