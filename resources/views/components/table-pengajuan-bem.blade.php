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
    @forelse($items as $i => $pengajuan)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $pengajuan->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ $pengajuan->tgl_kegiatan }}</td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded {{ $pengajuan->verifikasi_bem === 'diterima' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($pengajuan->verifikasi_bem) }}
          </span>
        </td>

        <td class="px-4 py-2 text-gray-500 text-xs">-</td>
        <td class="px-4 py-2">{{ $pengajuan->organisasi }}</td>

        <td class="px-4 py-2">
          <div class="flex items-center gap-2 justify-center">
            <form method="POST" action="{{ route('bem.peminjaman.verifikasi', $pengajuan->id) }}">
              @csrf
              <input type="hidden" name="verifikasi_bem" value="diterima">
              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded">
                Terima
              </button>
            </form>

            <button onclick="showDetail({{ $pengajuan->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-[999] hidden bg-black/40 backdrop-blur-sm flex items-center justify-center px-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-screen mt-8 sm:mt-16" onclick="event.stopPropagation()">
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Peminjaman</h2>

    <div class="flex flex-col md:flex-row gap-6">
      <div class="flex-1 space-y-2 text-sm text-gray-800">
        <div class="flex justify-between gap-4">
          <div>
            <p class="font-semibold text-[#1e2d5e]">Judul Kegiatan</p>
            <p id="judulKegiatan">-</p>
          </div>
          <div>
            <p class="font-semibold text-[#1e2d5e]">Waktu Kegiatan</p>
            <p id="waktuKegiatan">-</p>
          </div>
        </div>
        <div>
          <p class="font-semibold text-[#1e2d5e]">Aktivitas</p>
          <p id="aktivitas">-</p>
        </div>
        <div>
          <p class="font-semibold text-[#1e2d5e]">Penanggungjawab</p>
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
      </div>

      <div class="w-full md:w-1/3 border border-gray-200 rounded-lg p-4 flex flex-col">
        <p class="font-semibold text-[#1e2d5e] mb-1">Diskusi</p>
        <div id="diskusiArea" class="text-sm text-gray-400 italic flex-1">belum ada diskusi</div>
        <div class="mt-4">
          <input type="text" placeholder="Ketikkan di sini" class="w-full border rounded px-3 py-2 text-sm mb-2" disabled>
          <button class="bg-gray-300 text-white text-sm px-4 py-2 rounded cursor-not-allowed w-full" disabled>Kirim</button>
        </div>
      </div>
    </div>

    <div class="mt-6 text-right">
      <button onclick="closeModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">Tutup</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function showDetail(id) {
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);

        // Format tanggal Indonesia
        const formatTanggal = (tanggalStr) => {
          const date = new Date(tanggalStr);
          return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
          });
        };

        // Format jam HH:mm
        const formatJam = (waktuStr) => {
          return waktuStr ? waktuStr.slice(0, 5) : '-';
        };

        // Set isi modal
        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('waktuKegiatan').textContent = `${formatTanggal(data.tgl_kegiatan)} ${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

        // Perlengkapan
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
</script>
@endpush
