<?php $__env->startSection('title', 'Ajukan Peminjaman (staff)'); ?>

<?php $__env->startSection('content'); ?>
<style>
  #step1, #step2 {
    display: none;
  }
  .active-step {
    display: block !important;
  }
</style>

<?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => ['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Staff - Ajukan Peminjaman']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Peminjaman','breadcrumb' => 'Peminjaman > Staff - Ajukan Peminjaman']); ?>
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

<form id="peminjamanForm" method="POST" action="<?php echo e(route('dosen.peminjaman.store')); ?>" onsubmit="return validateAndSubmit(event)">
  <?php echo csrf_field(); ?>
  <div class="px-4 pt-2 pb-3 border-b border-gray-300 mb-4">
    <h2 class="text-lg font-semibold text-[#003366]">Form Pengajuan Peminjaman (Staff)</h2>
  </div>

  
  <button type="button" onclick="toggleStep(1)" id="btn1"
    class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm text-[#003366]">
    <span class="font-semibold">Tahap 1 - Detail Kegiatan</span>
    <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div id="step1" class="bg-white border-t active-step">
    <?php if (isset($component)) { $__componentOriginal7258606b4ba94c28952e37259d97a7b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7258606b4ba94c28952e37259d97a7b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-peminjaman.tahap1','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-peminjaman.tahap1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
    <div>
      <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
      <input type="text" name="judul_kegiatan"
        value="<?php echo e(old('judul_kegiatan')); ?>"
        class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['judul_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
      <?php $__errorArgs = ['judul_kegiatan'];
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
      <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
        <input type="date" name="tgl_kegiatan" value="<?php echo e(old('tgl_kegiatan')); ?>"
          class="border rounded px-2 py-1 w-full <?php $__errorArgs = ['tgl_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
        <input type="time" name="waktu_mulai" value="<?php echo e(old('waktu_mulai')); ?>"
          class="border rounded px-2 py-1 w-full <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
        <span class="text-sm text-center">s/d</span>
        <input type="time" name="waktu_berakhir" value="<?php echo e(old('waktu_berakhir')); ?>"
          class="border rounded px-2 py-1 w-full <?php $__errorArgs = ['waktu_berakhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
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
      <input type="text" name="aktivitas"
        value="<?php echo e(old('aktivitas')); ?>"
        class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['aktivitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
      <?php $__errorArgs = ['aktivitas'];
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
      <label class="block text-sm font-medium mb-1">Keterangan *</label>
      <textarea name="deskripsi_kegiatan" class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['deskripsi_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        rows="3" required><?php echo e(old('deskripsi_kegiatan')); ?></textarea>
      <?php $__errorArgs = ['deskripsi_kegiatan'];
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
      <label class="block text-sm font-medium mb-1">Organisasi *</label>
      <input type="text" name="organisasi" class="w-full border rounded px-3 py-2 bg-gray-100" value="Staff" readonly>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
      <input type="text" name="penanggung_jawab"
        value="<?php echo e(old('penanggung_jawab')); ?>"
        class="w-full border rounded px-3 py-2 <?php $__errorArgs = ['penanggung_jawab'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
      <?php $__errorArgs = ['penanggung_jawab'];
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


    <div class="flex justify-end mt-4">
      <button id="btn-simpan" type="submit"
        class="bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">
        Simpan
      </button>
    </div>
  </div>
</form>

<?php
  $errorStep2 = $errors->has('judul_kegiatan') || $errors->has('tgl_kegiatan') || $errors->has('waktu_mulai') ||
                $errors->has('waktu_berakhir') || $errors->has('aktivitas') || $errors->has('deskripsi_kegiatan') ||
                $errors->has('penanggung_jawab');
?>

<?php if($errorStep2): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      toggleStep(2);
    });
  </script>
<?php endif; ?>

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
      return false;
    }
    return true;
  }

  document.addEventListener('DOMContentLoaded', () => {
    toggleStep(1);
    document.getElementById('btn1')?.addEventListener('click', () => toggleStep(1));
    document.getElementById('btn2')?.addEventListener('click', () => toggleStep(2));
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar-dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/pages/dosen/peminjaman/create.blade.php ENDPATH**/ ?>