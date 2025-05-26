@extends('layouts.sidebar-admin')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

  @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-md shadow p-6">
    {{-- Tabs --}}
    <div class="flex items-center justify-between mb-4">
      <div class="flex gap-4 border-b">
        <button onclick="showTab('pengajuan')" id="tabPengajuan"
          class="border-b-2 border-[#003366] font-semibold text-[#003366] px-2 pb-1">Pengajuan</button>
        <button onclick="showTab('riwayat')" id="tabRiwayat"
          class="text-gray-500 px-2 pb-1 hover:text-[#003366]">Riwayat</button>
      </div>
      <div class="flex gap-2">
        <input type="text" placeholder="Cari........"
          class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-[#003366]">
        <button class="border px-3 py-1 rounded text-sm text-[#003366] border-[#003366] hover:bg-[#003366] hover:text-white">
          Filter
        </button>
        <a href="{{ route('download.riwayat.admin') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Download Riwayat
        </a>
      </div>
    </div>

    {{-- Tabel Pengajuan --}}
    <div id="pengajuanTab">
      <x-table-pengajuan :items="$pengajuans" />
    </div>

    {{-- Tabel Riwayat --}}
    <div id="riwayatTab" class="hidden">
      <x-table-riwayat :items="$riwayats" />
    </div>
  </div>

  <!-- {{-- Popup Detail --}}
  <div id="popupDetail" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-lg relative">
      <button onclick="closeDetail()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
      <h3 class="text-lg font-semibold mb-3">Detail Pengajuan</h3>
      <div id="popupContent" class="text-sm text-gray-700">Loading...</div>
    </div>
  </div> -->

  <script>
    function showTab(tab) {
      document.getElementById('pengajuanTab').classList.add('hidden');
      document.getElementById('riwayatTab').classList.add('hidden');
      document.getElementById('tabPengajuan').classList.remove('border-b-2', 'text-[#003366]');
      document.getElementById('tabRiwayat').classList.remove('border-b-2', 'text-[#003366]');

      if (tab === 'pengajuan') {
        document.getElementById('pengajuanTab').classList.remove('hidden');
        document.getElementById('tabPengajuan').classList.add('border-b-2', 'text-[#003366]');
      } else {
        document.getElementById('riwayatTab').classList.remove('hidden');
        document.getElementById('tabRiwayat').classList.add('border-b-2', 'text-[#003366]');
      }
    }

    // function showDetail(id) {
    //   fetch(`/sarpras/peminjaman/${id}/detail`)
    //     .then(res => res.json())
    //     .then(data => {
    //       let fasilitasList = data.detail_peminjaman.map(item => {
    //         return `<li>${item.fasilitas.nama_barang} — Jumlah: ${item.jumlah}</li>`;
    //       }).join('');

    //       let html = `
    //         <p><strong>Judul:</strong> ${data.judul_kegiatan}</p>
    //         <p><strong>Tanggal:</strong> ${data.tgl_kegiatan}</p>
    //         <p><strong>Waktu:</strong> ${data.waktu_mulai} s/d ${data.waktu_berakhir}</p>
    //         <p><strong>Aktivitas:</strong> ${data.aktivitas}</p>
    //         <p><strong>Organisasi:</strong> ${data.organisasi}</p>
    //         <p><strong>Penanggung Jawab:</strong> ${data.penanggung_jawab}</p>
    //         <p><strong>Deskripsi:</strong> ${data.deskripsi_kegiatan}</p>
    //         <p><strong>Status BEM:</strong> ${data.verifikasi_bem}</p>
    //         <p><strong>Status Sarpras:</strong> ${data.verifikasi_sarpras}</p>
    //         <p class="mt-2"><strong>Fasilitas Dipinjam:</strong></p>
    //         <ul class="list-disc list-inside">${fasilitasList}</ul>
    //       `;
    //       document.getElementById('popupContent').innerHTML = html;
    //       document.getElementById('popupDetail').classList.remove('hidden');
    //     });
    // }

    // function closeDetail() {
    //   document.getElementById('popupDetail').classList.add('hidden');
    // }
  </script>
@endsection