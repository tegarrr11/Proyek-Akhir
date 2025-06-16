@extends('layouts.sidebar-admin')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

  {{-- Notifikasi sukses --}}
  @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
      {{ session('success') }}
    </div>
  @endif

  {{-- Card utama --}}
  <div class="bg-white rounded-lg shadow p-6">
    {{-- Tabs --}}
    <div class="flex items-center justify-between mb-6">
      <div class="flex gap-6 relative">
        <button onclick="showTab('pengajuan')" id="tabPengajuan"
          class="pb-2 relative text-sm font-semibold text-[#003366]">
          <span>Pengajuan</span>
          <span class="absolute left-0 -bottom-0.5 w-full h-[2px] bg-[#003366] scale-x-100 origin-left transition-transform duration-300" id="underlinePengajuan"></span>
        </button>

        <button onclick="showTab('riwayat')" id="tabRiwayat"
          class="pb-2 relative text-sm font-semibold text-gray-500">
          <span>Riwayat</span>
          <span class="absolute left-0 -bottom-0.5 w-full h-[2px] bg-[#003366] scale-x-0 origin-left transition-transform duration-300" id="underlineRiwayat"></span>
        </button>
      </div>
    </div>

    {{-- Tab Pengajuan --}}
    <div id="pengajuanTab">
      <x-table-pengajuan-admin :items="$pengajuans" />
    </div>

    {{-- Tab Riwayat --}}
    <div id="riwayatTab" class="hidden">
      <x-table-riwayat :items="$riwayats" />
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function showTab(tab) {
    const tabs = ['pengajuan', 'riwayat'];

    tabs.forEach(id => {
      const tabEl = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);

      if (id === tab) {
        tabEl.classList.remove('text-gray-500');
        tabEl.classList.add('text-[#003366]');
        underline.classList.add('scale-x-100');
        underline.classList.remove('scale-x-0');
        document.getElementById(`${id}Tab`).classList.remove('hidden');
      } else {
        tabEl.classList.add('text-gray-500');
        tabEl.classList.remove('text-[#003366]');
        underline.classList.add('scale-x-0');
        underline.classList.remove('scale-x-100');
        document.getElementById(`${id}Tab`).classList.add('hidden');
      }
    });
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    showTab('pengajuan');
  });
</script>
@endpush
