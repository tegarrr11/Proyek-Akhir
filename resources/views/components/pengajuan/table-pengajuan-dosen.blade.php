<x-table-wrapper>
    <table class="min-w-full bg-white border rounded-lg">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-2">Judul Kegiatan</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Waktu</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
                <td class="px-4 py-2">{{ $item->tgl_kegiatan }}</td>
                <td class="px-4 py-2">{{ $item->waktu_mulai }} - {{ $item->waktu_berakhir }}</td>
                <td class="px-4 py-2">
                    @if($item->verifikasi_sarpras === 'diterima')
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Diterima</span>
                    @elseif($item->verifikasi_sarpras === 'ditangguhkan')
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Ditangguhkan</span>
                    @else
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Proses</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-table-wrapper>