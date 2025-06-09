@php
use App\Models\Gedung;
use App\Models\Fasilitas;

$gedungSlug = strtolower($_GET['gedung'] ?? '');
$gedung = Gedung::where('slug', $gedungSlug)->first();
$fasilitasList = [];

if ($gedung) {
    $fasilitasList = Fasilitas::where('gedung_id', $gedung->id)
        ->where('stok', '>', 0)
        ->get();
}
@endphp



<form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" enctype="multipart/form-data" class="bg-white rounded-md shadow-md p-6">
  @csrf

  <style>
    #step1, #step2 { display: none; }
    .active-step { display: block !important; }
  </style>

  <div class="mb-6 border rounded overflow-hidden">
    {{-- === Tahap 1 === --}}
    <button type="button" onclick="toggleStep(1)" id="btn1"
        class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm bg-white text-[#003366]">
        <span class="font-semibold">Tahap 1 - Fasilitas</span>
        <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

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
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 8h12M9 8v10m6-10v10M5 6h14l-1 14H6L5 6z"/>
                  </svg>
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

    {{-- === Tahap 2 === --}}
    <button type="button" onclick="toggleStep(2)" id="btn2"
        class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm bg-white text-[#003366]">
        <span class="font-semibold">Tahap 2 - Detail Kegiatan</span>
        <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div id="step2" class="bg-white border-t p-4 space-y-4">
    {{-- Judul Kegiatan --}}
    <div>
        <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
        <input type="text" name="judul_kegiatan" class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Waktu Kegiatan --}}
    <div>
        <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
        <input type="date" class="border rounded px-2 py-1 w-full" name="tgl_kegiatan" required>
        <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_mulai" required>
        <span class="text-sm text-center">s/d</span>
        <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_berakhir" required>
        </div>
    </div>

    {{-- Aktivitas --}}
    <div>
        <label class="block text-sm font-medium mb-1">Aktivitas *</label>
        <input type="text" name="aktivitas" class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Organisasi --}}
    <div>
        <label class="block text-sm font-medium mb-1">Organisasi *</label>
        <select class="w-full border rounded px-3 py-2" name="organisasi" required>
        <option>BEM</option>
        <option>BLM</option>
        <option>HIMA</option>
        <option>UKM</option>
        <option>KM</option>
        </select>
    </div>

    {{-- Penanggung Jawab --}}
    <div>
        <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
        <select class="w-full border rounded px-3 py-2" name="penanggung_jawab" required>
        <option value="">Pilih penanggung jawab...</option>
        <option value="AAZ - Alvin Alvarez">AAZ - Alvin Alvarez</option>
        <option value="JKT - Jessica Kartika">JKT - Jessica Kartika</option>
        <option value="FZN - Fajar Zainuddin">FZN - Fajar Zainuddin</option>
        </select>
    </div>

    {{-- Keterangan --}}
    <div>
        <label class="block text-sm font-medium mb-1">Keterangan *</label>
        <textarea name="deskripsi_kegiatan" class="w-full border rounded px-3 py-2" rows="3" placeholder="Penjelasan singkat kegiatan" required></textarea>
    </div>

    {{-- Upload Proposal --}}
    <div>
        <label class="block text-sm font-medium mb-1">Lampirkan Proposal (PDF) *</label>
        <input type="file" name="proposal" class="w-full border rounded px-3 py-2" accept="application/pdf" required>
    </div>

    {{-- Upload Surat Undangan Pembicara (Jika eksternal) --}}
    <div id="undangan-wrapper" class="hidden">
        <label class="block text-sm font-medium mb-1">Surat Undangan Pembicara (PDF)</label>
        <input type="file" name="undangan_pembicara" class="w-full border rounded px-3 py-2" accept="application/pdf">
    </div>

    <div class="flex justify-end items-center gap-4 mt-4">
        <div id="validasi-form" class="text-red-600 text-sm hidden">
        ⚠️ Mohon lengkapi semua kolom sebelum menyimpan.
        </div>
        <button id="btn-simpan" type="submit"
        class="px-5 py-2 bg-green-400 text-white rounded text-sm opacity-60 cursor-not-allowed"
        disabled>
        Simpan
        </button>
    </div>
    </div>                                  
  </div>
</form>

<div id="auditorium-warning" class="hidden bg-yellow-100 text-sm text-gray-800 px-4 py-2 rounded relative w-fit">
        Penggunaan auditorium hanya diperuntukan untuk acara eksternal
</div>

<script>
  const jenisWrapper = document.getElementById('jenis-kegiatan-wrapper');
  const jenisRadioContainer = document.getElementById('jenis-kegiatan-radio');
  const auditoriumWarning = document.getElementById('auditorium-warning');
  const fasilitasSection = document.getElementById('fasilitas-section');
  const undanganPembicara = document.getElementById('undangan-wrapper');
  const gedungSelect = document.getElementById('gedung-select');

  const jenisByGedung = {
    gsg: ['internal', 'eksternal'],
    r361: ['internal', 'eksternal'],
    gor: ['internal', 'eksternal', 'olahraga'],
    auditorium: ['eksternal']
  };

  function updateJenisKegiatanOptions() {
    const gedung = gedungSelect.value;
    const jenisList = jenisByGedung[gedung] || [];

    jenisRadioContainer.innerHTML = '';
    auditoriumWarning.classList.add('hidden');
    jenisWrapper.classList.add('hidden');

    if (!gedung) return;

    jenisWrapper.classList.remove('hidden');

    if (gedung === 'auditorium') {
    jenisRadioContainer.innerHTML = `
        <div class="flex items-center gap-2">
        <span class="font-medium text-sm">Eksternal</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 cursor-pointer text-yellow-600" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" onclick="showAuditoriumInfo()" onmouseover="showAuditoriumInfo()">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
        </svg>
        </div>
    `;

        // Set internal value jenis_kegiatan menjadi eksternal
    const eksternalRadio = document.createElement('input');
    eksternalRadio.type = 'hidden';
    eksternalRadio.name = 'jenis_kegiatan';
    eksternalRadio.value = 'eksternal';
    document.getElementById('jenis-kegiatan-wrapper').appendChild(eksternalRadio);

    // Paksa trigger ke step 2 logic
    handleJenisChange();
    }else {
      jenisList.forEach(jenis => {
        const label = jenis.charAt(0).toUpperCase() + jenis.slice(1);
        jenisRadioContainer.innerHTML += `
          <label class="flex items-center gap-1">
            <input type="radio" name="jenis_kegiatan" value="${jenis}" onchange="handleJenisChange()"> ${label}
          </label>
        `;
      });
    }
  }

    function showAuditoriumInfo() {
    const info = document.getElementById('auditorium-warning');
    info.classList.remove('hidden');

    setTimeout(() => {
        info.classList.add('hidden');
    }, 5000); // Sembunyikan otomatis setelah 5 detik
    }

  function handleJenisChange() {
    const selected = document.querySelector('input[name="jenis_kegiatan"]:checked');
    if (!selected) return;

    const jenis = selected.value;

    // Fasilitas
    if (jenis === 'olahraga') {
      fasilitasSection?.classList.add('hidden');
    } else {
      fasilitasSection?.classList.remove('hidden');
    }

    // Surat Undangan Pembicara
    if (jenis === 'eksternal') {
      undanganPembicara.classList.remove('hidden');
    } else {
      undanganPembicara.classList.add('hidden');
    }
  }

  function toggleStep(stepNumber) {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');

    const all = [
      { btn: btn1, panel: step1 },
      { btn: btn2, panel: step2 }
    ];

    all.forEach(({ btn, panel }, index) => {
      const isActive = stepNumber === index + 1;
      if (isActive) {
        panel.classList.add('active-step');
        btn.classList.add('bg-green-100');
        btn.querySelector('.icon-arrow')?.classList.add('stroke-green-700');
      } else {
        panel.classList.remove('active-step');
        btn.classList.remove('bg-green-100');
        btn.querySelector('.icon-arrow')?.classList.remove('stroke-green-700');
      }
    });
  }

  function lanjutTahap2() {
    cekBatas();
    if (!document.getElementById('peringatan').classList.contains('hidden')) {
      alert("Periksa jumlah barang! Ada yang melebihi batas stok.");
      return;
    }

    toggleStep(2);
    document.getElementById('step2')?.scrollIntoView({ behavior: 'smooth' });
    setTimeout(cekFormLengkap, 200);
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateJenisKegiatanOptions();
    handleJenisChange();

    const step2 = document.getElementById('step2');
    const errorExists = step2.querySelector('.required');
    toggleStep(errorExists ? 2 : 1);
  });

  function cekBatas() {
  let over = false;
  document.querySelectorAll('.jumlah-barang').forEach(input => {
    const max = parseInt(input.max);
    const val = parseInt(input.value);
    if (val > max) over = true;
  });

  const peringatan = document.getElementById('peringatan');
  if (peringatan) {
    peringatan.classList.toggle('hidden', !over);
  }
 }

 function cekFormLengkap() {
  let lengkap = true;
  document.querySelectorAll('#step2 label .required').forEach(el => el.remove());

  document.querySelectorAll('#step2 input, #step2 select, #step2 textarea').forEach(input => {
    const wrapper = input.closest('div');
    const label = wrapper ? wrapper.querySelector('label') : null;

    const isRadio = input.type === 'radio';
    const isFile = input.type === 'file';

    if (!input.disabled && ((input.type !== 'radio' && input.type !== 'file' && !input.value.trim()) ||
      (isFile && input.required && !input.files.length))) {
      lengkap = false;
      if (label && !label.querySelector('.required')) {
        const span = document.createElement('span');
        span.className = 'required text-red-500 ml-1';
        span.textContent = '*';
        label.appendChild(span);
      }
    }

    if (isRadio) {
      const group = document.querySelectorAll(`input[name="${input.name}"]`);
      const checked = Array.from(group).some(r => r.checked);
      if (!checked && label && !label.querySelector('.required')) {
        lengkap = false;
        const span = document.createElement('span');
        span.className = 'required text-red-500 ml-1';
        span.textContent = '*';
        label.appendChild(span);
      }
    }
  });

  const btnSimpan = document.getElementById('btn-simpan');
  const validasiMsg = document.getElementById('validasi-form');

  if (btnSimpan) {
    btnSimpan.disabled = !lengkap;
    btnSimpan.classList.toggle('opacity-60', !lengkap);
    btnSimpan.classList.toggle('cursor-not-allowed', !lengkap);
    btnSimpan.classList.toggle('bg-green-600', lengkap);
    btnSimpan.classList.toggle('bg-green-400', !lengkap);
  }

  if (validasiMsg) {
    validasiMsg.classList.toggle('hidden', lengkap);
  }

  if (!lengkap) {
    const firstInvalid = document.querySelector('#step2 input:invalid, #step2 select:invalid, #step2 textarea:invalid');
    if (firstInvalid) {
      firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      firstInvalid.focus();
    }
  }
}

document.querySelectorAll('#step2 input, #step2 select, #step2 textarea').forEach(el => {
  el.addEventListener('input', cekFormLengkap);
  el.addEventListener('change', cekFormLengkap);
});

</script>


