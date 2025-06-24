<table class="w-full text-sm">
  <thead class="bg-gray-100">
    <tr class="font-semibold">
      <th class="px-4 py-2 text-left">No.</th>
      <th class="px-4 py-2">Judul Kegiatan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2">Detail</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ $item->created_at->format('d/m/Y') }}</td>
        <td class="px-4 py-2">
          <span class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">Diterima</span>
        </td>
        <td class="px-4 py-2">
          @if ($item->verifikasi_sarpras === 'diterima')
            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">Diterima</span>
          @elseif ($item->verifikasi_sarpras === 'ditolak')
            <span class="bg-green-100 text-red-800 text-xs font-semibold px-3 py-1 rounded hover:bg-red-200 transition">Ditolak</span>
          @else
            <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded hover:bg-yello-600">Proses</span>
          @endif
        </td>
        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2">
          <button onclick="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </button>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada riwayat.</td></tr>
    @endforelse
  </tbody>
</table>

<!-- Removed local showDetail and closeModal JS to avoid conflict with global -->



