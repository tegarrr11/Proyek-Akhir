

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['fasilitasLainnya' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['fasilitasLainnya' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
use App\Models\Gedung;
use App\Models\Fasilitas;

$gedungSlug = strtolower(request('gedung', ''));
$gedung = Gedung::where('slug', $gedungSlug)->first();
$fasilitasLainnya = Fasilitas::where('gedung_id', 8)->where('stok', '>', 0)->get();
$fasilitasList = $gedung ? Fasilitas::where('gedung_id', $gedung->id)->where('stok', '>', 0)->get() : [];

$isMahasiswa = auth()->user()->role === 'mahasiswa';
?>

<div id="step1" class="bg-white border-t p-4 space-y-4 active-step">
  
  <div>
    <label class="block mb-1 text-sm font-medium">Ruangan *</label>
    <select class="w-full border rounded px-3 py-2" name="gedung" id="gedung-select"
      onchange="window.location.href='?gedung=' + this.value; document.getElementById('gedung-hidden').value = this.value;">
      <option value="">-- Pilih Ruangan --</option>
      <option value="gsg" <?php echo e(request('gedung') == 'gsg' ? 'selected' : ''); ?>>Main Hall GSG</option>
      <option value="gor" <?php echo e(request('gedung') == 'gor' ? 'selected' : ''); ?>>GOR</option>
      <option value="auditorium" <?php echo e(request('gedung') == 'auditorium' ? 'selected' : ''); ?>>Auditorium</option>
      <option value="r361" <?php echo e(request('gedung') == 'r361' ? 'selected' : ''); ?>>R. 361</option>
    </select>
    <input type="hidden" name="gedung" id="gedung-hidden" value="<?php echo e(request('gedung')); ?>">
    <script>
      document.getElementById('gedung-select').addEventListener('change', function () {
        document.getElementById('gedung-hidden').value = this.value;
      });
    </script>
  </div>

  
  <div id="jenis-kegiatan-wrapper" class="hidden">
    <label class="block text-sm font-medium mb-1">Jenis Kegiatan *</label>
    <div id="jenis-kegiatan-radio" class="flex gap-4 text-sm"></div>
  </div>

  
  <?php if($isMahasiswa && !empty($fasilitasList)): ?>
    <div id="fasilitas-section">
      <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan Default *</label>
      <table class="w-full border text-sm mb-6">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-2 py-1">No</th>
            <th class="border px-2 py-1">Nama Barang</th>
            <th class="border px-2 py-1">Jumlah</th>
            <th class="border px-2 py-1">Aksi</th>
          </tr>
        </thead>
        <tbody id="fasilitas-body">
          <?php $__currentLoopData = $fasilitasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr data-index="<?php echo e($index); ?>">
              <td class="border px-2 text-center"><?php echo e($index + 1); ?></td>
              <td class="border px-2">
                <?php echo e($item->nama_barang); ?>

                <input type="hidden" name="barang[<?php echo e($index); ?>][id]" value="<?php echo e($item->id); ?>">
              </td>
              <td class="border px-2 text-center">
                <input type="number" name="barang[<?php echo e($index); ?>][jumlah]" class="jumlah-barang border rounded w-20 text-center"
                      max="<?php echo e($item->stok); ?>" value="<?php echo e($item->stok); ?>" min="0">
                  <small class="text-gray-400 block">Max: <?php echo e($item->stok); ?></small>
              </td>
              <td class="border px-2 text-center">
                <button type="button" onclick="hapusBaris(this)" class="text-red-500 hover:text-red-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                    <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
                  </svg>
                </button>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

  
  <div id="fasilitas-tambahan-section" class="mt-4 hidden">
    <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan Tambahan</label>
    <table class="w-full border text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-2 py-1">No</th>
          <th class="border px-2 py-1">Nama Barang</th>
          <th class="border px-2 py-1">Jumlah</th>
          <th class="border px-2 py-1">Aksi</th>
        </tr>
      </thead>
      <tbody id="fasilitas-tambahan-body">
      </tbody>
    </table>
  </div>

  
  <div class="flex justify-end items-center gap-4 mt-4">
    <div id="peringatan" class="text-red-600 text-sm hidden">
      ⚠️ Jumlah barang melebihi batas maksimal stok!
    </div>

    <button type="button" onclick="showFasilitasModal()"
            class="bg-white border border-[#003366] text-[#003366] hover:bg-[#e6f0ff] px-5 py-2 rounded text-sm font-medium">
      Tambah Fasilitas
    </button>

    <div id="fasilitasModal" class="fixed inset-0 bg-black/40 z-50 hidden justify-center items-center">
      <div class="bg-white rounded-xl p-6 w-[400px] relative">
        <button onclick="hideFasilitasModal()" class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">
          &times;
        </button>
        <h3 class="text-lg font-semibold mb-4">Fasilitas Tambahan</h3>

        <input type="text" id="searchFasilitasLainnya" placeholder="Cari fasilitas..."
          class="w-full px-3 py-2 mb-3 border rounded text-sm focus:outline-none focus:ring focus:ring-blue-200">


        <div id="fasilitasList" class="space-y-3 text-sm text-gray-800"></div>

          <div id="pagination-controls" class="flex justify-center items-center gap-4 mt-4 text-sm text-gray-700">
            <button onclick="gantiHalaman(-1)" id="prevBtn" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50" disabled>
              &lt;
            </button>
            <span id="pageIndicator">1 / 1</span>
            <button onclick="gantiHalaman(1)" id="nextBtn" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
              &gt;
            </button>
          </div>

      </div>
    </div>

    <button type="button" onclick="lanjutTahap2()"
            class="bg-[#003366] hover:bg-[#002244] text-white px-5 py-2 rounded text-sm font-medium">
      Selanjutnya
    </button>
  </div>
</div>

<script>
  const fasilitasData = {};

  function showFasilitasModal() {
    document.getElementById('fasilitasModal').classList.remove('hidden');
    document.getElementById('fasilitasModal').classList.add('flex');
    tampilkanFasilitas(); // <== ini yang harus ditambahkan
  }


  function hideFasilitasModal() {
    document.getElementById('fasilitasModal')?.classList.add('hidden');
  }

  function tambahKeFasilitas(id, nama, stok) {
    const tbody = document.getElementById('fasilitas-tambahan-body');
    const section = document.getElementById('fasilitas-tambahan-section');
    const modalItem = document.getElementById(`modal-item-${id}`);
    if (!tbody || !modalItem) return;

    modalItem.style.display = 'none';
    fasilitasData[id] = { nama, stok };

    const index = tbody.children.length;

    if (section.classList.contains('hidden')) section.classList.remove('hidden');

    const row = document.createElement('tr');
    row.id = `tambah-item-${id}`;
    row.innerHTML = `
      <td class="border px-2 text-center">${index + 1}</td>
      <td class="border px-2">
        ${nama}
        <input type="hidden" name="fasilitas_tambahan[${id}][id]" value="${id}">
      </td>
      <td class="border px-2 text-center">
        <input type="number" name="fasilitas_tambahan[${id}][jumlah]" value="${stok}" max="${stok}" min="0"
               class="jumlah-barang border rounded w-20 text-center">
      </td>
      <td class="border px-2 text-center">
        <button type="button" class="text-red-500 hover:text-red-700" onclick="hapusFasilitasTambahan(${id})">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
            <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
          </svg>
        </button>
      </td>`;
    tbody.appendChild(row);
    hideFasilitasModal();
  }

  function hapusFasilitasTambahan(id) {
    const row = document.getElementById(`tambah-item-${id}`);
    const modalItem = document.getElementById(`modal-item-${id}`);
    if (row) row.remove();
    if (modalItem) modalItem.style.display = 'flex';

    const tbody = document.getElementById('fasilitas-tambahan-body');
    const section = document.getElementById('fasilitas-tambahan-section');
    if (tbody && tbody.children.length === 0 && section) {
      section.classList.add('hidden');
    }
  }

  function hapusBaris(btn) {
    const row = btn.closest('tr');
    if (row) row.remove();
  }

  function toggleStep(step) {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');

    [step1, step2].forEach(s => s?.classList.remove('active-step'));
    [btn1, btn2].forEach(b => b?.classList.remove('bg-green-100', 'font-semibold'));

    if (step === 1) {
      step1?.classList.add('active-step');
      btn1?.classList.add('bg-green-100', 'font-semibold');
    } else if (step === 2) {
      step2?.classList.add('active-step');
      btn2?.classList.add('bg-green-100', 'font-semibold');
    }
  }

  function lanjutTahap2() {
  const jumlahInputs = document.querySelectorAll('.jumlah-barang');
  let stokValid = true;

  jumlahInputs.forEach(input => {
    const max = parseInt(input.max);
    const value = parseInt(input.value);
    if (value > max) {
      stokValid = false;
    }
  });

  const peringatan = document.getElementById('peringatan');

  if (!stokValid) {
    peringatan.classList.remove('hidden');
    peringatan.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  } else {
    peringatan.classList.add('hidden');
    toggleStep(2);
  }
}


  function handleJenisChange() {
    const selected = document.querySelector('input[name="jenis_kegiatan"]:checked');
    const isEksternal = selected?.value === 'eksternal';
    const undanganSection = document.getElementById('undangan-wrapper');
    if (undanganSection) {
      undanganSection.classList.toggle('hidden', !isEksternal);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const gedungSelect = document.getElementById('gedung-select');
    const jenisWrapper = document.getElementById('jenis-kegiatan-wrapper');
    const radioContainer = document.getElementById('jenis-kegiatan-radio');
    const undanganSection = document.getElementById('undangan-wrapper');

    const jenisByGedung = {
      gsg: ['internal', 'eksternal'],
      r361: ['internal', 'eksternal'],
      gor: ['internal', 'eksternal', 'olahraga'],
      auditorium: ['eksternal']
    };

    function updateJenisKegiatan() {
      const gedung = gedungSelect.value;
      const jenisList = jenisByGedung[gedung] || [];

      jenisWrapper.classList.add('hidden');
      radioContainer.innerHTML = '';
      if (undanganSection) undanganSection.classList.add('hidden');

      if (!jenisList.length) return;

      jenisWrapper.classList.remove('hidden');

      if (gedung === 'auditorium') {
        radioContainer.innerHTML = `
          <div class="flex items-center gap-2 relative group">
            <span class="font-medium text-sm">Eksternal</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-600 cursor-pointer" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
            </svg>
            <div class="absolute left-6 top-1 bg-yellow-100 text-sm text-gray-800 px-3 py-1 rounded shadow-md w-max hidden group-hover:block z-10">
              Penggunaan auditorium hanya diperuntukan untuk acara eksternal
            </div>
          </div>
          <input type="hidden" name="jenis_kegiatan" value="eksternal">
        `;
        if (undanganSection) undanganSection.classList.remove('hidden');
        return;
      }

      jenisList.forEach(jenis => {
        const label = jenis.charAt(0).toUpperCase() + jenis.slice(1);
        radioContainer.innerHTML += `
          <label class="flex items-center gap-2">
            <input type="radio" name="jenis_kegiatan" value="${jenis}" onchange="handleJenisChange()" class="border-gray-300 focus:ring focus:ring-blue-200">
            <span>${label}</span>
          </label>
        `;
      });
    }

    updateJenisKegiatan();
    gedungSelect?.addEventListener('change', () => {
      setTimeout(updateJenisKegiatan, 100);
    });

    toggleStep(1);
  });

  // Search filter fasilitas tambahan
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchFasilitasLainnya');
    const items = document.querySelectorAll('.fasilitas-item');

    if (searchInput) {
      searchInput.addEventListener('input', () => {
        const keyword = searchInput.value.toLowerCase();
        items.forEach(item => {
          const nama = item.dataset.nama;
          item.style.display = nama.includes(keyword) ? 'flex' : 'none';
        });
      });
    }
  });

  const semuaFasilitas = <?php echo json_encode($fasilitasLainnya, 15, 512) ?>;
let halamanSekarang = 1;
const itemPerHalaman = 5;

function tampilkanFasilitas() {
  const listContainer = document.getElementById('fasilitasList');
  listContainer.innerHTML = '';

  const awal = (halamanSekarang - 1) * itemPerHalaman;
  const akhir = awal + itemPerHalaman;
  const halamanData = semuaFasilitas.slice(awal, akhir);

  halamanData.forEach(item => {
    const div = document.createElement('div');
    div.className = 'flex justify-between items-center border-b py-2 fasilitas-item';
    div.id = `modal-item-${item.id}`;
    div.dataset.nama = item.nama_barang.toLowerCase();

    div.innerHTML = `
      <div>
        <div class="font-semibold">${item.nama_barang}</div>
        <div class="text-xs text-gray-500">Stok: ${item.stok}</div>
      </div>
      <button type="button" class="text-blue-600 hover:underline text-xs"
              onclick="tambahKeFasilitas(${item.id}, '${item.nama_barang}', ${item.stok})">
        Tambah
      </button>
    `;
    listContainer.appendChild(div);
  });
}

  const fasilitasItems = Array.from(document.querySelectorAll('#fasilitasList > div'));
  const itemsPerPage = 5;
  let currentPage = 1;
  let totalPages = Math.max(1, Math.ceil(fasilitasItems.length / itemsPerPage));

  function renderHalaman() {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    fasilitasItems.forEach((item, index) => {
      item.style.display = (index >= start && index < end) ? 'flex' : 'none';
    });

    document.getElementById('pageIndicator').innerText = `${currentPage} / ${totalPages}`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
  }

  function gantiHalaman(arah) {
    currentPage += arah;
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;
    renderHalaman();
  }

  document.addEventListener('DOMContentLoaded', () => {
    totalPages = Math.max(1, Math.ceil(fasilitasItems.length / itemsPerPage));
    renderHalaman();
  });


</script>
<?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/components/form-peminjaman/tahap1.blade.php ENDPATH**/ ?>