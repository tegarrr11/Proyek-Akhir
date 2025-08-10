<x-table-wrapper>
  <table class="w-full text-sm">
    <thead class="bg-gray-100 text-left">
      <tr>
        <th class="px-4 py-2">No.</th>
        <th class="px-4 py-2">Pengajuan</th>
        <th class="px-4 py-2">Tanggal Pengajuan</th>
        <th class="px-4 py-2">Verifikasi BEM</th>
        <th class="px-4 py-2">Verifikasi Sarpras</th>
        <th class="px-4 py-2">Organisasi</th>
        <th class="px-4 py-2">Status Peminjaman</th>
        <th class="px-4 py-2">Status Pengembalian</th>
        <th class="px-2 py-2"></th>
        <th class="px-2 py-2"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $i => $item)
      @if($item->status_pengembalian === 'selesai')
      @continue
      @endif
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}"
          data-row-id="row-{{ $item->id }}">
        <td class="px-4 py-2">{{ $i + 1 }}</td>
        <td class="px-4 py-2">{{ $item->judul_kegiatan }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>

        <td class="px-4 py-2">
          <span class="px-3 py-1 text-xs rounded-full {{ $item->verifikasi_bem === 'diterima' ? 'bg-green-100 text-green-600 font-medium' : 'bg-gray-200 text-gray-600 font-medium' }}">
            {{ ucfirst($item->verifikasi_bem) }}
          </span>
        </td>
        <td class="px-4 py-2">
          <span id="vsBadge-{{ $item->id }}" 
                class="px-3 py-1 text-xs rounded-full
                @if($item->verifikasi_sarpras === 'diterima')
                  bg-green-100 text-green-600 font-medium
                @elseif(in_array($item->verifikasi_sarpras, ['proses','diajukan']))
                  bg-gray-200 text-gray-700 font-medium
                @elseif($item->verifikasi_sarpras === 'pending')
                  bg-yellow-100 text-yellow-600 font-medium
                @else
                  bg-gray-200 text-gray-600 font-medium
                @endif">
            {{ in_array($item->verifikasi_sarpras, ['', NULL]) ? '-' : ucfirst($item->verifikasi_sarpras) }}
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

        <td class="px-2 py-2">
          <div class="flex items-center gap-2">
              <!-- ajukan -->
            @if($item->verifikasi_sarpras === 'pending')
              <button type="button"
                data-action="ajukan"
                onclick="markAjukan('{{ route('admin.peminjaman.ajukan', $item->id) }}', {{ $item->id }})"
                class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-xs rounded">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z"/>
                </svg>
                Ajukan
              </button>
            @endif
              <!-- edit -->
              <button onclick="fetchAndShowEditModal({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Edit Detail">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="#0071ff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.587 1.413T19 21zm4-6v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4t-.137.738t-.438.662L13.25 15zM21.025 4.4l-1.4-1.4zM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575zm6.5-6.5l-.725-.7zl.7.7z"/>
                </svg>
              </button>
              <!-- detail -->
              <button onclick="showDetail({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#025891" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <path d="M12 16v-4" />
                  <path d="M12 8h.01" />
                </svg>
              </button>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
      <div id="toastSuccess"
        class="fixed top-6 right-6 z-[9999] hidden opacity-0 bg-green-500 text-white px-4 py-2 rounded shadow-lg transition-opacity duration-300">
        Berhasil diperbarui!
      </div>
      @endforelse
    </tbody>
  </table>
</x-table-wrapper>
@include('components.modal-edit-detail-peminjaman')

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

  async function markAjukan(url, id) {
    let csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrf) {
      const tokenInput = document.querySelector('input[name=_token]');
      if (tokenInput) csrf = tokenInput.value;
    }

    try {
      const res = await fetch(url, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        }
      });

      if (!res.ok) {
        console.error('Gagal ajukan:', await res.text());
        alert('Gagal mengajukan. Coba lagi.');
        return;
      }

      // === Update badge verifikasi_sarpras ===
      const badge = document.getElementById(`vsBadge-${id}`);
      if (badge) {
        badge.textContent = 'Diajukan';
        badge.className = 'px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-700 font-medium';
      }

      // === Hilangkan tombol Ajukan tanpa refresh ===
      const row = document.querySelector(`tr[data-row-id="row-${id}"]`);
      if (row) {
        const btnAjukan = row.querySelector('button[data-action="ajukan"]');
        if (btnAjukan) {
          btnAjukan.remove(); 
        }
      }

      console.log(`Pengajuan ${id} di-set ke "diajukan" & tombol dihapus.`);
    } catch (e) {
      console.error('Network error:', e);
      alert('Terjadi kesalahan jaringan.');
    }
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

  function fetchAndShowEditModal(id) {
    fetch(`/mahasiswa/peminjaman/${id}`)
      .then(res => res.json())
      .then(data => {
        showEditModal(data); 
      })
      .catch(err => {
        alert('Gagal mengambil data peminjaman.');
        console.error('', err);
      });
  }

  function showToast(message) {
    const toast = document.getElementById('toastSuccess');
    toast.textContent = message;
    toast.classList.remove('hidden');
    void toast.offsetWidth;
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');
    setTimeout(() => {
      toast.classList.remove('opacity-100');
      toast.classList.add('opacity-0');
      setTimeout(() => {
        toast.classList.add('hidden');
      }, 300);
    }, 3000);
  }
</script>
@endpush