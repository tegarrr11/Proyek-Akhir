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
  <div>
    <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
      <input type="date" name="tgl_kegiatan" value="{{ old('tgl_kegiatan') }}"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
      <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
      <span class="text-sm text-center">s/d</span>
      <input type="time" name="waktu_berakhir" value="{{ old('waktu_berakhir') }}"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
    </div>

    {{-- Error --}}
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
  <div>
    <label class="block text-sm font-medium mb-1">Organisasi *</label>
    <select id="organisasiSelect" name="organisasi" class="w-full select2" required>
      <option value="">Pilih organisasi</option>
      @foreach ([
        "AET", "ITSA", "HIMASISTIFO", "HIMATRIK", "HMM", "HIMAKSI", "HIMATEL", "HIMIKA", "HIMAKOM", "HIMATRON",
        "UKM Basket", "UKM Futsal", "UKM Volly", "UKM Badminton", "PCR-Rohil", "PCR-Sumbar"
      ] as $org)
        <option value="{{ $org }}" {{ old('organisasi') == $org ? 'selected' : '' }}>{{ $org }}</option>
      @endforeach
    </select>
  </div>

  {{-- Penanggung Jawab --}}
  <div>
    <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
    <select id="penanggungSelect" name="penanggung_jawab" class="w-full select2" required>
      <option value="">Pilih atau cari penanggung jawab...</option>
    </select>
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
      class="bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">
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
</script>
