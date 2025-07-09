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
  
  <div>
    <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
    <input type="text" name="judul_kegiatan" value="<?php echo e(old('judul_kegiatan')); ?>"
      class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
      <input type="date" name="tgl_kegiatan" value="<?php echo e(old('tgl_kegiatan')); ?>"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
      <input type="time" name="waktu_mulai" value="<?php echo e(old('waktu_mulai')); ?>"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
      <span class="text-sm text-center">s/d</span>
      <input type="time" name="waktu_berakhir" value="<?php echo e(old('waktu_berakhir')); ?>"
        class="border border-gray-500 rounded px-2 py-1 w-full" required>
    </div>

    
    <?php $__errorArgs = ['tgl_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php $__errorArgs = ['waktu_berakhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Aktivitas *</label>
    <input type="text" name="aktivitas" value="<?php echo e(old('aktivitas')); ?>"
      class="w-full border border-gray-500 rounded px-3 py-2" required>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Organisasi *</label>
    <select id="organisasiSelect" name="organisasi" class="w-full select2" required>
      <option value="">Pilih organisasi</option>
      <?php $__currentLoopData = [
        "AET", "ITSA", "HIMASISTIFO", "HIMATRIK", "HMM", "HIMAKSI", "HIMATEL", "HIMIKA", "HIMAKOM", "HIMATRON",
        "UKM Basket", "UKM Futsal", "UKM Volly", "UKM Badminton", "PCR-Rohil", "PCR-Sumbar", ""
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($org); ?>" <?php echo e(old('organisasi') == $org ? 'selected' : ''); ?>><?php echo e($org); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
    <select id="penanggungSelect" name="penanggung_jawab" class="w-full select2" required>
      <option value="">Pilih penanggung jawab...</option>
      <?php $__currentLoopData = [
        "AAZ - Alvin Alvarez", "JKT - Jessica Kartika", "FZN - Fajar Zainuddin", "IDI - Indah Lestari",
        "DDS - Dadang Syarif Sihabudin Sahid", "SPA - Satria Perdana Arifin", "AGW - Agus Wijayanto",
        "YAS - Yoanda Alim Syahbana", "YDL - Yohana Dewi Lulu", "JNS - Juni Nurma Sari"
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($pj); ?>" <?php echo e(old('penanggung_jawab') == $pj ? 'selected' : ''); ?>><?php echo e($pj); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Keterangan *</label>
    <textarea name="deskripsi_kegiatan" rows="3"
      class="w-full border border-gray-500 rounded px-3 py-2" required><?php echo e(old('deskripsi_kegiatan')); ?></textarea>
  </div>

  
  <div>
    <label class="block text-sm font-medium mb-1">Lampirkan Proposal (PDF) *</label>
    <input type="file" name="proposal"
      class="w-full border border-gray-500 rounded px-3 py-2" accept="application/pdf" required>
  </div>

  
  <div id="undangan-wrapper" class="<?php echo e(old('jenis_kegiatan') == 'eksternal' ? '' : 'hidden'); ?>">
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

  $(document).ready(function() {
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