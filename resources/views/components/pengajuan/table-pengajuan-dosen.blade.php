<x-table-wrapper>
    <table class="min-w-full bg-white border rounded-lg">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th class="px-4 py-2 text-left">No</th>
            <th class="px-4 py-2 text-left">Judul Kegiatan</th>
            <th class="px-4 py-2 text-left">Rungan</th> {{-- Tambahan --}}
            <th class="px-4 py-2 text-left">Tanggal</th>
            <th class="px-4 py-2 text-left">Waktu</th>
            <th class="px-4 py-2 text-left">Penanggung Jawab</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $index => $item)
        <tr>
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
            <td class="px-4 py-2">{{ $item->gedung->nama ?? '-' }}</td> {{-- Tambahan --}}
            <td class="px-4 py-2">{{ $item->tgl_kegiatan }}</td>
            <td class="px-4 py-2">{{ $item->waktu_mulai }} - {{ $item->waktu_berakhir }}</td>
            <td class="px-4 py-2">{{ $item->penanggung_jawab ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada pengajuan.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</x-table-wrapper>
