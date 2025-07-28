@extends('layouts.sidebar-mahasiswa')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

  <div class="bg-white rounded-md p-6 shadow flex-1 overflow-visible">
    {{-- Tabs --}}
    <div class="flex items-center justify-between mb-4">
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

      <div class="relative flex items-center gap-2">
        <!-- Tombol Search (hanya muncul di mobile) -->
        <button id="searchIcon" onclick="toggleSearchInput()" class="md:hidden text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="#777" d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"/>
          </svg>
        </button>

        <!-- Input Search -->
        <form method="GET" action="{{ route('mahasiswa.peminjaman') }}" class="relative">
          <input type="hidden" name="tab" value="riwayat">
          <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari kegiatan ..." class="border rounded px-3 py-1 text-sm w-52 hidden md:inline-block" />
        </form>
      </div>

    </div>

    {{-- Tab Pengajuan --}}
    <div id="pengajuanTab">
        <div id="tablePengajuan">
          @include('components.pengajuan.table-pengajuan-mahasiswa', ['items' => $pengajuans])
        </div>
    </div>

    {{-- Tab Riwayat --}}
    <div id="riwayatTab" class="hidden">
        <div id="tableRiwayat">
          @include('components.riwayat.table-riwayat-mahasiswa', ['items' => $riwayats])
        </div>
    </div>
  </div>

  {{-- Modal Detail Peminjaman --}}
  <x-modal-detail-peminjaman />
@endsection

@section('script')
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Ambil tab dari URL dan tampilkan tab terkait
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'pengajuan';
    showTab(tab);

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const activeTable = getActiveTableId();
        if (!activeTable) return;

        const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
        rows.forEach(row => {
          const kolomJudul = row.children[1];
          const isi = kolomJudul?.textContent.toLowerCase() || '';
          row.style.display = isi.includes(keyword) ? '' : 'none';
        });
      });
    }

    // Toggle input search saat di mobile
    window.toggleSearchInput = function () {
      const input = document.getElementById('searchInput');
      const icon = document.getElementById('searchIcon');

      input.classList.toggle('hidden');
      if (!input.classList.contains('hidden')) {
        input.focus();
        icon.classList.add('hidden');
      } else {
        icon.classList.remove('hidden');
      }
    };

    // Tutup search input saat klik di luar
    document.addEventListener('click', function (e) {
      const input = document.getElementById('searchInput');
      const icon = document.getElementById('searchIcon');

      if (!input.contains(e.target) && !icon.contains(e.target)) {
        input.classList.add('hidden');
        icon.classList.remove('hidden');
      }
    });
  });

  // Fungsi ganti tab
  window.showTab = function(tab) {
    const tabs = ['pengajuan', 'riwayat'];
    tabs.forEach(id => {
      const tabBtn = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);
      const tabContent = document.getElementById(`${id}Tab`);

      if (id === tab) {
        tabBtn?.classList.remove('text-gray-500');
        tabBtn?.classList.add('text-[#003366]');
        underline?.classList.add('scale-x-100');
        underline?.classList.remove('scale-x-0');
        tabContent?.classList.remove('hidden');
      } else {
        tabBtn?.classList.add('text-gray-500');
        tabBtn?.classList.remove('text-[#003366]');
        underline?.classList.add('scale-x-0');
        underline?.classList.remove('scale-x-100');
        tabContent?.classList.add('hidden');
      }
    });

    // Reset pencarian setiap kali tab berganti
    const input = document.getElementById('searchInput');
    if (input) input.value = '';
    const activeTable = tab === 'pengajuan' ? 'tablePengajuan' : 'tableRiwayat';
    const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
    rows.forEach(row => row.style.display = '');
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function getActiveTableId() {
    if (!document.getElementById('pengajuanTab').classList.contains('hidden')) return 'tablePengajuan';
    if (!document.getElementById('riwayatTab').classList.contains('hidden')) return 'tableRiwayat';
    return null;
  }
</script>
@endpush
@endsection


