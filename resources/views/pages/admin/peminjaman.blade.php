@extends('layouts.sidebar-admin')

@section('title', 'Peminjaman')

@section('content')
<x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

@if(session('success'))
<div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
  {{ session('success') }}
</div>
@endif

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
    <x-pengajuan.table-pengajuan-admin :items="$pengajuans" />
  </div>

  {{-- Tab Riwayat --}}
  <div id="riwayatTab" class="hidden">
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <form method="GET" action="" class="flex gap-2 items-center w-full md:w-auto" onsubmit="setRiwayatTabFlag()">
        <!-- Tombol Search (hanya mobile) -->
        <button id="searchIcon" onclick="toggleSearchInput()" type="button" class="md:hidden text-gray-600">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="#777" d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"/>
          </svg>
        </button>

        <!-- Input Search -->
        <input
          type="text"
          id="searchInput"
          name="search"
          placeholder="Cari kegiatan..."
          value="{{ request('search') }}"
          class="hidden md:block border border-gray-300 rounded px-3 py-1 text-sm bg-white shadow z-20 w-40 md:w-52 focus:outline-none focus:ring-0 focus:border-gray-300 transition-all"
        />

        <!-- Dropdown -->
        <select name="gedung_id" class="border rounded px-2 py-1 text-sm w-40" onchange="setRiwayatTabFlag(); this.form.submit();">
          <option value="">Semua Ruangan</option>
          @foreach(App\Models\Gedung::all() as $gedung)
          <option value="{{ $gedung->id }}" {{ request('gedung_id') == $gedung->id ? 'selected' : '' }}>{{ $gedung->nama }}</option>
          @endforeach
        </select>

        <input type="hidden" name="tab" id="tabInput" value="riwayat">
      </form>

      <!-- Tombol Download -->
      <a href="{{ route('download.riwayat.admin') }}"
        class="inline-flex items-center gap-2 border rounded px-2 py-1 text-sm bg-blue-900 hover:bg-blue-950 text-white font-regular shadow transition duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>
        Download Riwayat
      </a>
    </div>

    @include('components.riwayat.table-riwayat-admin', ['items' => $riwayats])
  </div>
</div>

{{-- MODAL DETAIL GLOBAL --}}
<x-modal-detail-peminjaman />
@endsection

@push('scripts')
<script>
  function setRiwayatTabFlag() {
    document.getElementById('tabInput').value = 'riwayat';
  }

  function showTab(tab) {
    ['pengajuan', 'riwayat'].forEach(id => {
      document.getElementById(`tab${capitalize(id)}`)?.classList.toggle('text-[#003366]', id === tab);
      document.getElementById(`tab${capitalize(id)}`)?.classList.toggle('text-gray-500', id !== tab);
      document.getElementById(`underline${capitalize(id)}`)?.classList.toggle('scale-x-100', id === tab);
      document.getElementById(`underline${capitalize(id)}`)?.classList.toggle('scale-x-0', id !== tab);
      document.getElementById(`${id}Tab`)?.classList.toggle('hidden', id !== tab);
    });
    closeModal();
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function() {
    const tab = new URLSearchParams(window.location.search).get('tab') || 'pengajuan';
    showTab(tab);
  });

  window.showDetail = function(id) {
    console.log('[DEBUG] Global showDetail called with id:', id);
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);
        el('judulKegiatan').innerText = data.judul_kegiatan || '-';
        el('waktuKegiatan').innerText = data.tgl_kegiatan + ' (' + data.waktu_mulai?.slice(0, 5) + ' - ' + data.waktu_berakhir?.slice(0, 5) + ')';
        el('aktivitas').innerText = data.aktivitas || '-';
        el('organisasi').innerText = data.organisasi || '-';
        el('penanggungJawab').innerText = data.penanggung_jawab || '-';
        el('keterangan').innerText = data.deskripsi_kegiatan || '-';
        el('ruangan').innerText = data.nama_ruangan || '-';

        const perlengkapanList = el('perlengkapan');
        perlengkapanList.innerHTML = '';
        if (Array.isArray(data.perlengkapan) && data.perlengkapan.length > 0) {
          data.perlengkapan.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.nama} - ${item.jumlah}`;
            perlengkapanList.appendChild(li);
          });
        } else {
          const li = document.createElement('li');
          li.className = 'text-gray-400 italic';
          li.textContent = 'Tidak ada perlengkapan';
          perlengkapanList.appendChild(li);
        }

        const link = el('linkDokumen');
        const notfound = el('dokumenNotFound');
        if (data.link_dokumen === 'ada') {
          link.href = `/admin/peminjaman/download-proposal/${data.id}`;
          link.classList.remove('hidden');
          notfound.classList.add('hidden');
        } else {
          link.href = '#';
          link.classList.add('hidden');
          notfound.classList.remove('hidden');
        }

        const diskusiArea = el('diskusiArea');
        if (Array.isArray(data.diskusi)) {
          let html = '';
          data.diskusi.forEach(d => {
            html += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
          });
          diskusiArea.innerHTML = html || '<p class="text-gray-400 italic">Belum ada diskusi</p>';
        }

        document.getElementById('detailModal').classList.remove('hidden');
        window.currentPeminjamanId = data.id;
      })
      .catch(err => {
        console.error('Gagal memuat detail:', err);
        alert('Gagal memuat detail peminjaman.');
      });
  };

  window.closeModal = function() {
    document.getElementById('detailModal')?.classList.add('hidden');
  };

  function toggleSearchInput() {
    const input = document.getElementById('searchInput');
    const icon = document.getElementById('searchIcon');

    input.classList.toggle('hidden');
    if (!input.classList.contains('hidden')) {
      input.focus();
      icon.classList.add('hidden');
    } else {
      icon.classList.remove('hidden');
    }
  }

  document.addEventListener('click', function (e) {
    const input = document.getElementById('searchInput');
    const icon = document.getElementById('searchIcon');

    if (!input.contains(e.target) && !icon.contains(e.target)) {
      input.classList.add('hidden');
      icon.classList.remove('hidden');
    }
  });
</script>
@endpush
