{{-- resources/views/components/peminjaman/tahap1.blade.php --}}

@props(['fasilitasLainnya' => []])

@php
use App\Models\Gedung;
use App\Models\Fasilitas;

$gedungSlug = strtolower(request('gedung', ''));
$gedung = Gedung::where('slug', $gedungSlug)->first();
$fasilitasLainnya = Fasilitas::where('gedung_id', 8)->where('stok', '>', 0)->get();
$fasilitasList = $gedung ? Fasilitas::where('gedung_id', $gedung->id)->where('stok', '>', 0)->get() : [];

$isMahasiswa = auth()->user()->role === 'mahasiswa';
@endphp

<div id="step1" class="bg-white border-t p-4 space-y-4 active-step">
  {{-- Pilih Gedung --}}
  <div>
    <label class="block mb-1 text-sm font-medium">Ruangan *</label>
    <select class="w-full border rounded px-3 py-2" name="gedung" id="gedung-select"
      onchange="window.location.href='?gedung=' + this.value; document.getElementById('gedung-hidden').value = this.value;">
      <option value="">-- Pilih Ruangan --</option>
      <option value="gsg" {{ request('gedung') == 'gsg' ? 'selected' : '' }}>Main Hall GSG</option>
      <option value="gor" {{ request('gedung') == 'gor' ? 'selected' : '' }}>GOR</option>
      <option value="auditorium" {{ request('gedung') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
      <option value="r361" {{ request('gedung') == 'r361' ? 'selected' : '' }}>R. 361</option>
    </select>
    <input type="hidden" name="gedung" id="gedung-hidden" value="{{ request('gedung') }}">
    <script>
      document.getElementById('gedung-select').addEventListener('change', function () {
        document.getElementById('gedung-hidden').value = this.value;
      });
    </script>
  </div>

  {{-- Jenis Kegiatan --}}
  <div id="jenis-kegiatan-wrapper" class="hidden">
    <label class="block text-sm font-medium mb-1">Jenis Kegiatan *</label>
    <div id="jenis-kegiatan-radio" class="flex gap-4 text-sm"></div>
  </div>
  <p id="jenisErrorMsg" class="text-red-600 text-sm mt-1 hidden">Jenis kegiatan harus dipilih.</p>

  {{-- Fasilitas --}}
  @if ($isMahasiswa && !empty($fasilitasList))
    <div id="fasilitas-section">
      <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan Default *</label>
      <table class="w-full border text-sm mb-6">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-2 py-2 w-[40px] text-left">No</th>
            <th class="border px-2 py-2 text-left">Nama Barang</th>
            <th class="border px-2 py-2 w-[100px] text-center">Jumlah</th>
            <th class="border px-2 py-2 w-[60px] text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="fasilitas-body">
          @foreach ($fasilitasList as $index => $item)
            <tr data-index="{{ $index }}">
              <td class="border px-2 text-center">{{ $index + 1 }}</td>
              <td class="border px-2">
                {{ $item->nama_barang }}
                <input type="hidden" name="barang[{{ $index }}][id]" value="{{ $item->id }}">
              </td>
              <td class="border px-2 text-center">
                <input type="number" name="barang[{{ $index }}][jumlah]" class="jumlah-barang border rounded w-20 text-center"
                      max="{{ $item->stok }}" value="{{ $item->stok }}" min="0">
                  <small class="text-gray-400 block">Max: {{ $item->stok }}</small>
              </td>
              <td class="border px-2 text-center">
                <button type="button" onclick="hapusBaris(this)" class="text-red-500 hover:text-red-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                    <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
                  </svg>
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

  {{-- Fasilitas Tambahan --}}
  <div id="fasilitas-tambahan-section" class="mt-4 hidden">
    <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan Tambahan</label>
    <table class="w-full border text-sm">
      <thead class="bg-gray-100 text-left">
        <tr>
            <th class="border px-2 py-2 w-[40px] text-left">No</th>
            <th class="border px-2 py-2 text-left">Nama Barang</th>
            <th class="border px-2 py-2 w-[100px] text-center">Jumlah</th>
            <th class="border px-2 py-2 w-[60px] text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="fasilitas-tambahan-body">
      </tbody>
    </table>
  </div>

  <div id="tabelFasilitasTambahan" class="mt-4 hidden">
     <h3 class="block mb-1 text-sm font-medium">Fasilitas Tambahan Dipilih:</h3>
        <table class="w-full border text-sm">
          <thead class="bg-gray-100 ">
            <tr>
              <th class="border px-2 py-2 w-[40px] text-left">No</th>
              <th class="border px-2 py-2 text-left">Nama Barang</th>
              <th class="border px-2 py-2 w-[100px] text-center">Jumlah</th>
              <th class="border px-2 py-2 w-[60px] text-center">Aksi</th>
            </tr>
          </thead>
        <tbody></tbody>
    </table>
  </div>

  {{-- Tombol --}}
  <div class="flex justify-end items-center gap-4 mt-4">

    <button type="button" onclick="showFasilitasModal()"
            class="bg-white border border-[#003366] text-[#003366] hover:bg-[#e6f0ff] px-5 py-2 rounded text-sm font-medium">
      Tambah Fasilitas
    </button>

      <div id="fasilitasModal" class="fixed inset-0 bg-black/40 z-50 hidden justify-center items-center">
        <div class="bg-white rounded-xl p-6 w-[400px] max-h-[90vh] overflow-hidden relative flex flex-col">

          <!-- Tombol Close -->
          <button onclick="hideFasilitasModal()" class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">
            &times;
          </button>

          <!-- Judul -->
          <h3 class="text-base font-semibold mb-4">Fasilitas Tambahan</h3>

          <!-- Input Search -->
          <div class="relative mb-3">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
            <input type="text" id="searchFasilitasLainnya" placeholder="Cari Barang"
                  class="w-full pl-10 pr-3 py-2 border rounded text-sm focus:outline-none focus:ring focus:ring-blue-200" />
          </div>

          <!-- Daftar Fasilitas -->
          <div id="fasilitasList" class="flex-1 overflow-y-auto divide-y divide-gray-200 text-sm text-gray-800">
            <!-- Diisi oleh JS -->
          </div>

          <!-- Pagination + Tambah -->
          <div class="flex justify-between items-center mt-4 text-sm">
            <div id="paginationControls" class="flex gap-1"></div>
            <button onclick="tambahFasilitasTerpilih()"
                    id="tombolTambah"
                    class="px-4 py-1.5 bg-cyan-200 hover:bg-cyan-300 text-sm text-gray-900 font-medium rounded disabled:opacity-50"
                    disabled>Tambah</button>
          </div>
        </div>
      </div>

    <button type="button" id="btnTahap1Next" onclick="lanjutKeTahap2()"
            class="bg-[#003366] hover:bg-[#002244] text-white px-5 py-2 rounded text-sm font-medium">
      Selanjutnya
    </button>
  </div>
    <p id="peringatan" class="text-red-500 hidden mt-2 text-sm flex justify-end items-center">Jumlah fasilitas melebihi stok yang tersedia</p>
    <p id="gedungErrorMsg" class="text-red-500 text-sm mt-2 hidden flex justify-end items-center">Harap pilih ruangan terlebih dahulu sebelum lanjut ke Tahap 2.</p>
</div>

<script>
  const fasilitasData = {};

  function showFasilitasModal() {
    document.getElementById('fasilitasModal').classList.remove('hidden');
    document.getElementById('fasilitasModal').classList.add('flex');
    tampilkanFasilitas(); 
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

      if (gedung === 'Auditorium') {
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

  let semuaFasilitas = @json($fasilitasLainnya); 
  let halaman = 1;
  const perHalaman = 5;
  let fasilitasTerpilih = new Set();

  function tampilkanFasilitas() {
    const keyword = document.getElementById('searchFasilitasLainnya').value.toLowerCase();
    const hasilFilter = semuaFasilitas.filter(f => f.nama_barang.toLowerCase().includes(keyword));
    const totalHalaman = Math.ceil(hasilFilter.length / perHalaman);

    halaman = Math.max(1, Math.min(halaman, totalHalaman)); 
    const awal = (halaman - 1) * perHalaman;
    const akhir = awal + perHalaman;
    const daftar = hasilFilter.slice(awal, akhir);

    // Render daftar
    const container = document.getElementById('fasilitasList');
    container.innerHTML = '';
    daftar.forEach(item => {
      const el = document.createElement('div');
      el.className = 'flex justify-between items-center py-2 px-2 pr-4 hover:bg-gray-50';
      el.innerHTML = `
        <div>
          <div class="font-medium">${item.nama_barang}</div>
          <div class="text-xs text-gray-500">Stok: ${item.stok}</div>
        </div>
        <input type="checkbox" value="${item.id}" class="form-checkbox h-4 w-4 text-blue-600 rounded"
          ${fasilitasTerpilih.has(item.id) ? 'checked' : ''} onchange="toggleFasilitas(${item.id}, this)">
      `;
      container.appendChild(el);
    });

    renderPagination(totalHalaman);
    toggleTombolTambah();
  }

  function renderPagination(totalHalaman) {
    const container = document.getElementById('paginationControls');
    container.innerHTML = '';

    const buatTombol = (label, page, aktif = false) => {
      const btn = document.createElement('button');
      btn.textContent = label;
      btn.className = `px-2 py-1 border rounded ${aktif ? 'bg-blue-100 text-blue-700' : ''}`;
      btn.onclick = () => {
        halaman = page;
        tampilkanFasilitas();
      };
      container.appendChild(btn);
    };

    if (halaman > 1) buatTombol('<', halaman - 1);
    for (let i = 1; i <= totalHalaman; i++) {
      if (i === 1 || i === totalHalaman || Math.abs(i - halaman) <= 1) {
        buatTombol(i, i, i === halaman);
      } else if (
        (i === 2 && halaman > 3) ||
        (i === totalHalaman - 1 && halaman < totalHalaman - 2)
      ) {
        const dots = document.createElement('span');
        dots.textContent = '...';
        container.appendChild(dots);
      }
    }
    if (halaman < totalHalaman) buatTombol('>', halaman + 1);
  }

  function toggleFasilitas(id, checkbox) {
    if (checkbox.checked) {
      fasilitasTerpilih.add(id);
    } else {
      fasilitasTerpilih.delete(id);
    }
    toggleTombolTambah();
  }

  function toggleTombolTambah() {
    document.getElementById('tombolTambah').disabled = fasilitasTerpilih.size === 0;
  }

  function tambahFasilitasTerpilih() {
    const container = document.getElementById('tabelFasilitasTambahan');
    const tbody = container.querySelector('tbody');
    tbody.innerHTML = ''; // Kosongkan isi tabel dulu

    let index = 0;
    fasilitasTerpilih.forEach((id) => {
      const item = semuaFasilitas.find(f => f.id == id);
      if (!item) return;

      const row = document.createElement('tr');
      row.innerHTML = `
      <tr>
        <td class="border px-2 text-center">${index + 1}</td>

        <td class="border px-2">
          ${item.nama_barang}
          <input type="hidden" name="fasilitas_tambahan[${item.id}][id]" value="${item.id}">
        </td>

        <td class="border px-2 text-center align-top">
          <div class="flex flex-col items-center">
            <input 
              type="number" 
              name="fasilitas_tambahan[${item.id}][jumlah]" 
              value="${item.jumlah || 1}" 
              max="${item.stok}" 
              min="1"
              class="jumlah-barang border rounded w-20 text-center">
            <small class="text-xs text-gray-500 mt-1">Max: ${item.stok}</small>
          </div>
        </td>

        <td class="border px-2 text-center">
          <button type="button" onclick="hapusFasilitasTambahan(${item.id})" class="text-red-600 hover:text-red-800 text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
              <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
            </svg>
          </button>
        </td>
      </tr>
      `;
      tbody.appendChild(row);
      index++;
    });

    container.classList.remove('hidden');
    hideFasilitasModal(); // Tutup modal setelah selesai
  }

  function hapusFasilitasTambahan(id) {
    fasilitasTerpilih.delete(id);
    document.querySelector(`[data-id="${id}"] input[type="checkbox"]`)?.click();
    tambahFasilitasTerpilih(); // Refresh tabel
  }


  document.getElementById('searchFasilitasLainnya').addEventListener('input', () => {
    halaman = 1;
    tampilkanFasilitas();
  });

  function showFasilitasModal() {
    document.getElementById('fasilitasModal').classList.remove('hidden');
    document.getElementById('fasilitasModal').classList.add('flex');
    tampilkanFasilitas();
  }

  function hideFasilitasModal() {
    document.getElementById('fasilitasModal').classList.add('hidden');
    document.getElementById('fasilitasModal').classList.remove('flex');
  }

function lanjutKeTahap2() {
  const selectGedung = document.getElementById('gedung-select');
  const errorMsg = document.getElementById('gedungErrorMsg');
  const jenisErrorMsg = document.getElementById('jenisErrorMsg');
  const jumlahInputs = document.querySelectorAll('.jumlah-barang');
  const peringatan = document.getElementById('peringatan');
  const jenisKegiatanInputs = document.querySelectorAll('input[name="jenis_kegiatan"]');
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');

  let stokValid = true;
  let jenisKegiatanValid = true;

  // Validasi stok
  jumlahInputs.forEach(input => {
    const max = parseInt(input.max);
    const value = parseInt(input.value);
    if (value > max) stokValid = false;
  });

  // Menampilkan peringatan jika stok tidak valid
  if (!stokValid) {
    peringatan.classList.remove('hidden');
    peringatan.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  } else {
    peringatan.classList.add('hidden');
  }

  // Validasi ruangan (gedung)
  if (!selectGedung.value) {
    errorMsg.classList.remove('hidden');
    return;
  } else {
    errorMsg.classList.add('hidden');
  }

  // Validasi jenis kegiatan
  const selectedJenis = document.querySelector('input[name="jenis_kegiatan"]:checked');
  if (!selectedJenis) {
    jenisErrorMsg.classList.remove('hidden');
    jenisKegiatanValid = false;
  } else {
    jenisErrorMsg.classList.add('hidden');
  }

  // Jika semua valid, lanjutkan ke langkah 2
  if (stokValid && jenisKegiatanValid) {
    step1.classList.remove('active-step');
    step2.classList.add('active-step');
    toggleStep(2);
  }
}

</script>
