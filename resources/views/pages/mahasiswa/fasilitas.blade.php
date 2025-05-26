@extends('layouts.sidebar-mahasiswa')

@section('title', 'Fasilitas')

@section('content')
  <x-header title="Fasilitas" breadcrumb="Fasilitas > Prosedur Peminjaman" />
  <div class="bg-white p-6 rounded-md shadow-md">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-xl font-semibold text-[#003366]">Prosedur Peminjaman</h1>
    </div>
    <a href="{{ route('peminjaman.create') }}" 
    class="bg-[#003366] hover:bg-[#002952] text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
    Ajukan Peminjaman
    </a>
  </div>

  <div class="space-y-4">
    @php
      $steps = [
        [
          'icon' => 'M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z',
          'title' => 'Cek Ketersediaan Jadwal Ruangan',
          'desc' => 'Mahasiswa mengecek jadwal ruangan melalui sistem untuk memastikan waktu dan tempat yang diinginkan tersedia.'
        ],
        [
          'icon' => 'M9 12h6m2 6H7m2-12h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z',
          'title' => 'Pengisian Form Peminjaman (Tahap 1 dan 2)',
          'desc' => '<ul class="list-disc ml-5"><li>Pengisian fasilitas yang akan digunakan, mencakup ruangan dan barang.</li><li>Pengisian detail kegiatan seperti nama acara, penanggung jawab, tanggal, waktu, dan keperluan kegiatan.</li></ul>'
        ],
        [
          'icon' => 'M8 16h8M8 12h8m-6 8h6M3 5h12a2 2 0 012 2v11a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2z',
          'title' => 'Verifikasi oleh BEM',
          'desc' => '<ul class="list-disc ml-5"><li>BEM dapat menggunakan fitur diskusi untuk meminta klarifikasi kegiatan atau memberikan saran jika terjadi bentrok jadwal.</li><li>Jika disetujui, status akan berubah menjadi Diterima.</li></ul>'
        ],
        [
          'icon' => 'M8 16h8M8 12h8m-6 8h6M3 5h12a2 2 0 012 2v11a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2z',
          'title' => 'Verifikasi oleh Sarpras (Sarana dan Prasarana)',
          'desc' => '<ul class="list-disc ml-5"><li>Fitur diskusi juga tersedia di tahap ini untuk koordinasi lebih lanjut.</li><li>Jika disetujui, status akan berubah menjadi Diterima dan peminjaman dinyatakan berhasil.</li></ul>'
        ],
        [
          'icon' => 'M8 7V3m8 4V3m-9 9h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
          'title' => 'Pengambilan dan Pengembalian Barang',
          'desc' => '<ul class="list-disc ml-5"><li>Barang dapat diambil H−3 sebelum hari kegiatan.</li><li>Barang harus dikembalikan H+2 setelah hari kegiatan.</li></ul>'
        ],
      ];
    @endphp

    @foreach ($steps as $step)
    <div class="flex items-start bg-[#e0f7fa] p-4 rounded-md gap-4 shadow-sm">
      <div class="flex-shrink-0 bg-white p-2 rounded-full shadow">
        <svg class="w-6 h-6 text-[#003366]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
        </svg>
      </div>
      <div>
        <h2 class="font-semibold text-sm text-[#003366]">{{ $step['title'] }}</h2>
        <div class="text-sm text-gray-700">{!! $step['desc'] !!}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
