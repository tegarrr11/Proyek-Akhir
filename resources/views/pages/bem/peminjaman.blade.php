@extends('layouts.sidebar-bem')

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
        <form method="GET" action="{{ route('bem.peminjaman') }}" class="relative">
          <input type="hidden" name="tab" value="riwayat">
          <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari kegiatan ..." class="border rounded px-3 py-1 text-sm w-52 hidden md:inline-block" />
        </form>
      </div>
    </div>

    {{-- Tab Pengajuan --}}
    <div id="pengajuanTab">
        @include('components.pengajuan.table-pengajuan-bem', ['items' => $pengajuans])
    </div>

    {{-- Tab Riwayat --}}
    <div id="riwayatTab" class="hidden">
        @include('components.riwayat.table-riwayat-bem', ['items' => $riwayats])
    </div>
  </div>

  {{-- Modal Detail Peminjaman --}}
  <x-modal-detail-peminjaman />

@endsection

@section('script')
@push('scripts')
<script>

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
  
  function tampilkanKolomKembali(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('tr');
    row.querySelector('.status-kembali-col')?.classList.remove('hidden');
    form.submit();
  }

  function showTab(tab) {
    const tabs = ['pengajuan', 'riwayat'];

    tabs.forEach(id => {
      const tabEl = document.getElementById(`tab${capitalize(id)}`);
      const underline = document.getElementById(`underline${capitalize(id)}`);

      if (id === tab) {
        tabEl?.classList.remove('text-gray-500');
        tabEl?.classList.add('text-[#003366]');
        underline?.classList.add('scale-x-100');
        underline?.classList.remove('scale-x-0');
        document.getElementById(`${id}Tab`)?.classList.remove('hidden');
      } else {
        tabEl?.classList.add('text-gray-500');
        tabEl?.classList.remove('text-[#003366]');
        underline?.classList.add('scale-x-0');
        underline?.classList.remove('scale-x-100');
        document.getElementById(`${id}Tab`)?.classList.add('hidden');
      }
    });
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'pengajuan'; 
    showTab(tab);
  });

  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('.btnKirimDiskusi');
    const input = modal.querySelector('.inputDiskusi');
    if (!btn || !input) return;

    btn.onclick = function() {
      const pesan = input.value.trim();
      if (!pesan || !currentPeminjamanId) return;
      btn.setAttribute('disabled', true);
      let csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (!csrf) {
        const tokenInput = document.querySelector('input[name=_token]');
        if (tokenInput) csrf = tokenInput.value;
      }

      fetch('/diskusi', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ peminjaman_id: currentPeminjamanId, pesan })
      })
      .then(res => res.json())
      .then(resp => {
        if (resp.success) {
          showDetail(currentPeminjamanId);
        } else {
          alert(resp.error || 'Gagal mengirim pesan.');
        }
      })
      .catch(() => alert('Gagal mengirim pesan.'));
    };
  }

  function showDetail(id) {
    currentPeminjamanId = id;

    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);

        const formatTanggal = (tgl) => {
          const date = new Date(tgl);
          return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
          });
        };

        const formatJam = (jamStr) => jamStr ? jamStr.slice(0, 5) : '-';

        if (el('judulKegiatan')) el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        if (el('tglKegiatan')) el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        if (el('jamKegiatan')) el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        if (el('aktivitas')) el('aktivitas').textContent = data.aktivitas || '-';
        if (el('organisasi')) el('organisasi').textContent = data.organisasi || '-';
        if (el('penanggungJawab')) el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        if (el('keterangan')) el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        if (el('ruangan')) el('ruangan').textContent = data.nama_ruangan || '-';

        const linkDokumen = el('linkDokumen');
        const dokumenNotFound = el('dokumenNotFound');
        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin','mahasiswa','bem','dosen','staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;

          if (linkDokumen) {
            linkDokumen.href = downloadUrl;
            linkDokumen.onclick = function(e) {
              e.preventDefault();
              fetch(downloadUrl)
              .then(response => {
                if (!response.ok) throw new Error('Gagal download dokumen');
                return response.blob();
              })
              .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'proposal.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
              })
              .catch(() => alert('Gagal download dokumen.'));
            };
            linkDokumen.classList.remove('hidden');
          }
          dokumenNotFound?.classList.add('hidden');
        } else {
          if (linkDokumen) {
            linkDokumen.href = '#';
            linkDokumen.onclick = null;
            linkDokumen.classList.add('hidden');
          }
          dokumenNotFound?.classList.remove('hidden');
        }

        const perlengkapanList = el('perlengkapan');
        if (perlengkapanList) {
          perlengkapanList.innerHTML = '';
          if (Array.isArray(data.perlengkapan) && data.perlengkapan.length > 0) {
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
        }

        let diskusiHtml = 'belum ada diskusi';
        let adaChatAdminBem = false;
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
            if (["admin", "bem"].includes((d.role || '').toLowerCase())) {
              adaChatAdminBem = true;
            }
          });
        }
        if (el('diskusiArea')) el('diskusiArea').innerHTML = diskusiHtml;

        const userRole = "{{ auth()->user()->role }}";
        let enableDiskusi = false;
        if (userRole !== 'dosen') {
          if (userRole === 'mahasiswa' && adaChatAdminBem) {
            enableDiskusi = true;
          } else if (userRole !== 'mahasiswa') {
            enableDiskusi = true;
          }
        }

        const modal = document.getElementById('detailModal');
        const inputDiskusi = modal?.querySelector('.inputDiskusi');
        const btnKirimDiskusi = modal?.querySelector('.btnKirimDiskusi');

        if (inputDiskusi && btnKirimDiskusi) {
          if (enableDiskusi) {
            inputDiskusi.removeAttribute('disabled');
            btnKirimDiskusi.removeAttribute('disabled');
            btnKirimDiskusi.classList.remove('bg-gray-300', 'cursor-not-allowed');
            btnKirimDiskusi.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
          } else {
            inputDiskusi.setAttribute('disabled', true);
            btnKirimDiskusi.setAttribute('disabled', true);
            btnKirimDiskusi.classList.add('bg-gray-300', 'cursor-not-allowed');
            btnKirimDiskusi.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
          }

          inputDiskusi.value = '';
        }

        modal?.classList.remove('hidden');
        bindDiskusiHandler();
      })
      .catch(err => {
        console.error('Gagal fetch detail:', err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }

  document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const tabAktif = document.getElementById('riwayatTab');
    if (tabAktif.classList.contains('hidden')) return;

    document.querySelectorAll('#riwayatTab table tbody tr').forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(keyword) ? '' : 'none';
    });
  });



</script>
@endpush
@endsection
