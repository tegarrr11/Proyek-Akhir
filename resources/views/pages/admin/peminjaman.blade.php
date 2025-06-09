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
      <table class="w-full text-sm text-left">
        <thead class="text-gray-700 border-b">
          <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Pengajuan</th>
            <th class="px-4 py-2">Tanggal</th>
            <th class="px-4 py-2">Verifikasi BEM</th>
            <th class="px-4 py-2">Verifikasi Sarpras</th>
            <th class="px-4 py-2">Organisasi</th>
            <th class="px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pengajuans as $index => $item)
            <tr class="border-b">
              <td class="px-4 py-2">{{ $index + 1 }}</td>
              <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
              <td class="px-4 py-2">{{ $item->tgl_kegiatan }}</td>
              <td class="px-4 py-2">{{ ucfirst($item->verifikasi_bem) }}</td>
              <td class="px-4 py-2">{{ ucfirst($item->verifikasi_sarpras) ?? 'Diajukan' }}</td>
              <td class="px-4 py-2">{{ $item->organisasi }}</td>
              <td class="px-4 py-2">
                <div class="flex gap-2 items-center">
                  <form method="POST" action="{{ route('admin.peminjaman.verifikasi', $item->id) }}">
                    @csrf
                    <input type="hidden" name="verifikasi_sarpras" value="diterima">
                    <button type="submit"
                      class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">✔ Terima</button>
                  </form>
                  <form method="POST" action="{{ route('admin.peminjaman.verifikasi', $item->id) }}">
                    @csrf
                    <input type="hidden" name="verifikasi_sarpras" value="ditangguhkan">
                    <button type="submit"
                      class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">✘ Tangguhkan</button>
                  </form>
                  <button type="button" onclick="showDetail({{ $item->id }})"
                    class="text-blue-600 hover:text-blue-800 text-xs">🔍 Detail</button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Tab Riwayat --}}
    <div id="riwayatTab" class="hidden">
      <x-table-riwayat :items="$riwayats" />
    </div>
  </div>
  @include('components.card-detail-peminjaman')
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

  document.addEventListener('DOMContentLoaded', () => {
    showTab('pengajuan');
  });
</script>
@endpush
