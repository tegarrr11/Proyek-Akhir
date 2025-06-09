@extends('layouts.sidebar-bem')

@section('title', 'Peminjaman')

@section('content')
  <x-header title="Peminjaman" breadcrumb="Peminjaman > Pengajuan" />

  @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
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
      <div class="flex gap-2">
        <input type="text" placeholder="Cari........"
          class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-[#003366]">
        <button class="border px-3 py-1 rounded text-sm text-[#003366] border-[#003366] hover:bg-[#003366] hover:text-white flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zM3 9h18v2H3V9zm0 5h18v2H3v-2zm0 5h18v2H3v-2z" />
          </svg>
          Filter
        </button>
      </div>
    </div>

    {{-- Tab Konten --}}
    <div id="pengajuanTab">
<table class="w-full text-sm text-left text-gray-700">
  <thead class="bg-gray-50 text-black border-b">
    <tr class="text-sm font-semibold">
      <th class="px-4 py-3">No.</th>
      <th class="px-4 py-3">Pengajuan</th>
      <th class="px-4 py-3">Tanggal Pengajuan</th>
      <th class="px-4 py-3">Verifikasi BEM</th>
      <th class="px-4 py-3">Verifikasi Sarpras</th>
      <th class="px-4 py-3">Organisasi</th>
      <th class="px-4 py-3 text-center">Aksi</th>
      <th class="px-4 py-3 text-center"></th> {{-- Kolom detail tanpa judul --}}
    </tr>
  </thead>
  <tbody class="text-sm">
    @foreach ($pengajuans as $index => $item)
      <tr class="border-b hover:bg-gray-50 transition duration-150">
        <td class="px-4 py-3">{{ $index + 1 }}</td>
        <td class="px-4 py-3">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-3">{{ $item->tgl_kegiatan }}</td>

        {{-- Verifikasi BEM --}}
        <td class="px-4 py-3">
          <span class="px-3 py-1 rounded-full text-xs 
            {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>

        {{-- Verifikasi Sarpras --}}
        <td class="px-4 py-3 text-gray-500 text-xs">-</td>

        {{-- Organisasi --}}
        <td class="px-4 py-3">{{ $item->organisasi }}</td>

        {{-- Aksi --}}
        <td class="px-4 py-3">
          <form method="POST" action="{{ route('bem.peminjaman.verifikasi', $item->id) }}" class="flex items-center justify-center">
            @csrf
            <input type="hidden" name="verifikasi_bem" value="diterima">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs px-4 py-1 rounded flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Terima
            </button>
          </form>
        </td>

        {{-- Detail --}}
        <td class="px-4 py-3 text-center">
          <button onclick="showDetail({{ $item->id }})" title="Detail"
            class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 20h.01" />
            </svg>
          </button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
    </div>

    <div id="riwayatTab" class="hidden">
      <x-table-riwayat :items="$riwayats" />
    </div>
  </div>

  @include('components.card-detail-peminjaman')

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

    function showDetail(id) {
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);
        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('waktuKegiatan').textContent = `${data.tgl_kegiatan} ${data.waktu_mulai} - ${data.waktu_berakhir}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('organisasi').textContent = data.organisasi || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';
        el('linkDokumen').href = data.link_dokumen || '#';

        const perlengkapanList = el('perlengkapan');
        perlengkapanList.innerHTML = '';
        if (data.perlengkapan?.length > 0) {
          data.perlengkapan.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.nama} - ${item.jumlah}`;
            perlengkapanList.appendChild(li);
          });
        } else {
          const li = document.createElement('li');
          li.className = 'italic text-gray-400';
          li.textContent = 'Tidak ada perlengkapan';
          perlengkapanList.appendChild(li);
        }

        el('diskusiArea').textContent = 'belum ada diskusi';
        document.getElementById('detailModal').classList.remove('hidden');
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }


  </script>
@endsection
