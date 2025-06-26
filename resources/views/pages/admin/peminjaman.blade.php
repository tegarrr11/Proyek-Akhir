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
    closeModal(); // Tutup modal saat ganti tab
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const tab = new URLSearchParams(window.location.search).get('tab') || 'pengajuan';
    showTab(tab);
  });

  // Fungsi GLOBAL
  window.showDetail = function(id) {
    console.log('[DEBUG] Global showDetail called with id:', id);
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);
        el('judulKegiatan').innerText = data.judul_kegiatan || '-';
        el('waktuKegiatan').innerText = data.tgl_kegiatan + ' (' + data.waktu_mulai?.slice(0,5) + ' - ' + data.waktu_berakhir?.slice(0,5) + ')';
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
          const downloadUrl = `/admin/peminjaman/download-proposal/${data.id}`;
          link.href = downloadUrl;
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
</script>
@endpush
