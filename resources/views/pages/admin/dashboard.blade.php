@extends('layouts.sidebar-admin')

@section('title', 'Dashboard Admin')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

{{-- Header --}}
<x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

{{-- Card Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 mb-6">
    {{-- Card 1: Pengajuan Menunggu Verifikasi --}}
    <div class="bg-yellow-100 shadow rounded-lg p-5 flex flex-col items-start text-left">
        <div class="bg-yellow-400 p-3 rounded-full mb-3 flex items-center justify-center">
            <i class="fas fa-file-alt text-white text-2xl"></i>
        </div>
        <h3 class="text-sm text-gray-700 font-semibold mb-1">Pengajuan Menunggu Verifikasi</h3>
        <p class="text-3xl text-gray-900 font-bold">{{ $jumlahPengajuanAktif }}</p>
    </div>

    {{-- Card 2: Peminjaman Aktif --}}
    <div class="bg-green-100 shadow rounded-lg p-5 flex flex-col items-start text-left">
        <div class="bg-green-500 p-3 rounded-full mb-3 flex items-center justify-center">
            <i class="fas fa-bell text-white text-2xl"></i>
        </div>
        <h3 class="text-sm text-gray-700 font-semibold mb-1">Peminjaman Aktif</h3>
        <p class="text-3xl text-gray-900 font-bold">{{ $jumlahPeminjamanAktif }}</p>
    </div>

    {{-- Card 3: Ruangan Sering Dipinjam --}}
    <div class="bg-blue-100 shadow rounded-lg p-5 flex flex-col items-start text-left">
        <div class="bg-blue-500 p-3 rounded-full mb-3 flex items-center justify-center">
            <i class="fas fa-chart-line text-white text-2xl"></i>
        </div>
        <h3 class="text-sm text-gray-700 font-semibold mb-2">Ruangan Sering Dipinjam (Bulan ini)</h3>
        <ul class="text-sm text-gray-800 space-y-1 w-full">
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