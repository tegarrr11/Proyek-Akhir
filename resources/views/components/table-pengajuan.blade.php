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
          @php
            $role = strtolower(auth()->user()->role);
          @endphp

          @if ($role === 'admin')
            @if ($vb === 'diterima' && $vs === 'diajukan')
              <form action="{{ route('admin.peminjaman.approve', $item->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">
                  Approve
                </button>
              </form>
            @elseif ($vb !== 'diterima')
              <span class="bg-gray-100 text-gray-400 text-xs px-3 py-1 rounded italic">Menunggu BEM</span>
            @else
              <span class="bg-gray-100 text-gray-400 text-xs px-3 py-1 rounded italic">Sudah Diproses</span>
            @endif

          @elseif ($role === 'bem')
            @if ($vb === 'diajukan')
              <form action="{{ route('bem.peminjaman.approve', $item->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">
                  Approve
                </button>
              </form>
            @else
              <span class="bg-gray-100 text-gray-400 text-xs px-3 py-1 rounded italic">Sudah Diverifikasi</span>
            @endif

          @else
            <span class="text-gray-400 text-sm italic">Menunggu</span>
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

@include('components.card-detail-peminjaman')

<script>
  function showDetail(id) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');
    content.innerHTML = 'Memuat data...';

    fetch(`/peminjaman/${id}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Gagal fetch data');
        }
        return response.json();
      })
      .then(data => {
        content.innerHTML = `
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <p><strong>Judul Kegiatan:</strong> ${data.judul_kegiatan}</p>
              <p><strong>Waktu Kegiatan:</strong> ${data.tgl_kegiatan} ${data.waktu_mulai} - ${data.waktu_berakhir}</p>
              <p><strong>Aktivitas:</strong> ${data.aktivitas}</p>
              <p><strong>Organisasi:</strong> ${data.organisasi}</p>
              <p><strong>Penanggung Jawab:</strong> ${data.penanggung_jawab}</p>
              <p><strong>Keterangan:</strong> ${data.deskripsi_kegiatan}</p>
              <p><strong>Dokumen:</strong> <a href="/storage/${data.dokumen}" target="_blank" class="text-blue-600 underline">Download</a></p>
            </div>
            <div>
              <p><strong>Ruangan:</strong> ${data.nama_ruangan}</p>
              <p><strong>Perlengkapan:</strong></p>
              <ul class="list-disc list-inside">
                ${data.perlengkapan.map(p => `<li>${p.nama} - ${p.jumlah}</li>`).join('')}
              </ul>
            </div>
          </div>
        `;
      })
      .catch(error => {
        content.innerHTML = `<p class="text-red-500">Gagal memuat data. (${error.message})</p>`;
        console.error(error);
      });
  }

  function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
  }
</script>
