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
      <th class="px-4 py-2"></th> {{-- kolom aksi tanpa judul --}}
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      @if($item->status_pengembalian === 'selesai')
        @continue
      @endif

      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>

        {{-- Verifikasi BEM --}}
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-500 text-white text-xs px-3 py-1 rounded' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>

        {{-- Verifikasi Sarpras --}}
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-500 text-white text-xs px-3 py-1 rounded
            @elseif($item->verifikasi_sarpras === 'proses')
              bg-yellow-100 text-yellow-800
            @else
              bg-gray-200 text-gray-600
            @endif">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>

        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        {{-- Status Peminjaman --}}
        <td class="px-4 py-2">
          @if ($item->verifikasi_sarpras === 'diterima')
            @if ($item->status_peminjaman === 'kembalikan' && $item->status_pengembalian === 'proses')
              <form method="POST" action="{{ route('mahasiswa.peminjaman.kembalikan', $item->id) }}" onsubmit="return confirm('Yakin ingin mengembalikan barang ini?')">
                @csrf
                @method('PATCH')
                <button class="bg-yellow-500 text-white px-3 py-1 text-xs rounded hover:bg-yellow-600 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Kembalikan
                </button>
              </form>
            @elseif (is_null($item->status_peminjaman))
              <form method="POST" action="{{ route('mahasiswa.peminjaman.ambil', $item->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-blue-600 text-white px-3 py-1 text-xs rounded hover:bg-blue-700">Ambil</button>
              </form>
            @else
              {{ ucfirst($item->status_peminjaman) }}
            @endif
          @else
            <span class="text-gray-400 text-xs italic">-</span>
          @endif
        </td>

        {{-- Status Pengembalian --}}
        <td class="px-4 py-2">
          @if ($item->status_peminjaman === 'kembalikan')
            <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">Belum</span>
          @else
            <span class="text-gray-400 text-xs italic">-</span>
          @endif
        </td>
        {{-- Status Pengembalian (sembunyi awal) --}}
        <td class="px-4 py-2 hidden status-kembali-col">{{ ucfirst($item->status_pengembalian ?? '-') }}</td>

        {{-- Aksi Icon Detail --}}
        <td class="px-4 py-2">
          <div class="flex items-center gap-2">

            {{-- Icon Detail --}}
            <button onclick="showDetail({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

@push('scripts')
<script>
  function tampilkanKolomKembali(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('tr');
    row.querySelector('.status-kembali-col').classList.remove('hidden');
    form.submit();
  }

  function showDetail(id) {
    fetch(`/mahasiswa/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        showDetailModal({
          organisasi: data.organisasi,
          tanggal: data.tgl_kegiatan,
          kegiatan: data.judul_kegiatan,
          jam: `${data.waktu_mulai} - ${data.waktu_berakhir}`,
          jenis: data.keterangan ?? '-'
        });
      });
  }
</script>
@endpush
