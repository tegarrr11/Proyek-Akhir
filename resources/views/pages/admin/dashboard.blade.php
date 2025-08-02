@extends('layouts.sidebar-admin')

@section('title', 'Dashboard Admin')

@section('content')
  {{-- Header --}}
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  {{-- Card Statistik --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 mb-6">
    {{-- Card 1 --}}
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold">Pengajuan Menunggu Verifikasi</h3>
      <p class="text-2xl text-blue-600 font-bold">{{ $jumlahPengajuanAktif }}</p>
    </div>

    {{-- Card 2 --}}
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold">Peminjaman Aktif</h3>
      <p class="text-2xl text-green-600 font-bold">{{ $jumlahPeminjamanAktif }}</p>
    </div>

    {{-- Card 3 --}}
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-sm text-gray-500 font-semibold mb-2">Ruangan Sering Dipinjam</h3>
      <ul class="text-sm text-gray-800 space-y-1">
        @forelse ($ruanganTerbanyak as $item)
          <li class="flex justify-between border-b pb-1">
            <span>{{ $item->nama_gedung }}</span>
            <span class="text-blue-600 font-semibold">{{ $item->total }}x</span>
          </li>
        @empty
          <li class="italic text-gray-400">Tidak ada data</li>
        @endforelse
      </ul>
    </div>
  </div>

  {{-- Kalender --}}
  <div class="px-6">
    @include('components.kalender-mahasiswa', [
      'gedungs' => $gedungs,
      'selectedGedungId' => $selectedGedungId,
      'events' => $events
    ])
  </div>
@endsection
