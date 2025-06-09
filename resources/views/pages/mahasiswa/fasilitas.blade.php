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
            'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'title' => 'Cek Ketersediaan Jadwal Ruangan',
            'desc' => 'Mahasiswa mengecek jadwal ruangan melalui sistem untuk memastikan waktu dan tempat yang diinginkan tersedia.'
          ],
          [
            'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>',
            'title' => 'Pengisian Form Peminjaman (Tahap 1 dan 2)',
            'desc' => '<ul class="list-disc ml-5"><li>Pengisian fasilitas yang akan digunakan, mencakup ruangan dan barang.</li><li>Pengisian detail kegiatan seperti nama acara, penanggung jawab, tanggal, waktu, dan keperluan kegiatan.</li></ul>'
          ],
          [
            'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/>',
            'title' => 'Verifikasi oleh BEM',
            'desc' => '<ul class="list-disc ml-5"><li>BEM dapat menggunakan fitur diskusi untuk meminta klarifikasi kegiatan atau memberikan saran jika terjadi bentrok jadwal.</li><li>Jika disetujui, status akan berubah menjadi Diterima.</li></ul>'
          ],
          [
            'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="m9 15 2 2 4-4"/>',
            'title' => 'Verifikasi oleh Sarpras (Sarana dan Prasarana)',
            'desc' => '<ul class="list-disc ml-5"><li>Fitur diskusi juga tersedia di tahap ini untuk koordinasi lebih lanjut.</li><li>Jika disetujui, status akan berubah menjadi Diterima dan peminjaman dinyatakan berhasil.</li></ul>'
          ],
          [
            'icon' => '<path d="M22 18H6a2 2 0 0 1-2-2V7a2 2 0 0 0-2-2"/><path d="M17 14V4a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10"/><rect width="13" height="8" x="8" y="6" rx="1"/><circle cx="18" cy="20" r="2"/><circle cx="9" cy="20" r="2"/>',
            'title' => 'Pengambilan dan Pengembalian Barang',
            'desc' => '<ul class="list-disc ml-5"><li>Barang dapat diambil H−3 sebelum hari kegiatan.</li><li>Barang harus dikembalikan H+2 setelah hari kegiatan.</li></ul>'
          ],
        ];
      @endphp

      @foreach ($steps as $step)
        <div class="flex items-start bg-[#e0f7fa] p-4 rounded-md gap-4 shadow-sm">
          <div class="flex-shrink-0 bg-[#003366] p-2 rounded-full shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              {!! $step['icon'] !!}
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
