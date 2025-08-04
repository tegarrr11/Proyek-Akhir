<x-table-wrapper>
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
          <span class="px-3 py-1 text-xs rounded-full {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-100 text-green-600 font-medium' : 'bg-gray-200 text-gray-600 font-medium' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full
            @if($item->verifikasi_sarpras === 'diterima')
              bg-green-100 text-green-600 font-medium
            @elseif(in_array($item->verifikasi_sarpras, ['proses','diajukan']))
              bg-gray-200 text-gray-700 font-medium
            @else
              bg-gray-200 text-gray-600 font-medium
            @endif">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>

        <td class="px-4 py-2">{{ $item->organisasi }}</td>
        <td class="px-4 py-2">
          @if ($item->status_peminjaman === 'diambil')
          <span class="bg-blue-100 text-blue-600 text-xs px-3 py-1 rounded-full font-medium">Sedang Dipinjam</span>
          @else
          <span class="bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full font-medium">Menunggu</span>
          @endif
        </td>

        <td class="px-4 py-2">
          @if ($item->status_pengembalian === 'selesai')
          <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-medium">Selesai</span>
          @else
          <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-medium">Belum</span>
          @endif
        </td>

        <td class="px-4 py-2">
          <button onclick="showDetail({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <path d="M12 16v-4" />
              <path d="M12 8h.01" />
            </svg>
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</x-table-wrapper>

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

  document.addEventListener('DOMContentLoaded', function() {
    showTab('pengajuan');
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
          body: JSON.stringify({
            peminjaman_id: currentPeminjamanId,
            pesan
          })
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

        const formatTanggal = (tgl) => new Date(tgl).toLocaleDateString('id-ID', {
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        });

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
          if (!['admin', 'mahasiswa', 'bem', 'dosen', 'staff'].includes(prefix)) prefix = '';
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
            if (["admin", "bem"].includes((d.role || '').toLowerCase())) adaChatAdminBem = true;
          });
        }
        document.getElementById('diskusiArea').innerHTML = diskusiHtml;

        const userRole = "{{ auth()->user()->role }}";
        let enableDiskusi = false;
        if (userRole !== 'dosen') {
          if (userRole === 'mahasiswa') {
            if (adaChatAdminBem) enableDiskusi = true;
          } else {
            enableDiskusi = true;
          }
        }

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

        inputDiskusi.value = '';
        document.getElementById('detailModal').classList.remove('hidden');
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
</script>
@endpush