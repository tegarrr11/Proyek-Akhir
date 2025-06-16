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
        <input type="text" placeholder="Cari........"
          class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-[#003366]">
        <button class="border px-3 py-1 rounded text-sm text-[#003366] border-[#003366] hover:bg-[#003366] hover:text-white">
          Filter
        </button>
      </div>
    </div>

    {{-- Tab Pengajuan --}}
    <div id="pengajuanTab">
      <x-table-pengajuan-mahasiswa :items="$pengajuans" />
    </div>

    {{-- Tab Riwayat --}}
    <div id="riwayatTab" class="hidden">
      <x-table-riwayat :items="$riwayats" />
    </div>
  </div>

  {{-- Script --}}
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

    function showModal(id) {
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

    document.addEventListener('DOMContentLoaded', () => {
      showTab('pengajuan');
    });
  </script>
@endsection
