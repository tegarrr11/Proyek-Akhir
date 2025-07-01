<!-- Tambahkan ini di bagian <head> halaman -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Samakan tinggi, padding, dan border Select2 dengan input Tailwind */
  .select2-container .select2-selection--single {
    height: 42px !important;
    padding: 6px 12px !important;
    border: 1px solid #6b7280 !important; /* Tailwind gray-500 */
    border-radius: 0.375rem !important;    /* rounded-md */
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
  
  <div>
    <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
    <input type="text" name="judul_kegiatan" class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
      <input type="date" class="border border-gray-500 rounded px-2 py-1 w-full" name="tgl_kegiatan" required>
      <input type="time" class="border border-gray-500 rounded px-2 py-1 w-full" name="waktu_mulai" required>
      <span class="text-sm text-center">s/d</span>
      <input type="time" class="border border-gray-500 rounded px-2 py-1 w-full" name="waktu_berakhir" required>
    </div>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Aktivitas *</label>
    <input type="text" name="aktivitas" class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Organisasi *</label>
    <select id="organisasiSelect" name="organisasi" class="w-full select2" required>
      <option value="">Pilih organisasi</option>
    </select>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
    <select id="penanggungSelect" name="penanggung_jawab" class="w-full select2" required>
      <option value="">Pilih penanggung jawab...</option>
    </select>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Keterangan *</label>
    <textarea name="deskripsi_kegiatan" class="w-full border border-gray-500 rounded px-3 py-2" rows="3" placeholder="Penjelasan singkat kegiatan" required></textarea>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Lampirkan Proposal (PDF) *</label>
    <input type="file" name="proposal" class="w-full border border-gray-500 rounded px-3 py-2" accept="application/pdf" required>
  </div>

  
  <div id="undangan-wrapper" class="hidden">
    <label class="block text-sm font-medium mb-1">Surat Undangan Pembicara (PDF)</label>
    <input type="file" name="undangan_pembicara" class="w-full border border-gray-500 rounded px-3 py-2" accept="application/pdf">
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

<!-- Tambahkan ini sebelum </body> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  const penanggungJawabList = {
    "AAZ - Alvin Alvarez": "AAZ - Alvin Alvarez",
    "JKT - Jessica Kartika": "JKT - Jessica Kartika",
    "FZN - Fajar Zainuddin": "FZN - Fajar Zainuddin",
    "IDI - Indah Lestari": "IDI - Indah Lestari",
    "DDS - Dadang Syarif Sihabudin Sahid": "DDS - Dadang Syarif Sihabudin Sahid",
    "SPA - Satria Perdana Arifin": "SPA - Satria Perdana Arifin",
    "AGW - Agus Wijayanto": "AGW - Agus Wijayanto",
    "YAS - Yoanda Alim Syahbana": "YAS - Yoanda Alim Syahbana",
    "YDL - Yohana Dewi Lulu": "YDL - Yohana Dewi Lulu",
    "JNS - Juni Nurma Sari": "JNS - Juni Nurma Sari"
  };

  const organisasiList = [
    "AET",
    "ITSA",
    "HIMASISTIFO",
    "HIMATRIK",
    "HMM",
    "HIMAKSI",
    "HIMATEL",
    "HIMIKA",
    "HIMAKOM",
    "HIMATRON",
    "UKM Basket",
    "UKM Futsal",
    "UKM Volly",
    "UKM Badminton",
    "PCR-Rohil",
    "PCR-Sumbar",
  ];

  const penanggungSelect = document.getElementById('penanggungSelect');
  Object.entries(penanggungJawabList).forEach(([value, label]) => {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = label;
    penanggungSelect.appendChild(option);
  });

  const organisasiSelect = document.getElementById('organisasiSelect');
  organisasiList.forEach(org => {
    const option = document.createElement('option');
    option.value = org;
    option.textContent = org;
    organisasiSelect.appendChild(option);
  });

  $(document).ready(function () {
    $('#organisasiSelect').select2({
      width: '100%',
      placeholder: "Pilih organisasi",
      dropdownAutoWidth: true
    });

    $('#penanggungSelect').select2({
      width: '100%',
      placeholder: "Pilih penanggung jawab...",
      dropdownAutoWidth: true
    });
  });
</script><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/form-peminjaman/tahap2.blade.php ENDPATH**/ ?>