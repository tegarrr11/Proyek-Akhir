<table class="w-full text-sm">
  <thead class="bg-gray-100">
    <tr>
      <th class="px-4 py-2">No.</th>
      <th class="px-4 py-2">Pengajuan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2">Status Peminjaman</th>
      <th class="px-4 py-2">Status Pengembalian</th>
      <th class="px-4 py-2 hidden status-kembali-col">Status Pengembalian</th>
      <th class="px-4 py-2"></th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      @if($item->status_pengembalian === 'selesai')
        @continue
      @endif

      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-500 text-white text-xs px-3 py-1 rounded' : 'bg-gray-200 text-gray-600' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-500 text-white text-xs px-3 py-1 rounded
            @elseif($item->verifikasi_sarpras === 'proses')
              bg-yellow-100 text-yellow-800
            @else
              bg-gray-200 text-gray-600
            @endif">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>

        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2">
          @if ($item->verifikasi_sarpras === 'diterima')
            @if ($item->status_peminjaman === 'kembalikan' && $item->status_pengembalian === 'proses')
              <form method="POST" action="{{ route('mahasiswa.peminjaman.kembalikan', $item->id) }}" onsubmit="return confirm('Yakin ingin mengembalikan barang ini?')">
                @csrf
                @method('PATCH')
                <button class="bg-yellow-500 text-white px-3 py-1 text-xs rounded hover:bg-yellow-600 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Kembalikan
                </form>
            @elseif (is_null($item->status_peminjaman))
              <form method="POST" action="{{ route('mahasiswa.peminjaman.ambil', $item->id) }}">
                @csrf
                @method('PATCH')
                <button class="bg-blue-600 text-white px-3 py-1 text-xs rounded hover:bg-blue-700">Ambil</button>
              </form>
            @else
              {{ ucfirst($item->status_peminjaman) }}
            @endif
          @else
            <span class="text-gray-400 text-xs italic">-</span>
          @endif
        </td>

        <td class="px-4 py-2">
          @if ($item->status_peminjaman === 'kembalikan')
            <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">Belum</span>
          @else
            <span class="text-gray-400 text-xs italic">-</span>
          @endif
        </td>
        <td class="px-4 py-2 hidden status-kembali-col">{{ ucfirst($item->status_pengembalian ?? '-') }}</td>

        <td class="px-4 py-2">
          <div class="flex items-center gap-2">
            <button onclick="showDetail({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

<!-- Modal hanya satu, di luar loop table -->
<div id="detailModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4">
  <!-- Overlay, pointer-events none agar tidak menutupi konten -->
  <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(2px);pointer-events:none;"></div>
  <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl p-6 relative" style="pointer-events:auto;z-index:2;" onclick="event.stopPropagation()">

    <!-- Tombol Close -->
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>

    <!-- Judul -->
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Peminjaman</h2>

    <div class="flex flex-col md:flex-row gap-6">
      <!-- Kolom Kiri -->
      <div class="flex-1 space-y-2 text-sm text-gray-800">
        <div class="flex justify-between gap-4">
          <div>
            <p class="font-semibold text-[#1e2d5e]">Judul Kegiatan</p>
            <p id="judulKegiatan">-</p>
          </div>
          <div>
            <p class="font-semibold text-[#1e2d5e]">Waktu Kegiatan</p>
            <p><span id="tglKegiatan">-</span> &nbsp; <span id="jamKegiatan">-</span></p>
          </div>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Aktivitas</p>
          <p id="aktivitas">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Penanggungjawab Kegiatan</p>
          <p id="penanggungJawab">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Keterangan</p>
          <p id="keterangan">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Ruangan</p>
          <p id="ruangan">-</p>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Perlengkapan</p>
          <ul id="perlengkapan" class="list-disc list-inside text-gray-800 space-y-0.5">
            <li class="italic text-gray-400">Tidak ada perlengkapan</li>
          </ul>
        </div>

        <div>
          <p class="font-semibold text-[#1e2d5e]">Dokumen</p>
          <a id="linkDokumen" href="#" class="text-blue-600 underline text-sm">Lihat Proposal</a>
          <span id="dokumenNotFound" class="text-gray-400 italic hidden">Tidak ada dokumen</span>
        </div>
      </div>

      <!-- Kolom Kanan (Diskusi) -->
      <div class="w-full md:w-1/3 border border-gray-200 rounded-lg p-4 flex flex-col">
        <p class="font-semibold text-[#1e2d5e] mb-1">Diskusi</p>
        <div id="diskusiArea" class="text-sm text-gray-400 italic flex-1">belum ada diskusi</div>
        <div class="mt-4">
          <input class="inputDiskusi w-full border rounded px-3 py-2 text-sm mb-2" id="inputDiskusi" type="text" placeholder="Ketikkan di sini" disabled>
          <button class="btnKirimDiskusi bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded w-full" id="btnKirimDiskusi" disabled>Kirim</button>
        </div>
      </div>
    </div>

    <!-- Tombol Tutup di bawah -->
    <div class="mt-6 text-right">
      <button onclick="closeModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">Tutup</button>
    </div>
  </div>
</div>


@push('scripts')
<script>
console.log('[DEBUG] Script chat loaded');

  function tampilkanKolomKembali(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('tr');
    row.querySelector('.status-kembali-col').classList.remove('hidden');
    form.submit();
  }

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

  let currentPeminjamanId = null;
  function bindDiskusiHandler() {
    // Cari modal yang sedang aktif
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('.btnKirimDiskusi');
    const input = modal.querySelector('.inputDiskusi');
    const btns = document.querySelectorAll('.btnKirimDiskusi');
    const inputs = document.querySelectorAll('.inputDiskusi');
    console.log('[DEBUG] bindDiskusiHandler dipanggil', btn, input, 'Jumlah btn:', btns.length, 'Jumlah input:', inputs.length);
    if (!btn) return;
    btn.onclick = function() {
      console.log('[DEBUG] Tombol Kirim diklik');
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
          showDetail(currentPeminjamanId); // refresh chat
        } else {
          alert(resp.error || 'Gagal mengirim pesan.');
        }
      })
      .catch(() => alert('Gagal mengirim pesan.'));
    };
  }

  function showDetail(id) {
    console.log('[DEBUG] showDetail dipanggil dengan id:', id);
    currentPeminjamanId = id;
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);

        // Format Tanggal Indonesia
        const formatTanggal = (tgl) => {
          const date = new Date(tgl);
          return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
          });
        };

        // Format Jam tanpa detik
        const formatJam = (jamStr) => jamStr ? jamStr.slice(0, 5) : '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('tglKegiatan').textContent = formatTanggal(data.tgl_kegiatan);
        el('jamKegiatan').textContent = `${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

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
          li.className = 'italic text-gray-400';
          li.textContent = 'Tidak ada perlengkapan';
          perlengkapanList.appendChild(li);
        }

        // Dokumen
        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin','mahasiswa','bem','dosen','staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;
          el('linkDokumen').href = downloadUrl;
          el('linkDokumen').onclick = function(e) {
            e.preventDefault();
            fetch(downloadUrl, {
              method: 'GET',
              credentials: 'same-origin',
            })
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
          el('linkDokumen').classList.remove('hidden');
          el('dokumenNotFound').classList.add('hidden');
        } else {
          el('linkDokumen').href = '#';
          el('linkDokumen').onclick = null;
          el('linkDokumen').classList.add('hidden');
          el('dokumenNotFound').classList.remove('hidden');
        }

        // Diskusi
        let diskusiHtml = 'belum ada diskusi';
        let adaChatAdminBem = false;
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
            // Perbaiki pengecekan role, pastikan lowercase
            if (["admin","bem"].includes((d.role||'').toLowerCase())) adaChatAdminBem = true;
          });
        }
        document.getElementById('diskusiArea').innerHTML = diskusiHtml;
        // Aktifkan input hanya jika role bukan dosen, dan jika mahasiswa: hanya jika ada chat admin/bem
        const userRole = "{{ auth()->user()->role }}";
        let enableDiskusi = false;
        if (userRole !== 'dosen') {
          if (userRole === 'mahasiswa') {
            if (adaChatAdminBem) enableDiskusi = true;
          } else {
            enableDiskusi = true;
          }
        }
        // Debugging
        console.log('DISKUSI:', data.diskusi, 'adaChatAdminBem:', adaChatAdminBem, 'userRole:', userRole, 'enableDiskusi:', enableDiskusi);
        const inputDiskusi = document.getElementById('inputDiskusi');
        const btnKirimDiskusi = document.getElementById('btnKirimDiskusi');
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
        // Paksa input dan tombol chat selalu aktif untuk debug
        const modal = document.getElementById('detailModal');
        const inputDiskusi = modal.querySelector('.inputDiskusi');
        const btnKirimDiskusi = modal.querySelector('.btnKirimDiskusi');
        inputDiskusi.removeAttribute('disabled');
        btnKirimDiskusi.removeAttribute('disabled');
        btnKirimDiskusi.classList.remove('bg-gray-300', 'cursor-not-allowed');
        btnKirimDiskusi.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
        inputDiskusi.value = '';
        document.getElementById('detailModal').classList.remove('hidden');
        // Tambahkan style pada modal agar selalu di atas dan pointer-events aktif
        const modal = document.getElementById('detailModal');
        if (modal) {
          modal.style.zIndex = '99999';
          modal.style.pointerEvents = 'auto';
          modal.style.background = 'rgba(0,0,0,0.2)';
          modal.style.opacity = '1';
          console.log('[DEBUG] Modal detailModal visible:', !modal.classList.contains('hidden'), modal);
        }
        // Paksa style tombol dan input agar selalu bisa diklik
        inputDiskusi.style.pointerEvents = 'auto';
        inputDiskusi.style.zIndex = '9999';
        inputDiskusi.style.opacity = '1';
        btnKirimDiskusi.style.pointerEvents = 'auto';
        btnKirimDiskusi.style.zIndex = '9999';
        btnKirimDiskusi.style.opacity = '1';
        // Tambah log debug pada event mousedown dan click
        btnKirimDiskusi.onmousedown = function() { console.log('[DEBUG] Tombol Kirim mousedown'); };
        btnKirimDiskusi.onclick = function() { console.log('[DEBUG] Tombol Kirim click'); };
        bindDiskusiHandler(); // <--- re-bind setiap modal dibuka
      })
      .catch(err => {
        console.error('Gagal fetch detail:', err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }
</script>
@endpush
