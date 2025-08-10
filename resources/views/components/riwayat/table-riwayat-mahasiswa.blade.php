@php
  $perPage = 10;
  $currentPage = request()->get('page', 1);
  $offset = ($currentPage - 1) * $perPage;

  $filtered = $items->filter(function ($item) {
    $search = strtolower(request()->get('search', ''));
    return str_contains(strtolower($item->judul_kegiatan), $search);
  });

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
        <td class="px-4 py-2">{{ $item->created_at->format('d/m/Y') }}</td>
        <td class="px-4 py-2">
          <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full">Diterima</span>
        </td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-100 text-green-600 font-medium
            @elseif(in_array($item->verifikasi_sarpras, ['proses','diajukan']))
              bg-gray-200 text-gray-700 font-medium
            @else
              bg-gray-200 text-gray-600 font-medium
            @endif">
            {{ in_array($item->verifikasi_sarpras, ['diajukan', null, '']) ? '-' : ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>
        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2">
          <button onclick="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
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
                    ? 'bg-sky-900 text-white'
                    : 'bg-white text-gray-700 hover:bg-gray-100' }}">
        {{ $page }}
      </a>
    @endfor
  </div>
</x-table-wrapper>