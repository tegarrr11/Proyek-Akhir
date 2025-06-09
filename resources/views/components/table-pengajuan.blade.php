<table class="w-full text-sm">
  <thead class="bg-gray-100">
    <tr>
      <th class="px-4 py-2">No.</th>
      <th class="px-4 py-2">Judul Kegiatan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ $item->created_at->format('d/m/Y') }}</td>

        {{-- Verifikasi BEM --}}
        <td class="px-4 py-2">
          @php $vb = strtolower($item->verifikasi_bem); @endphp
          @if ($vb === 'diterima')
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs font-medium">Diterima</span>
          @elseif ($vb === 'ditolak')
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-xs font-medium">Ditolak</span>
          @else
            <span class="bg-gray-200 px-3 py-1 rounded text-xs">Diajukan</span>
          @endif
        </td>

        {{-- Verifikasi Sarpras --}}
        <td class="px-4 py-2">
          @php $vs = strtolower($item->verifikasi_sarpras); @endphp
          @if ($vs === 'diterima')
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs font-medium">Diterima</span>
          @elseif ($vs === 'ditolak')
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-xs font-medium">Ditolak</span>
          @else
            <span class="bg-gray-200 px-3 py-1 rounded text-xs">Diajukan</span>
          @endif
        </td>

        <td class="px-4 py-2">{{ $item->organisasi }}</td>

        {{-- Aksi --}}
        <td class="px-4 py-2 text-center">
          <button onclick="showDetail({{ $item->id }})" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600">
            Detail
          </button>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

{{-- Modal --}}
@include('components.card-detail-peminjaman')

<script>
  function showDetail(id) {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('hidden');

    // Reset
    ['judulKegiatan', 'waktuKegiatan', 'aktivitas', 'organisasi', 'penanggungJawab', 'keterangan', 'ruangan'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.innerText = '';
    });

    const link = document.getElementById('linkDokumen');
    if (link) link.href = '#';

    const perlengkapan = document.getElementById('perlengkapan');
    if (perlengkapan) perlengkapan.innerHTML = '';

    fetch(`/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        if (document.getElementById('judulKegiatan')) document.getElementById('judulKegiatan').innerText = data.judul_kegiatan;
        if (document.getElementById('waktuKegiatan')) document.getElementById('waktuKegiatan').innerText = `${data.tgl_kegiatan} ${data.waktu_mulai} - ${data.waktu_berakhir}`;
        if (document.getElementById('aktivitas')) document.getElementById('aktivitas').innerText = data.aktivitas;
        if (document.getElementById('organisasi')) document.getElementById('organisasi').innerText = data.organisasi;
        if (document.getElementById('penanggungJawab')) document.getElementById('penanggungJawab').innerText = data.penanggung_jawab;
        if (document.getElementById('keterangan')) document.getElementById('keterangan').innerText = data.deskripsi_kegiatan;
        if (document.getElementById('ruangan')) document.getElementById('ruangan').innerText = data.nama_ruangan;
        if (link && data.dokumen) link.href = `/storage/${data.dokumen}`;

        if (Array.isArray(data.perlengkapan)) {
          data.perlengkapan.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.nama} - ${item.jumlah}`;
            perlengkapan.appendChild(li);
          });
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
  }

  
</script>

