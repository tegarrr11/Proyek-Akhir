<x-table-wrapper>
  <table class="w-full text-sm text-left text-gray-700">
    <thead class="bg-gray-100 text-black border-b">
      <tr class="text-sm font-semibold">
        <th class="px-4 py-2">No.</th>
        <th class="px-4 py-2">Pengajuan</th>
        <th class="px-4 py-2">Tanggal</th>
        <th class="px-4 py-2">Verifikasi BEM</th>
        <th class="px-4 py-2">Verifikasi Sarpras</th>
        <th class="px-4 py-2">Status Peminjaman</th>
        <th class="px-4 py-2">Status Pengembalian</th>
        <th class="px-4 py-2 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $i => $item)
      @if($item->status_pengembalian === 'selesai') @continue @endif
      @if($item->verifikasi_sarpras === 'pending') @continue @endif
      <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}" data-row-id="row-{{ $item->id }}">
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
            @elseif($item->verifikasi_sarpras === 'pending')
              bg-yellow-100 text-yellow-600 font-medium
            @else
              bg-gray-200 text-gray-600 font-medium
            @endif">
            {{ ucfirst($item->verifikasi_sarpras) }}
          </span>
        </td>
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
        <td class="px-4 py-2 text-center">
          <div class="flex gap-2 justify-center">
            {{-- BUTTON TERIMA --}}
            <button onclick="showDetail({{ $item->id }})"
              class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
              Diskusi
            </button>

            <button type="button"
              onclick="markPending('{{ route('admin.peminjaman.pending', $item->id) }}', {{ $item->id }})"
              class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 text-xs rounded">
              Pending
            </button>

            {{-- BUTTON TERIMA --}}
            @if($item->verifikasi_sarpras !== 'diterima')
            <form method="POST" action="{{ route('admin.peminjaman.approve', $item->id) }}">
              @csrf
              @method('PATCH')
              <button class="bg-green-500 text-white px-3 py-1 text-xs rounded">Terima</button>
            </form>
            @elseif($item->status_peminjaman !== 'diambil')
            {{-- BUTTON AMBIL --}}
            <form method="POST" action="{{ route('admin.peminjaman.ambil', $item->id) }}">
              @csrf
              @method('PATCH')
              <button class="bg-blue-600 text-white px-3 py-1 text-xs rounded">Ambil</button>
            </form>
            @elseif($item->status_peminjaman === 'diambil' && $item->status_pengembalian !== 'selesai')
            <button onclick="openModalSelesai({{ $item->id }})" class="bg-green-600 hover:bg-blue-700 text-white px-3 py-1 text-xs rounded">Selesai</button>
            @else
            <span class="text-gray-400 italic">Selesai</span>
            @endif

            {{-- DETAIL --}}
            <button onclick="showDetail({{ $item->id }})" class="text-gray-600 hover:text-blue-700" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0084db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</x-table-wrapper>

@include('components.modal-selesai')

@push('scripts')
<script>
  window.currentPeminjamanId = null;

  function bindDiskusiHandler() {
    const modal = document.getElementById('detailModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const btn = modal.querySelector('#btnKirimDiskusi');
    const input = modal.querySelector('#inputDiskusi');
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
          if (resp.success) showDetail(currentPeminjamanId);
          else alert(resp.error || 'Gagal mengirim pesan.');
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
          const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
        };
        const formatJam = (jam) => jam?.substring(0, 5) || '-';

        el('judulKegiatan').textContent = data.judul_kegiatan || '-';
        el('waktuKegiatan').textContent = `${formatTanggal(data.tgl_kegiatan)} ${formatJam(data.waktu_mulai)} - ${formatJam(data.waktu_berakhir)}`;
        el('aktivitas').textContent = data.aktivitas || '-';
        el('organisasi').textContent = data.organisasi || '-';
        el('penanggungJawab').textContent = data.penanggung_jawab || '-';
        el('keterangan').textContent = data.deskripsi_kegiatan || '-';
        el('ruangan').textContent = data.nama_ruangan || '-';

        if (data.link_dokumen === 'ada') {
          let prefix = window.location.pathname.split('/')[1];
          if (!['admin', 'mahasiswa', 'bem', 'dosen', 'staff'].includes(prefix)) prefix = '';
          let downloadUrl = prefix ? `/${prefix}/peminjaman/download-proposal/${data.id}` : `/peminjaman/download-proposal/${data.id}`;
          el('linkDokumen').href = downloadUrl;
          el('linkDokumen').onclick = function(e) {
            e.preventDefault();
            fetch(downloadUrl, {
                method: 'GET',
                credentials: 'same-origin'
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
        bindDiskusiHandler();
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat detail peminjaman.');
      });
  }

  function closeModal() {
    document.getElementById('detailModal')?.classList.add('hidden');
  }

  function setujuiPeminjaman(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    fetch(`/admin/peminjaman/${id}/setujui`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf
        }
      })
      .then(() => {
        const aksiCell = document.getElementById(`aksi-${id}`);
        const statusCell = document.getElementById(`status-${id}`);

        if (aksiCell) {
          const btnAmbil = document.createElement('button');
          btnAmbil.className = 'bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600';
          btnAmbil.textContent = 'Ambil';
          btnAmbil.setAttribute('onclick', `ambilBarang(${id})`);
          aksiCell.querySelector(`#btn-setujui-${id}`)?.remove();
          aksiCell.appendChild(btnAmbil);
        }

        if (statusCell) {
          statusCell.innerHTML = `<span class="text-xs text-gray-500 italic">-</span>`;
        }

        // ✅ Update kolom Verifikasi Sarpras
        const row = aksiCell.closest('tr');
        if (row) {
          const verifikasiSarprasCell = row.querySelector('td:nth-child(5)');
          if (verifikasiSarprasCell) {
            verifikasiSarprasCell.innerHTML = `
              <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">
                Diterima
              </span>`;
          }
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal menyetujui peminjaman.');
      });
  }

  async function markPending(url, id) {
    if (!confirm('Tandai pengajuan ini sebagai "pending"?')) return;

    // ambil CSRF dari meta atau input hidden (fallback)
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
        const err = await res.json().catch(() => ({}));
        console.error('Gagal set pending:', err);
        alert('Gagal memproses.');
        return;
      }

      // Hapus baris dari DOM
      const row = document.querySelector(`tr[data-row-id="row-${id}"]`);
      if (row) {
        const tbody = row.parentNode;
        row.remove();

        // jika tbody kosong, tampilkan baris "Tidak ada pengajuan."
        const masihAda = tbody.querySelectorAll('tr').length > 0;
        if (!masihAda) {
          const empty = document.createElement('tr');
          empty.innerHTML = `<td colspan="10" class="text-center py-4 text-gray-500">Tidak ada pengajuan.</td>`;
          tbody.appendChild(empty);
        }
      }

      console.log(`Pengajuan ${id} dipending & dihapus dari tabel.`);
    } catch (e) {
      console.error('Network error:', e);
      alert('Terjadi kesalahan jaringan.');
    }
  }

  function ambilBarang(id) {
    fetch(`/admin/peminjaman/${id}/ambil`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(() => {
        const aksiCell = document.getElementById(`aksi-${id}`);
        const statusCell = document.getElementById(`status-${id}`);

        if (aksiCell) {
          aksiCell.querySelector(`[onclick="ambilBarang(${id})"]`)?.remove();
          const btnSelesai = document.createElement('button');
          btnSelesai.className = 'bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700';
          btnSelesai.textContent = 'Selesai';
          btnSelesai.setAttribute('onclick', `showChecklistModal(${id})`);
          aksiCell.appendChild(btnSelesai);
        }

        if (statusCell) {
          statusCell.innerHTML = `<span class="text-xs text-gray-500 italic">Diambil</span>`;
        }
      });
  }

  function showChecklistModal(id) {
    window.currentPeminjamanId = id;

    fetch(`{{ url('admin/peminjaman') }}/${id}/checklist-html`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('checklistContent').innerHTML = data.html;

        const modal = document.getElementById('showChecklistModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      })
      .catch(err => {
        console.error(err);
        alert('Gagal memuat checklist.');
      });
  }

  function closeChecklistModal() {
    const modal = document.getElementById('showChecklistModal');
    modal.classList.add('hidden'); // sembunyikan kembali
    modal.classList.remove('flex'); // hapus flex
  }

  function submitChecklist() {
    if (!window.currentPeminjamanId) {
      alert('ID peminjaman tidak ditemukan!');
      return;
    }

    const checkedItems = [...document.querySelectorAll('input[name="barang[]"]:checked')].map(el => el.value);

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    checkedItems.forEach(item => formData.append('barang[]', item));

    fetch(`/admin/peminjaman/${window.currentPeminjamanId}/selesai`, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => {
        if (data.status === 'selesai') {
          const aksiCell = document.getElementById(`aksi-${window.currentPeminjamanId}`);
          const statusCell = document.getElementById(`status-${window.currentPeminjamanId}`);
          if (aksiCell) aksiCell.innerHTML = '';
          if (statusCell) statusCell.innerHTML = `<span class="text-green-600 font-semibold text-xs">Selesai</span>`;
          closeChecklistModal();
        } else {
          alert(data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal mengirim data.');
      });
  }
</script>
@endpush