<table class="w-full text-sm text-left text-gray-700">
  <thead class="bg-gray-100 text-black border-b">
    <tr class="text-sm font-semibold">
      <th class="px-4 py-2">No.</th>
      <th class="px-4 py-2">Pengajuan</th>
      <th class="px-4 py-2">Tanggal Pengajuan</th>
      <th class="px-4 py-2">Verifikasi BEM</th>
      <th class="px-4 py-2">Verifikasi Sarpras</th>
      <th class="px-4 py-2">Organisasi</th>
      <th class="px-4 py-2 text-center">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i => $item)
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tgl_kegiatan)->format('d/m/Y') }}</td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            @if($item->verifikasi_bem === 'diterima')
              bg-green-500 text-white
            @elseif($item->verifikasi_bem === 'ditolak')
              bg-red-100 text-red-600
            @else
              bg-yellow-500 text-white
            @endif">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>
        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-500 text-white
            @elseif($item->verifikasi_sarpras === 'ditolak')
              bg-red-100 text-red-600
            @else
              bg-yellow-500 text-white
            @endif">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>
        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2 text-center">
          <div class="flex items-center gap-2 justify-center">
            {{-- Tombol Terima --}}
            <form method="POST" action="{{ route('admin.peminjaman.verifikasi', $item->id) }}">
              @csrf
              <input type="hidden" name="verifikasi_sarpras" value="diterima">
              <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded"> Terima</button>
            </form>

            {{-- Tombol Tangguhkan --}}
            <form method="POST" action="{{ route('admin.peminjaman.verifikasi', $item->id) }}">
              @csrf
              <input type="hidden" name="verifikasi_sarpras" value="ditangguhkan">
              <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">Tangguhkan</button>
            </form>

            {{-- Tombol Detail --}}
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
        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
    @endforelse
  </tbody>
</table>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-[999] hidden bg-black/40 backdrop-blur-sm flex items-center justify-center px-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-screen mt-8 sm:mt-16" onclick="event.stopPropagation()">
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Peminjaman</h2>

    <div class="flex flex-col md:flex-row gap-6">
      <div class="flex-1 space-y-2 text-sm text-gray-800">
        <div class="flex justify-between gap-4">
          <div>
            <p class="font-semibold text-[#1e2d5e]">Judul Kegiatan</p>
            <p id="judulKegiatan">-</p>
          </div>
          <div>
            <p class="font-semibold text-[#1e2d5e]">Waktu Kegiatan</p>
            <p id="waktuKegiatan">-</p>
          </div>
        </div>
        <p class="font-semibold text-[#1e2d5e]">Aktivitas</p>
        <p id="aktivitas">-</p>

        <p class="font-semibold text-[#1e2d5e]">Organisasi</p>
        <p id="organisasi">-</p>

        <p class="font-semibold text-[#1e2d5e]">Penanggungjawab</p>
        <p id="penanggungJawab">-</p>

        <p class="font-semibold text-[#1e2d5e]">Keterangan</p>
        <p id="keterangan">-</p>

        <p class="font-semibold text-[#1e2d5e]">Ruangan</p>
        <p id="ruangan">-</p>

        <p class="font-semibold text-[#1e2d5e]">Perlengkapan</p>
        <ul id="perlengkapan" class="list-disc list-inside text-gray-800 space-y-0.5">
          <li class="italic text-gray-400">Tidak ada perlengkapan</li>
        </ul>

        <p class="font-semibold text-[#1e2d5e]">Dokumen</p>
        <a id="linkDokumen" href="#" class="text-blue-600 underline text-sm">Lihat Proposal</a>
        <span id="dokumenNotFound" class="text-gray-400 italic hidden">Tidak ada dokumen</span>
      </div>

      <div class="w-full md:w-1/3 border border-gray-200 rounded-lg p-4 flex flex-col">
        <p class="font-semibold text-[#1e2d5e] mb-1">Diskusi</p>
        <div id="diskusiArea" class="text-sm text-gray-400 italic flex-1">belum ada diskusi</div>
        <div class="mt-4">
          <input id="inputDiskusi" type="text" placeholder="Ketikkan di sini" class="w-full border rounded px-3 py-2 text-sm mb-2">
          <button id="btnKirimDiskusi" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded w-full">Kirim</button>
        </div>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const btn = document.getElementById('btnKirimDiskusi');
    if (!btn) return;
    btn.onclick = function() {
      const pesan = document.getElementById('inputDiskusi').value.trim();
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
    currentPeminjamanId = id;
    fetch(`/admin/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        const el = id => document.getElementById(id);

        const formatTanggal = (tgl) => {
          const d = new Date(tgl);
          const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
          return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
        };

        const formatJam = (jam) => jam?.substring(0,5) || '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('waktuKegiatan').textContent = `${formatTanggal(data.tgl_kegiatan)} ${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('organisasi').textContent = data.organisasi || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

        // Update dokumen link to use secure download route if dokumen exists
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
          el('linkDokumen').classList.remove('pointer-events-none', 'text-gray-400');
          el('dokumenNotFound').classList.add('hidden');
        } else {
          el('linkDokumen').href = '#';
          el('linkDokumen').onclick = null;
          el('linkDokumen').classList.add('pointer-events-none', 'text-gray-400');
          el('dokumenNotFound').classList.remove('hidden');
        }

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

        // Diskusi
        let diskusiHtml = 'belum ada diskusi';
        if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
          diskusiHtml = '';
          data.diskusi.forEach(d => {
            diskusiHtml += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> <span>${d.pesan}</span></div>`;
          });
        }
        document.getElementById('diskusiArea').innerHTML = diskusiHtml;
        document.getElementById('inputDiskusi').value = '';
        document.getElementById('inputDiskusi').removeAttribute('disabled');
        document.getElementById('btnKirimDiskusi').removeAttribute('disabled');
        document.getElementById('btnKirimDiskusi').classList.remove('bg-gray-300', 'cursor-not-allowed');
        document.getElementById('btnKirimDiskusi').classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
        document.getElementById('detailModal').classList.remove('hidden');
        bindDiskusiHandler(); // <--- re-bind setiap modal dibuka
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
@endpush
