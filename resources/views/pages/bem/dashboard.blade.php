@extends('layouts.sidebar-bem')

@section('title', 'Dashboard Bem')

@section('content')
  {{-- Header komponen reusable --}}
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  {{-- Wrapper putih untuk konten utama dan card, lebar dinamis mengikuti konten --}}
  <div class="inline-block bg-white rounded-md p-6 shadow">
    {{-- Judul Section --}}
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ruangan</h2>

    {{-- Wrapper Card Flex kiri ke kanan, tidak mengikuti lebar grid --}}
    <div class="flex flex-wrap gap-4">
      @foreach($gedungs->where('slug', '!=', 'fasilitas-lainnya') as $gedung)
        <x-card-ruangan
          image="{{ asset('images/' . $gedung->slug . '.png') }}"
          title="{{ ucfirst($gedung->slug) }}"
          desc="{{ $gedung->desc }}"
          kapasitas="{{ $gedung->kapasitas }}"
          waktu="{{ $gedung->jam_operasional }}"
          slug="{{ $gedung->slug }}" {{-- penting! untuk edit tombol dan routing --}}
        />
      @endforeach
    </div>
  </div>
@endsection
