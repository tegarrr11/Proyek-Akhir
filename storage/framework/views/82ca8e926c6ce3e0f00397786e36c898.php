<div id="detailModal" class="fixed inset-0 z-[9999] top-0 left-0 hidden flex items-center justify-center px-4">
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
          <p class="font-semibold text-[#1e2d5e]">Organisasi</p>
          <p id="organisasi">-</p>
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
          <div class="flex flex-col gap-1">
          <a id="linkDokumen" href="#" class="text-blue-600 underline text-sm hidden" target="_blank" rel="noopener noreferrer">
            Lihat Proposal
          </a>
          <a id="linkUndangan" href="#" class="text-blue-600 underline text-sm hidden" target="_blank" rel="noopener noreferrer">
            Lihat Undangan
          </a>
          </div>
          <span id="dokumenNotFound" class="text-gray-400 italic">Tidak ada dokumen</span>
        </div>
      </div>

      <!-- Kolom Kanan (Diskusi) -->
      <div class="w-full md:w-1/3 border border-gray-200 rounded-lg p-4 flex flex-col">
        <p class="font-semibold text-[#1e2d5e] mb-1">Diskusi</p>
        <div id="diskusiArea" class="text-sm text-gray-400 flex-1">belum ada diskusi</div>
        <div class="mt-4">
          <input class="inputDiskusi w-full border rounded px-3 py-2 text-sm mb-2" id="inputDiskusi" type="text" placeholder="Ketikkan di sini">
          <button class="btnKirimDiskusi bg-sky-900 text-white text-sm px-4 py-2 rounded w-full" id="btnKirimDiskusi">Kirim</button>
        </div>
      </div>
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
    console.log('📌 showDetail dipanggil dengan id:', id);

    fetch(`/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        console.log('✅ proposal:', data.proposal);
        console.log('✅ undangan_pembicara:', data.undangan_pembicara);

        const el = id => document.getElementById(id);
        const formatTanggal = tgl => new Date(tgl).toLocaleDateString('id-ID', {
          day: '2-digit', month: 'long', year: 'numeric'
        });
        const formatJam = jam => jam?.slice(0, 5) || '-';

        // -- Informasi Kegiatan
        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('organisasi').textContent = data.organisasi || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

        // -- Perlengkapan
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

        // -- Dokumen Proposal
        if (data.proposal) {
          const dokumen = el('linkDokumen');
          dokumen.href = `/storage/${data.proposal}`;
          dokumen.target = '_blank';
          dokumen.rel = 'noopener noreferrer';
          console.log("📂 proposal:", data.proposal);
          console.log("🔗 dibuat link:", `/storage/${data.proposal}`);
          console.log("🧩 elemen ada:", document.getElementById('linkDokumen'));
          dokumen.classList.remove('hidden');
        } else {
          el('linkDokumen').classList.add('hidden');
        }

        // -- Dokumen Undangan
        if (data.undangan_pembicara) {
          const undangan = el('linkUndangan');
          undangan.href = `/storage/${data.undangan_pembicara}`;
          undangan.target = '_blank';
          undangan.rel = 'noopener noreferrer';
          undangan.classList.remove('hidden');
        } else {
          el('linkUndangan').classList.add('hidden');
        }

        // -- Jika dua-duanya tidak ada
        if (!data.proposal && !data.undangan_pembicara) {
          el('dokumenNotFound').classList.remove('hidden');
        } else {
          el('dokumenNotFound').classList.add('hidden');
        }

        // -- Diskusi
        let html = 'belum ada diskusi';
        let adaAdminBem = false;
        if (data.diskusi?.length > 0) {
          html = '';
          data.diskusi.forEach(d => {
            html += `<div class="mb-1"><span class="font-semibold text-xs text-blue-700">${d.role}:</span> ${d.pesan}</div>`;
            if (['admin', 'bem'].includes((d.role || '').toLowerCase())) {
              adaAdminBem = true;
            }
          });
        }
        el('diskusiArea').innerHTML = html;

        // -- Aktivasi Input Chat
        const userRole = <?php echo json_encode(auth()->user()->role, 15, 512) ?>;
        const enableDiskusi = userRole !== 'dosen' &&
          (userRole === 'admin' || userRole === 'bem' || (userRole === 'mahasiswa' && adaAdminBem));

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

  window.showDetail = showDetail;

  function bindDiskusiHandler() {
    console.log('✅ bindDiskusiHandler dipanggil');

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
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
          peminjaman_id: currentPeminjamanId,
          pesan
        })
      })
        .then(res => res.json())
        .then(resp => {
          if (resp.success) {
            showDetail(currentPeminjamanId); // Refresh diskusi
          } else {
            alert(resp.error || 'Gagal mengirim pesan.');
          }
        })
        .catch(() => alert('Gagal mengirim pesan.'));
    };
  }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/modal-detail-peminjaman.blade.php ENDPATH**/ ?>