@php
  $perPage = 10;
  $currentPage = request()->get('page', 1);
  $offset = ($currentPage - 1) * $perPage;

  // Filter search
  $filtered = $items->filter(function ($item) {
    $search = strtolower(request()->get('search', ''));
    return str_contains(strtolower($item->judul_kegiatan), $search);
  });

  // Filter gedung
  if (request('gedung_id')) {
    $filtered = $filtered->where('gedung_id', request('gedung_id'));
  }

  // Filter bulan (berdasarkan created_at)
  if (request('bulan')) {
    $filtered = $filtered->filter(function ($item) {
        return $item->created_at && $item->created_at->format('Y-m') === request('bulan');
    });
  }

  $paginatedItems = $filtered->slice($offset, $perPage)->values();
  $totalPages = ceil($filtered->count() / $perPage);
@endphp

<x-table-wrapper>
  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr class="font-semibold text-left">
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
      @forelse($paginatedItems as $i => $item)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $offset + $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full font-medium
            @if($item->verifikasi_bem === 'diterima')
              bg-green-100 text-green-600
            @elseif($item->verifikasi_bem === 'ditolak')
              bg-red-100 text-red-600
            @else
              bg-yellow-100 text-white
            @endif">
            {{ ucfirst($item->verifikasi_bem ?? '-') }}
          </span>
        </td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full font-medium
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-100 text-green-600
            @elseif($item->verifikasi_sarpras === 'ditolak')
              bg-red-100 text-red-600
            @else
              bg-yellow-500 text-white
            @endif">
            {{ ucfirst($item->verifikasi_sarpras ?? '-') }}
          </span>
        </td>
        <td class="px-4 py-2">{{ $item->organisasi ?? '-' }}</td>
        <td class="px-4 py-2">
          <button
            onclick="showDetail({{ $item->id }})"
            class="text-blue-600 hover:text-blue-800 text-sm"
            title="Lihat Detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>            </button>
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada riwayat.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- PAGINATION --}}
  <div class="flex justify-start mt-4 pl-4 pb-4 gap-1">
    @for($page = 1; $page <= $totalPages; $page++)
      <a href="{{ request()->fullUrlWithQuery(['page' => $page, 'tab' => 'riwayat']) }}"
        class="px-3 py-1 rounded-md border text-sm shadow-sm transition
                {{ $page == $currentPage
                    ? 'bg-sky-900 text-white '
                    : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        {{ $page }}
      </a>
    @endfor
  </div>
</x-table-wrapper>
