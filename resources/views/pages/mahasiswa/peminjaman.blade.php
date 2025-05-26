@extends('layouts.sidebar-mahasiswa')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />
<!-- 
  <a href="{{ url('/peminjaman/download/mahasiswa') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    Download PDF
</a> -->

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
  <a href="{{ route('download.riwayat') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
    Download Riwayat
  </a>

</div>
  </div>

  {{-- Tabel Pengajuan --}}
  <div id="pengajuanTab">
    <x-table-pengajuan-mahasiswa :items="$pengajuans" />
  </div>

  {{-- Tabel Riwayat --}}
  <div id="riwayatTab" class="hidden">
    <x-table-riwayat :items="$riwayats" />
  </div>
</div>

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
</script>
@endsection
