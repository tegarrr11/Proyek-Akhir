<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4 bg-black bg-opacity-30 backdrop-blur-sm">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh] relative" onclick="event.stopPropagation()">
    <button onclick="closeEditModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>

    <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Peminjaman</h2>

    <form id="editPeminjamanForm">
      <input type="hidden" name="id" id="editPeminjamanId">

      <!-- Waktu Kegiatan -->
      <div class="mb-4">
        <label class="block font-semibold text-[#1e2d5e] mb-1">Waktu Kegiatan *</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-gray-600">Tanggal Mulai</label>
            <input type="date" name="tgl_kegiatan" id="editTglKegiatan" class="w-full border rounded px-3 py-2" required>
          </div>
          <div>
            <label class="text-xs text-gray-600">Tanggal Berakhir</label>
            <input type="date" name="tgl_kegiatan_berakhir" id="editTglKegiatanBerakhir" class="w-full border rounded px-3 py-2" required>
          </div>
          <div>
            <label class="text-xs text-gray-600">Jam Mulai</label>
            <input type="time" name="waktu_mulai" id="editWaktuMulai" class="w-full border rounded px-3 py-2" required>
          </div>
          <div>
            <label class="text-xs text-gray-600">Jam Berakhir</label>
            <input type="time" name="waktu_berakhir" id="editWaktuBerakhir" class="w-full border rounded px-3 py-2" required>
          </div>
        </div>
      </div>

      <!-- Dokumen -->
      <div class="mb-4">
        <label class="block font-semibold text-[#1e2d5e] mb-1">Dokumen</label>
        <div id="editDokumenContainer" class="space-y-2 text-sm"></div>
      </div>

      <!-- Perlengkapan -->
      <div class="mb-4">
        <label class="block font-semibold text-[#1e2d5e] mb-1">Perlengkapan</label>
        <table class="w-full border text-sm rounded overflow-hidden">
          <thead class="bg-green-100 text-left">
            <tr>
              <th class="px-1 py-2 border">No.</th>
              <th class="px-3 py-2 border">Nama Barang</th>
              <th class="px-3 py-2 border">Jumlah</th>
              <th class="px-3 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody id="editPerlengkapanTable" class="text-gray-800">
            <tr>
              <td colspan="4" class="text-center text-gray-400 italic py-4">Memuat data...</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tombol -->
      <div class="flex justify-end mt-6 gap-2">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">Tutup</button>
        <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">✔ Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
@push('scripts')
<script>
  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
  }

  // Render dokumen berdasar jenis_kegiatan
  function renderDokumenFields(jenis, existing = {}) {
    const c = document.getElementById('editDokumenContainer');
    const note = `<p class="text-xs text-red-500 italic">* Maks 3MB — Hanya PDF</p>`;

    // optional: link file lama jika tersedia
    const linkProposal = existing.proposal_url
      ? `<p class="text-xs mt-1">File saat ini: <a href="${existing.proposal_url}" target="_blank" class="text-blue-600 underline">Lihat</a></p>` : '';
    const linkUndangan = existing.undangan_pembicara_url
      ? `<p class="text-xs mt-1">File saat ini: <a href="${existing.undangan_pembicara_url}" target="_blank" class="text-blue-600 underline">Lihat</a></p>` : '';

    // Normalisasi
    jenis = (jenis || '').toLowerCase();

    // Default: kalau tidak dikenal, jadikan internal (hanya proposal)
    if (!['internal', 'eksternal'].includes(jenis)) {
      console.warn('jenis_kegiatan tidak dikenali. Default ke internal (proposal saja). Dapat:', jenis);
      jenis = 'internal';
    }

    if (jenis === 'internal') {
      c.innerHTML = `
        <div>
          <label class="block text-sm font-medium">Proposal Kegiatan</label>
          <input type="file" name="proposal" accept="application/pdf"
                 class="block w-full border rounded px-3 py-2 text-sm">
          ${note}
          ${linkProposal}
        </div>
      `;
    } else {
      c.innerHTML = `
        <div>
          <label class="block text-sm font-medium">Proposal Kegiatan</label>
          <input type="file" name="proposal" accept="application/pdf"
                 class="block w-full border rounded px-3 py-2 text-sm">
          ${note}
          ${linkProposal}
        </div>
        <div>
          <label class="block text-sm font-medium">Surat Undangan Pembicara</label>
          <input type="file" name="undangan_pembicara" accept="application/pdf"
                 class="block w-full border rounded px-3 py-2 text-sm">
          ${note}
          ${linkUndangan}
        </div>
      `;
    }
  }

  // Validasi client-side: hanya PDF & <= 3MB
  function validateDokumenFiles(form) {
    const maxSize = 3 * 1024 * 1024; // 3MB
    const files = ['proposal', 'undangan_pembicara']
      .map(n => form.querySelector(`input[name="${n}"]`))
      .filter(Boolean);

    for (const input of files) {
      const f = input.files?.[0];
      if (!f) continue; // tidak wajib upload ulang saat edit
      const isPdf = f.type === 'application/pdf' || /\.pdf$/i.test(f.name);
      if (!isPdf) {
        alert(`File "${f.name}" bukan PDF. Mohon unggah PDF saja.`);
        input.focus();
        return false;
      }
      if (f.size > maxSize) {
        alert(`File "${f.name}" melebihi 3MB. Mohon kompres atau pilih file lain.`);
        input.focus();
        return false;
      }
    }
    return true;
  }

  function showEditModal(data) {
    console.log('DEBUG isi perlengkapan:', data.perlengkapan);
    const el = id => document.getElementById(id);
    el('editModal').classList.remove('hidden');

    // isi waktu
    el('editPeminjamanId').value = data.id;
    el('editTglKegiatan').value = data.tgl_kegiatan;
    el('editTglKegiatanBerakhir').value = data.tgl_kegiatan_berakhir || data.tgl_kegiatan;
    el('editWaktuMulai').value = data.waktu_mulai?.slice(0, 5);
    el('editWaktuBerakhir').value = data.waktu_berakhir?.slice(0, 5);

    // === DOKUMEN ===
    // Ambil jenis_kegiatan dari data peminjaman (ditarik by id)
    // Sesuaikan nama field jika backend Anda menggunakan key berbeda.
    const jenisKegiatan = data.jenis_kegiatan || data.jenis || data.kategori_kegiatan;
    // Opsional: pass url file lama jika ada (ubah key sesuai API Anda)
    const existingDocs = {
      proposal_url: data.proposal_url,
      undangan_pembicara_url: data.undangan_pembicara_url
    };
    renderDokumenFields(jenisKegiatan, existingDocs);

    // Render perlengkapan table
    const table = el('editPerlengkapanTable');
    table.innerHTML = '';
    if (data.perlengkapan?.length) {
      data.perlengkapan.forEach((item, i) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="border px-1 py-1">${i + 1}</td>
          <td class="border px-3 py-1">${item.nama}
            <input type="hidden" name="perlengkapan[${i}][id]" value="${item.id ?? ''}">
          </td>
          <td class="border px-3 py-1">
            <input type="number" name="perlengkapan[${i}][jumlah]" value="${item.jumlah}" min="1" class="border px-2 py-1 rounded w-20">
          </td>
          <td class="border px-3 py-1 text-left text-red-500">
            <button type="button" onclick="this.closest('tr').remove()">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
              </svg>
            </button>
          </td>`;
        table.appendChild(tr);
      });
    } else {
      table.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 italic py-4">Tidak ada perlengkapan</td></tr>`;
    }
  }

  // Submit handler
  document.getElementById('editPeminjamanForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;

    // Validasi PDF & size
    if (!validateDokumenFiles(form)) return;

    const formData = new FormData(form);
    formData.append('_method', 'PATCH');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) return alert('CSRF token tidak ditemukan.');

    fetch(`/mahasiswa/peminjaman/${formData.get('id')}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(res => res.json())
    .then(resp => {
      if (resp.success) {
        alert('✅ Berhasil diperbarui!');
        location.reload();
      } else {
        alert(resp.error || '❌ Gagal menyimpan.');
      }
    })
    .catch(err => {
      console.error(err);
      alert('⚠️ Terjadi kesalahan saat menyimpan.');
    });
  });
</script>
@endpush

@endpush
