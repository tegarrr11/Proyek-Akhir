@extends('layouts.sidebar-mahasiswa')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

  <div class="bg-white rounded-md shadow flex-1 p-6">
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

      <div class="flex gap-2">
        <input type="text" id="searchInput" placeholder="Cari berdasarkan tanggal atau kata kunci..."
          class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-[#003366]">
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
    showTab('pengajuan');

    const searchInput = document.getElementById('searchInput');

    // Live filtering untuk tab aktif
    searchInput?.addEventListener('input', function () {
      const keyword = this.value.toLowerCase();
      const activeTable = getActiveTableId();
      if (!activeTable) return;
      const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
      rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(keyword) ? '' : 'none';
      });
    });

    function getActiveTableId() {
      if (!document.getElementById('pengajuanTab').classList.contains('hidden')) return 'tablePengajuan';
      if (!document.getElementById('riwayatTab').classList.contains('hidden')) return 'tableRiwayat';
      return null;
    }

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

      // Reset pencarian dan tampilkan ulang semua baris
      const input = document.getElementById('searchInput');
      input.value = '';
      const activeTable = tab === 'pengajuan' ? 'tablePengajuan' : 'tableRiwayat';
      const rows = document.querySelectorAll(`#${activeTable} tbody tr`);
      rows.forEach(row => row.style.display = '');
    }

    function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  });
</script>
@endpush
@endsection
