@extends('layouts.sidebar-mahasiswa')

@section('title', 'Ajukan Peminjaman')

@section('content')

@php
$adaErrorTahap2 = $errors->has('tgl_kegiatan') || $errors->has('waktu_mulai') || $errors->has('waktu_berakhir');
@endphp

<style>
  #step1,
  #step2 {
    display: none;
  }

  .active-step {
    display: block !important;
  }
</style>

<x-header title="Peminjaman" breadcrumb="Peminjaman > Mahasiswa - Ajukan Peminjaman" />

<form id="peminjamanForm" method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" enctype="multipart/form-data" onsubmit="return validateAndSubmit(event)">
  @csrf

  {{-- === Header Utama === --}}
  <div class="px-4 pt-2 pb-3 border-b border-gray-300 mb-4">
    <h2 class="text-lg font-semibold text-[#003366]">Form Pengajuan Peminjaman</h2>
  </div>

  {{-- === Tahap 1 === --}}
  <button type="button" onclick="toggleStep(1)" id="btn1"
    class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm text-[#003366]">
    <span class="font-semibold">Tahap 1 - Detail Kegiatan</span>
    <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div id="step1" class="bg-white border-t active-step">
    @php
      $fasilitasData = $fasilitasLainnya ?? collect();
    @endphp

    <x-form-peminjaman.tahap1 :fasilitasLainnya="$fasilitasData" />

  </div>

  {{-- === Tahap 2 === --}}
  <button type="button" id="btn2"
    class="step-toggle w-full flex items-center justify-between px-4 py-3 text-sm text-[#003366]">
    <span class="font-semibold">Tahap 2 - Detail Kegiatan</span>
    <svg class="w-4 h-4 icon-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div id="step2" class="bg-white border-t p-4 space-y-4">
    <x-form-peminjaman.tahap2 />

    {{-- Error validasi waktu --}}
    @if ($errors->has('tgl_kegiatan'))
    <p class="text-red-600 text-sm mt-1">{{ $errors->first('tgl_kegiatan') }}</p>
    @endif
    @if ($errors->has('waktu_mulai'))
    <p class="text-red-600 text-sm mt-1">{{ $errors->first('waktu_mulai') }}</p>
    @endif
    @if ($errors->has('waktu_berakhir'))
    <p class="text-red-600 text-sm mt-1">{{ $errors->first('waktu_berakhir') }}</p>
    @endif

    <!-- {{-- Error umum lain --}}
    @foreach ($errors->getMessages() as $field => $messages)
    @if (!in_array($field, ['tgl_kegiatan', 'waktu_mulai', 'waktu_berakhir']))
    @foreach ($messages as $message)
    <div class="text-red-600 text-sm mt-1">- {{ $message }}</div>
    @endforeach
    @endif
    @endforeach -->
  </div>
</form>

<script>
  function toggleStep(step) {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');
    const validasiForm = document.getElementById('validasi-form');

    [step1, step2].forEach(s => s.classList.remove('active-step'));
    [btn1, btn2].forEach(b => b.classList.remove('bg-green-100', 'font-semibold'));

    if (step === 1) {
      step1.classList.add('active-step');
      btn1.classList.add('bg-green-100', 'font-semibold');
    }

    if (step === 2) {
      const tahap1Fields = step1.querySelectorAll('[required]');
      let valid = true;

      tahap1Fields.forEach(field => {
        if (!field.value) {
          valid = false;
          field.classList.add('border-red-500');
        } else {
          field.classList.remove('border-red-500');
        }
      });

      if (!valid) {
        validasiForm.textContent = "⚠️ Mohon lengkapi semua kolom di Tahap 1 terlebih dahulu.";
        validasiForm.classList.remove('hidden');
        toggleStep(1);
        return;
      }

      validasiForm.classList.add('hidden');
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
    @if($adaErrorTahap2)
    toggleStep(2);
    @else
    toggleStep(1);
    @endif

    // Klik Tahap 1
    document.getElementById('btn1')?.addEventListener('click', () => toggleStep(1));

    // Klik Tahap 2 dengan validasi
    document.getElementById('btn2')?.addEventListener('click', (e) => {
      const tahap1Fields = document.querySelectorAll('#step1 [required]');
      let valid = true;

      tahap1Fields.forEach(field => {
        if (!field.value) {
          valid = false;
          field.classList.add('border-red-500');
        } else {
          field.classList.remove('border-red-500');
        }
      });

      const validasiForm = document.getElementById('validasi-form');
      if (!valid) {
        toggleStep(1);
        validasiForm.textContent = "⚠️ Mohon lengkapi semua kolom di Tahap 1 terlebih dahulu.";
        validasiForm.classList.remove('hidden');
        return;
      }

      validasiForm.classList.add('hidden');
      toggleStep(2);
    });

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

@endsection