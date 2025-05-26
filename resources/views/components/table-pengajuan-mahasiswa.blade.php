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
      <th class="px-4 py-2">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>

        {{-- Verifikasi BEM --}}
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>

        {{-- Verifikasi Sarpras --}}
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded {{ $item->verifikasi_sarpras === 'diterima' ? 'bg-green-200 text-green-800' : ($item->verifikasi_sarpras === 'proses' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-600') }}">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>

        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2">{{ ucfirst($item->status_peminjaman ?? '-') }}</td>
        <td class="px-4 py-2">{{ ucfirst($item->status_pengembalian ?? '-') }}</td>

        {{-- Aksi --}}
        <td class="px-4 py-2">
          @if ($item->verifikasi_sarpras === 'diterima')
            @if (is_null($item->status_peminjaman))
              <form method="POST" action="{{ route('mahasiswa.peminjaman.ambil', $item->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-blue-600 text-white px-3 py-1 text-xs rounded hover:bg-blue-700">Ambil</button>
              </form>
            @elseif ($item->status_peminjaman === 'kembalikan' && $item->status_pengembalian === 'proses')
              <form method="POST" action="{{ route('mahasiswa.peminjaman.kembalikan', $item->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-yellow-500 text-white px-3 py-1 text-xs rounded hover:bg-yellow-600">Kembalikan</button>
              </form>
            @else
              <span class="text-gray-400 text-xs italic">Selesai</span>
            @endif
          @else
            <span class="text-gray-400 text-xs italic">Menunggu</span>
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>
