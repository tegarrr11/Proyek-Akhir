<?php $__env->startSection('title', 'Ajukan Peminjaman'); ?>

<?php $__env->startSection('content'); ?>

<?php
$adaErrorTahap2 = $errors->has('tgl_kegiatan') || $errors->has('waktu_mulai') || $errors->has('waktu_berakhir');
?>

<style>
  #step1,
  #step2 {
    display: none;
  }

  .active-step {
    display: block !important;
  }
</style>

<?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => ['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Mahasiswa - Ajukan Peminjaman']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Mahasiswa - Ajukan Peminjaman']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>

<form id="peminjamanForm" method="POST" action="<?php echo e(route('mahasiswa.peminjaman.store')); ?>" enctype="multipart/form-data" onsubmit="return validateAndSubmit(event)">
  <?php echo csrf_field(); ?>

  
  <div class="px-4 pt-2 pb-3 border-b border-gray-300 mb-4">
    <h2 class="text-lg font-semibold text-[#003366]">Form Pengajuan Peminjaman</h2>
  </div>

  
  <button type="button" onclick="toggleStep(1)" id="btn1"
    class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm text-[#003366]">
    <span class="font-semibold">Tahap 1 - Detail Kegiatan</span>
    <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div id="step1" class="bg-white border-t active-step">
    <?php
      $fasilitasData = $fasilitasLainnya ?? collect();
    ?>

    <?php if (isset($component)) { $__componentOriginal7258606b4ba94c28952e37259d97a7b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7258606b4ba94c28952e37259d97a7b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-peminjaman.tahap1','data' => ['fasilitasLainnya' => $fasilitasData]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-peminjaman.tahap1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['fasilitasLainnya' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($fasilitasData)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7258606b4ba94c28952e37259d97a7b6)): ?>
<?php $attributes = $__attributesOriginal7258606b4ba94c28952e37259d97a7b6; ?>
<?php unset($__attributesOriginal7258606b4ba94c28952e37259d97a7b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7258606b4ba94c28952e37259d97a7b6)): ?>
<?php $component = $__componentOriginal7258606b4ba94c28952e37259d97a7b6; ?>
<?php unset($__componentOriginal7258606b4ba94c28952e37259d97a7b6); ?>
<?php endif; ?>

  </div>

  
  <button type="button" id="btn2"
    class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm text-[#003366]">
    <span class="font-semibold">Tahap 2 - Detail Kegiatan</span>
    <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div id="step2" class="bg-white border-t p-4 space-y-4">
    <?php if (isset($component)) { $__componentOriginal69480859ebe18e639c06f7aba7b74929 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69480859ebe18e639c06f7aba7b74929 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-peminjaman.tahap2','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-peminjaman.tahap2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69480859ebe18e639c06f7aba7b74929)): ?>
<?php $attributes = $__attributesOriginal69480859ebe18e639c06f7aba7b74929; ?>
<?php unset($__attributesOriginal69480859ebe18e639c06f7aba7b74929); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69480859ebe18e639c06f7aba7b74929)): ?>
<?php $component = $__componentOriginal69480859ebe18e639c06f7aba7b74929; ?>
<?php unset($__componentOriginal69480859ebe18e639c06f7aba7b74929); ?>
<?php endif; ?>

    
    <?php if($errors->has('tgl_kegiatan')): ?>
    <p class="text-red-600 text-sm mt-1"><?php echo e($errors->first('tgl_kegiatan')); ?></p>
    <?php endif; ?>
    <?php if($errors->has('waktu_mulai')): ?>
    <p class="text-red-600 text-sm mt-1"><?php echo e($errors->first('waktu_mulai')); ?></p>
    <?php endif; ?>
    <?php if($errors->has('waktu_berakhir')): ?>
    <p class="text-red-600 text-sm mt-1"><?php echo e($errors->first('waktu_berakhir')); ?></p>
    <?php endif; ?>

    <!-- 
    <?php $__currentLoopData = $errors->getMessages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $messages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(!in_array($field, ['tgl_kegiatan', 'waktu_mulai', 'waktu_berakhir'])): ?>
    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="text-red-600 text-sm mt-1">- <?php echo e($message); ?></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> -->
  </div>
</form>

<script>
  function toggleStep(step) {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');

    [step1, step2].forEach(s => s.classList.remove('active-step'));
    [btn1, btn2].forEach(b => b.classList.remove('bg-green-100', 'font-semibold'));

    if (step === 1) {
      step1.classList.add('active-step');
      btn1.classList.add('bg-green-100', 'font-semibold');
    } else {
      step2.classList.add('active-step');
      btn2.classList.add('bg-green-100', 'font-semibold');
    }
  }

  function validateAndSubmit(event) {
    const form = document.getElementById('peminjamanForm');
    const validasiForm = document.getElementById('validasi-form');
    validasiForm.classList.add('hidden');
    validasiForm.textContent = "";

    const requiredFields = form.querySelectorAll('[required]');
    let firstInvalid = null;

    for (const field of requiredFields) {
      if (!field.value) {
        if (!firstInvalid) firstInvalid = field;
        field.classList.add('border-red-500');
      } else {
        field.classList.remove('border-red-500');
      }
    }

    if (firstInvalid) {
      const parent = firstInvalid.closest('#step1') || firstInvalid.closest('#step2');
      if (parent?.id === 'step1') toggleStep(1);
      if (parent?.id === 'step2') toggleStep(2);
      firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      firstInvalid.focus();

      validasiForm.textContent = " Mohon lengkapi semua kolom sebelum menyimpan.";
      validasiForm.classList.remove('hidden');
      return false;
    }

    // Validasi waktu kegiatan tidak boleh masa lampau
    const tanggal = form.querySelector('input[name="tgl_kegiatan"]').value;
    const waktuMulai = form.querySelector('input[name="waktu_mulai"]').value;

    if (tanggal && waktuMulai) {
      const now = new Date();
      const waktuDipilih = new Date(`${tanggal}T${waktuMulai}`);
      now.setSeconds(0, 0);

      if (waktuDipilih < now) {
        validasiForm.textContent = "⚠️ Tidak dapat mengajukan peminjaman untuk waktu yang sudah lewat.";
        validasiForm.classList.remove('hidden');
        toggleStep(2);
        form.querySelector('input[name="tgl_kegiatan"]').focus();
        return false;
      }
    }

    return true;
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Tetap di Tahap 2 jika error waktu
    <?php if($adaErrorTahap2): ?>
    toggleStep(2);
    <?php else: ?>
    toggleStep(1);
    <?php endif; ?>

    document.getElementById('btn1')?.addEventListener('click', () => toggleStep(1));
    document.getElementById('btn2')?.addEventListener('click', () => toggleStep(2));

    // Jenis kegiatan eksternal check
    const jenisKegiatanRadios = document.querySelectorAll('input[name="jenis_kegiatan"]');
    const undanganSection = document.getElementById('undangan-wrapper');
    const updateUndanganVisibility = () => {
      const selected = document.querySelector('input[name="jenis_kegiatan"]:checked');
      if (!selected || !undanganSection) return;
      undanganSection.classList.toggle('hidden', selected.value !== 'eksternal');
    };

    jenisKegiatanRadios.forEach(radio => {
      radio.addEventListener('change', updateUndanganVisibility);
    });

    updateUndanganVisibility();
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/mahasiswa/peminjaman/create.blade.php ENDPATH**/ ?>