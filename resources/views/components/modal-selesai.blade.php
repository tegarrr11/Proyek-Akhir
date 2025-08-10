<div id="modalSelesai" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
    <h2 class="text-lg font-semibold mb-4">Checklist Barang Dikembalikan</h2>

    <form id="formSelesai" method="POST" action="">
      @csrf
      @method('PATCH')

      <!-- simpan ID di sini (jangan taruh di checklistContainer) -->
      <input type="hidden" id="peminjamanId" name="peminjaman_id" value="">

      <div id="checklistContainer" class="space-y-2 mb-4"></div>

      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModalSelesai()" class="bg-gray-300 px-3 py-1 rounded">Batal</button>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Cache ringan: ingat ID fasilitas yang sudah dikembalikan (per peminjaman) selama sesi halaman ini
  // Bentuk: { [peminjamanId]: Set<detailId atau fasilitasId> }
  const returnedByLoan = {};

  (function () {
    let bound = false; // pastikan submit handler di-bind sekali saja

    function getCsrfToken() {
      return (
        document.querySelector('meta[name="csrf-token"]')?.content ||
        document.querySelector('input[name=_token]')?.value ||
        ''
      );
    }

    // === DIPANGGIL dari tombol aksi di tabel ===
    window.openModalSelesai = async function (id) {
      if (id === undefined || id === null || id === '') {
        alert('❌ ID peminjaman tidak ditemukan');
        return;
      }

      const modal = document.getElementById('modalSelesai');
      const container = modal.querySelector('#checklistContainer');
      const form = modal.querySelector('#formSelesai');
      const hid = modal.querySelector('#peminjamanId');

      // set action & id
      form.action = `/admin/peminjaman/${id}/selesai`;
      hid.value = String(id);
      modal.dataset.currentId = String(id);

      // ambil data dari server
      const res = await fetch(`/admin/peminjaman/${id}`);
      if (!res.ok) {
        alert('Gagal memuat data.');
        return;
      }
      const data = await res.json();

      // render checklist
      container.innerHTML = '';
      const perlengkapan = Array.isArray(data.perlengkapan) ? data.perlengkapan : [];

      if (perlengkapan.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada perlengkapan.</p>';
      } else {
        // set item yang sudah disubmit sebelumnya (pada sesi ini) agar di-skip
        const already = returnedByLoan[id] || new Set();

        // "Checklist Semua"
        const selectAllDiv = document.createElement('div');
        selectAllDiv.innerHTML = `
          <label class="flex items-center gap-2 text-xs text-gray-400">
            <input type="checkbox" id="selectAllChecklist" class="rounded border-gray-300">
            Checklist Semua
          </label>`;
        container.appendChild(selectAllDiv);

        // Daftar item — SKIP yang sudah ada di cache
        perlengkapan.forEach(item => {
          if (!item || typeof item.id === 'undefined') return;
          if (already.has(String(item.id))) return; // ⬅️ ini yang bikin saat reopen tidak muncul lagi
          const row = document.createElement('div');
          row.innerHTML = `
            <label class="flex items-center gap-2">
              <input type="checkbox" name="checklist[]" value="${item.id}" class="checklist-item rounded border-gray-300">
              ${item.nama} - ${item.jumlah}
            </label>`;
          container.appendChild(row);
        });

        // jika setelah skip, memang tidak ada yang tersisa
        if (container.querySelectorAll('.checklist-item').length === 0) {
          container.innerHTML = '<p class="text-gray-500 text-sm">Semua perlengkapan sudah dikembalikan.</p>';
        } else {
          // event "Checklist Semua"
          container.querySelector('#selectAllChecklist')?.addEventListener('change', function () {
            container.querySelectorAll('.checklist-item').forEach(cb => (cb.checked = this.checked));
          });
        }
      }

      // bind submit sekali
      if (!bound) {
        form.addEventListener('submit', handleSubmit);
        bound = true;
      }

      modal.classList.remove('hidden');

      // debug (opsional)
      // console.log('[DEBUG] form.action =', form.action);
      // console.log('[DEBUG] hidden #peminjamanId =', hid.value);
      // console.log('[DEBUG] modal data-current-id =', modal.dataset.currentId);
    };

    window.closeModalSelesai = function () {
      const modal = document.getElementById('modalSelesai');
      modal.classList.add('hidden');
      modal.querySelector('#checklistContainer').innerHTML = '';
    };

    // === Submit handler (scoped ke modal) ===
    async function handleSubmit(e) {
      e.preventDefault();

      const form = e.currentTarget;
      const modal = document.getElementById('modalSelesai');
      const container = modal.querySelector('#checklistContainer');
      const hid = form.querySelector('#peminjamanId');

      const checkboxes = container.querySelectorAll('.checklist-item');
      const checked = Array.from(checkboxes).filter(cb => cb.checked);

      // tidak ada item sama sekali -> biarkan default (tidak akan terjadi di UI normal)
      if (checkboxes.length === 0) return true;

      if (checked.length === 0) {
        alert('Pilih minimal satu perlengkapan.');
        return false;
      }

      const peminjamanId = hid.value || modal.dataset.currentId || '';
      if (!peminjamanId) {
        alert('ID peminjaman tidak ditemukan!');
        return false;
      }

      const ids = checked.map(cb => cb.value);
      await submitChecklist(ids, peminjamanId, container);
      return false;
    }

    // === Kirim ke server dan update UI ===
    async function submitChecklist(ids, peminjamanId, container) {
      const fd = new FormData();
      fd.append('_method', 'PATCH');
      ids.forEach(v => fd.append('checklist[]', v));

      const res = await fetch(`/admin/peminjaman/${peminjamanId}/selesai`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': getCsrfToken(),
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: fd
      });

      if (!res.ok) {
        console.error('HTTP', res.status, await res.text());
        alert('Gagal menyimpan checklist.');
        return;
      }

      // Catat ke cache agar saat modal dibuka lagi itemnya tidak muncul
      if (!returnedByLoan[peminjamanId]) returnedByLoan[peminjamanId] = new Set();
      ids.forEach(v => returnedByLoan[peminjamanId].add(String(v)));

      // Hapus yang baru dicentang dari DOM modal (langsung hilang)
      ids.forEach(v => {
        container.querySelector(`.checklist-item[value="${v}"]`)
          ?.closest('label')?.parentElement?.remove();
      });

      // Reset "Checklist Semua" jika masih ada sisa
      const sisa = container.querySelectorAll('.checklist-item').length;
      const selectAll = container.querySelector('#selectAllChecklist');
      if (selectAll && sisa > 0) selectAll.checked = false;

      if (sisa === 0) {
        // Update badge status pengembalian (opsional, tanpa reload)
        const badge = document.getElementById(`statusPengembalian-${peminjamanId}`);
        if (badge) {
          badge.textContent = 'Selesai';
          badge.className = 'bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-medium';
        }

        // Tutup modal & refresh agar baris pindah ke "Riwayat"
        closeModalSelesai();
        location.reload();
      }
    }
  })();
</script>
