<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .select2-container .select2-selection--single {
    height: 42px !important;
    padding: 6px 12px !important;
    border: 1px solid #6b7280 !important;
    border-radius: 0.375rem !important;
    display: flex !important;
    align-items: center !important;
    background-color: white;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    color: #111827;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
  }
  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 1px #2563eb !important;
  }
</style>

<form>
  {{-- Judul Kegiatan --}}
  <div>
    <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
    <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}"
      class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

  {{-- Waktu Kegiatan --}}
  <div class="mb-4">
    <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>

    {{-- Baris Tanggal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
      <div>
        <label class="block text-xs text-gray-600 mb-1">Tanggal Mulai</label>
        <input type="date" name="tgl_kegiatan" value="{{ old('tgl_kegiatan') }}"
          class="w-full border border-gray-500 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block text-xs text-gray-600 mb-1">Tanggal Berakhir</label>
        <input type="date" name="tgl_kegiatan_berakhir" value="{{ old('tgl_kegiatan_berakhir') }}"
          class="w-full border border-gray-500 rounded px-3 py-2" required>
      </div>
    </div>

    {{-- Baris Jam --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
      <div>
        <label class="block text-xs text-gray-600 mb-1">Jam Mulai</label>
        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
          class="w-full border border-gray-500 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block text-xs text-gray-600 mb-1">Jam Berakhir</label>
        <input type="time" name="waktu_berakhir" value="{{ old('waktu_berakhir') }}"
          class="w-full border border-gray-500 rounded px-3 py-2" required>
      </div>
    </div>

    {{-- Error Message --}}
    @error('tgl_kegiatan')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @error('waktu_mulai')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @error('waktu_berakhir')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  {{-- Aktivitas --}}
  <div>
    <label class="block text-sm font-medium mb-1">Aktivitas *</label>
    <input type="text" name="aktivitas" value="{{ old('aktivitas') }}"
      class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

{{-- Organisasi --}}
<div class="relative">
  <label class="block text-sm font-medium mb-1">Organisasi *</label>
  <input type="text" id="organisasiInput" name="organisasi"
         value="{{ old('organisasi') }}"
         class="w-full border border-gray-500 rounded px-3 py-2" required
         autocomplete="off">
  <div id="organisasiList"
       class="absolute hidden border border-gray-300 mt-1 rounded shadow max-h-[7.5rem] overflow-y-auto bg-white z-50 w-full text-sm">
  </div>
</div>

{{-- Penanggung Jawab --}}
<div class="relative">
  <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
  <input type="text" id="penanggungInput" name="penanggung_jawab"
         value="{{ old('penanggung_jawab') }}"
         class="w-full border border-gray-500 rounded px-3 py-2" required
         autocomplete="off">
  <div id="penanggungList"
       class="absolute hidden border border-gray-300 mt-1 rounded shadow max-h-[7.5rem] overflow-y-auto bg-white z-50 w-full text-sm">
  </div>
</div>

  {{-- Keterangan --}}
  <div>
    <label class="block text-sm font-medium mb-1">Keterangan *</label>
    <textarea name="deskripsi_kegiatan" rows="3"
      class="w-full border border-gray-500 rounded px-3 py-2" required>{{ old('deskripsi_kegiatan') }}</textarea>
  </div>

  {{-- Upload Proposal --}}
  <div>
    <label class="block text-sm font-medium mb-1">Lampirkan Proposal (PDF) *</label>
    <input type="file" name="proposal"
      class="w-full border border-gray-500 rounded px-3 py-2" accept="application/pdf" required>
  </div>

  {{-- Upload Surat Undangan Pembicara (Jika eksternal) --}}
  <div id="undangan-wrapper" class="{{ old('jenis_kegiatan') == 'eksternal' ? '' : 'hidden' }}">
    <label class="block text-sm font-medium mb-1">Surat Undangan Pembicara (PDF)</label>
    <input type="file" name="undangan_pembicara"
      class="w-full border border-gray-500 rounded px-3 py-2" accept="application/pdf">
  </div>

  <div class="flex justify-end items-center gap-4 mt-4">
    <div id="validasi-form" class="text-red-600 text-sm hidden">
      ⚠️ Mohon lengkapi semua kolom sebelum menyimpan.
    </div>
  </div>

  <div class="flex justify-end mt-4">
      <button id="btn-simpan" type="submit"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">       
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Simpan
      </button>
  </div>
</form>


<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Load pegawai dari endpoint Laravel (proxy ke API PCR)
    $.getJSON('/pegawai/list', function(data) {
        if (!data.items) {
            alert("Gagal memuat data pegawai dari API.");
            return;
        }

        data.items.forEach(function(peg) {
            $('#penanggungSelect').append(
                $('<option>', {
                    value: peg.inisial + ' - ' + peg.nama,
                    text: peg.inisial + ' - ' + peg.nama
                })
            );
        });

        $('#penanggungSelect').select2({
            width: '100%',
            placeholder: "Pilih atau cari penanggung jawab...",
            dropdownAutoWidth: true
        });

        $('#organisasiSelect').select2({
            width: '100%',
            placeholder: "Pilih organisasi",
            dropdownAutoWidth: true
        });
    });
});

  const organisasiData = [
    "AET", "ITSA", "HIMASISTIFO", "HIMATRIK", "HMM", "HIMAKSI", "HIMATEL", "HIMIKA", "HIMAKOM", "HIMATRON",
    "UKM Basket", "UKM Futsal", "UKM Volly", "UKM Badminton", "PCR-Rohil", "PCR-Sumbar","BEM","BLM"
  ];

  const organisasiInput = document.getElementById('organisasiInput');
  const organisasiList = document.getElementById('organisasiList');

  organisasiInput.addEventListener('focus', showOrganisasiList);
  organisasiInput.addEventListener('input', showOrganisasiList);

  function showOrganisasiList() {
    const keyword = organisasiInput.value.toLowerCase();
    const filtered = organisasiData.filter(name => name.toLowerCase().includes(keyword));

    organisasiList.innerHTML = '';
    filtered.slice(0, 50).forEach((name, i) => {
      const div = document.createElement('div');
      div.textContent = name;
      div.className = 'cursor-pointer px-3 py-1 hover:bg-gray-100';
      div.onclick = () => {
        organisasiInput.value = name;
        organisasiList.classList.add('hidden');
      };
      organisasiList.appendChild(div);
    });

    organisasiList.classList.toggle('hidden', filtered.length === 0);
  }

  document.addEventListener('click', function(e) {
    if (!organisasiInput.contains(e.target) && !organisasiList.contains(e.target)) {
      organisasiList.classList.add('hidden');
    }
  });

  // Penanggung jawab
  const penanggungInput = document.getElementById('penanggungInput');
  const penanggungList = document.getElementById('penanggungList');
  let allPegawai = [];

  fetch('/pegawai/list')
    .then(res => res.json())
    .then(data => {
      allPegawai = data.items.map(d => `${d.inisial} - ${d.nama}`);
    });

  penanggungInput.addEventListener('focus', showPenanggungList);
  penanggungInput.addEventListener('input', showPenanggungList);

  function showPenanggungList() {
    const keyword = penanggungInput.value.toLowerCase();
    const filtered = allPegawai.filter(name => name.toLowerCase().includes(keyword));

    penanggungList.innerHTML = '';
    filtered.slice(0, 50).forEach(name => {
      const div = document.createElement('div');
      div.textContent = name;
      div.className = 'cursor-pointer px-3 py-1 hover:bg-gray-100';
      div.onclick = () => {
        penanggungInput.value = name;
        penanggungList.classList.add('hidden');
      };
      penanggungList.appendChild(div);
    });

    penanggungList.classList.toggle('hidden', filtered.length === 0);
  }

  document.addEventListener('click', function(e) {
    if (!penanggungInput.contains(e.target) && !penanggungList.contains(e.target)) {
      penanggungList.classList.add('hidden');
    }
  });
</script>
