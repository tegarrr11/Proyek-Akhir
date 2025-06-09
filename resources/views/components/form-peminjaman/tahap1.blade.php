{{-- resources/views/components/peminjaman/tahap1.blade.php --}}

@php
use App\Models\Gedung;
use App\Models\Fasilitas;

$gedungSlug = strtolower(request('gedung', ''));
$gedung = Gedung::where('slug', $gedungSlug)->first();
$fasilitasList = $gedung ? Fasilitas::where('gedung_id', $gedung->id)->where('stok', '>', 0)->get() : [];
@endphp

<div id="step1" class="bg-white border-t p-4 space-y-4 active-step">
  {{-- Pilih Gedung --}}
  <div>
    <label class="block mb-1 text-sm font-medium">Ruangan *</label>
    <select class="w-full border rounded px-3 py-2" name="gedung" id="gedung-select"
      onchange="window.location.href='?gedung=' + this.value;">
      <option value="">-- Pilih Ruangan --</option>
      <option value="gsg" {{ request('gedung') == 'gsg' ? 'selected' : '' }}>Main Hall GSG</option>
      <option value="gor" {{ request('gedung') == 'gor' ? 'selected' : '' }}>GOR</option>
      <option value="auditorium" {{ request('gedung') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
      <option value="r361" {{ request('gedung') == 'r361' ? 'selected' : '' }}>R. 361</option>
    </select>
  </div>

  {{-- Jenis Kegiatan --}}
    <div id="jenis-kegiatan-wrapper" class="hidden">
        <label class="block text-sm font-medium mb-1">Jenis Kegiatan *</label>
        <div id="jenis-kegiatan-radio" class="flex gap-4 text-sm"></div>
    </div>

  {{-- Fasilitas --}}
  @if (!empty($fasilitasList))
  <div id="fasilitas-section">
    <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan *</label>
    <table class="w-full border text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-2 py-1">No</th>
          <th class="border px-2 py-1">Nama Barang</th>
          <th class="border px-2 py-1">Jumlah</th>
          <th class="border px-2 py-1">Aksi</th>
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
            <input type="number" name="barang[{{ $index }}][jumlah]"
              class="jumlah-barang border rounded w-20 text-center"
              max="{{ $item->stok }}" value="{{ $item->stok }}" min="0"
              onchange="cekBatas()">
            <small class="text-gray-400 block">Max: {{ $item->stok }}</small>
          </td>
          <td class="border px-2 text-center">
            <button type="button" onclick="hapusBaris(this)" class="text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- Tombol lanjut --}}
  <div class="flex justify-end items-center gap-4 mt-4">
    <div id="peringatan" class="text-red-600 text-sm hidden">
      ⚠️ Jumlah barang melebihi batas maksimal stok!
    </div>
    <button type="button" onclick="lanjutTahap2()"
      class="bg-[#003366] hover:bg-[#002244] text-white px-5 py-2 rounded text-sm float-right">
      Selanjutnya
    </button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  console.log("✅ DOM Ready");

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
    if (!gedungSelect || !jenisWrapper || !radioContainer) return;

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

  window.handleJenisChange = function () {
    const selected = document.querySelector('input[name="jenis_kegiatan"]:checked');
    const isEksternal = selected?.value === 'eksternal';
    if (undanganSection) {
      undanganSection.classList.toggle('hidden', !isEksternal);
    }
  };

  // Initial setup
  updateJenisKegiatan();

  gedungSelect?.addEventListener('change', () => {
    setTimeout(updateJenisKegiatan, 100);
  });

  // Tombol lanjutan
  const nextBtn = document.querySelector('[onclick="lanjutTahap2()"]');
  if (nextBtn) {
    nextBtn.addEventListener('click', () => lanjutTahap2());
  }

  // Auto aktifkan step 1
  toggleStep(1);
});

// Toggle tahapan form
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

// Pindah ke Tahap 2
function lanjutTahap2() {
  toggleStep(2);
}

// Hapus baris fasilitas
function hapusBaris(btn) {
  const row = btn.closest('tr');
  if (row) {
    row.remove();
  }
}
</script>
