@props([
  'image',
  'title',
  'desc',
  'kapasitas' => '-',
  'waktu' => '-',
  'slug'
])

@php
  $link = match(strtolower($slug)) {
    'auditorium' => route('mahasiswa.auditorium'),
    'gsg' => route('mahasiswa.gsg'),
    'gor' => route('mahasiswa.gor'),
    default => '#'
  };
@endphp

<div class="relative w-[270px]">
  <a href="{{ $link }}" class="bg-white rounded-lg overflow-hidden shadow border hover:shadow-lg transition block">
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-32 object-cover">
    <div class="p-3 space-y-1.5">
      <h3 class="text-sm font-bold text-gray-800">{{ $title }}</h3>
      <p class="text-xs text-gray-500">{{ $desc }}</p>

      <div class="flex items-center text-xs text-gray-600 gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75M12 14v6" />
        </svg>
        <span>{{ $kapasitas }} orang</span>
      </div>

      <div class="flex items-center text-xs text-gray-600 gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 4a8 8 0 100 16 8 8 0 000-16z" />
        </svg>
        <span>{{ $waktu }}</span>
      </div>
    </div>

    <div class="px-4 pb-4 flex justify-end">
      <button class="text-[#003366] hover:text-blue-600">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M12.293 9.293a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L13 11.414V17a1 1 0 11-2 0v-5.586l-3.293 3.293a1 1 0 01-1.414-1.414l4-4z" />
        </svg>
      </button>
    </div>
  </a>

  {{-- Tombol edit untuk admin --}}
  @if(auth()->user()->role === 'admin')
    <div class="absolute top-2 right-2 z-10">
      <button onclick="editRuangan('{{ $slug }}')" 
              class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
        </svg>
      </button>
    </div>
  @endif
</div>
