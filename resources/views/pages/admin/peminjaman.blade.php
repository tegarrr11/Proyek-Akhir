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
      <div class="mb-4 flex items-center justify-between gap-2">
        <form method="GET" action="" class="flex gap-2 mb-0" onsubmit="setRiwayatTabFlag()">
          <select name="gedung_id" class="border rounded px-2 py-1 text-sm" onchange="setRiwayatTabFlag(); this.form.submit();">
            <option value="">Semua Ruangan</option>
            @foreach(App\Models\Gedung::all() as $gedung)
              <option value="{{ $gedung->id }}" {{ request('gedung_id') == $gedung->id ? 'selected' : '' }}>{{ $gedung->nama }}</option>
            @endforeach
          </select>
          <input type="hidden" name="tab" id="tabInput" value="riwayat">
        </form>
        <a href="{{ route('download.riwayat.admin') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
          </svg>
          Download Riwayat
        </a>
      </div>
      <x-table-riwayat-admin :items="$riwayats" />
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function setRiwayatTabFlag() {
    document.getElementById('tabInput').value = 'riwayat';
  }

  function showTab(tab) {
    const tabs = ['pengajuan', 'riwayat'];
    tabs.forEach(id => {
      const tabEl = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);
      const tabDiv = document.getElementById(`${id}Tab`);
      if (id === tab) {
        tabEl.classList.remove('text-gray-500');
        tabEl.classList.add('text-[#003366]');
        underline.classList.add('scale-x-100');
        underline.classList.remove('scale-x-0');
        tabDiv.classList.remove('hidden');
      } else {
        tabEl.classList.add('text-gray-500');
        tabEl.classList.remove('text-[#003366]');
        underline.classList.add('scale-x-0');
        underline.classList.remove('scale-x-100');
        tabDiv.classList.add('hidden');
      }
    });
    // Sembunyikan modal detail setiap kali pindah tab
    const modal = document.getElementById('detailModal');
    if (modal) modal.classList.add('hidden');
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'pengajuan';
    showTab(tab);
  });
</script>
@endpush
