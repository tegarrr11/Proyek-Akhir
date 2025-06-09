@extends('layouts.sidebar-mahasiswa')

@section('title', 'Ajukan Peminjaman')

@section('content')

<style>
  #step1, #step2 {
    display: none;
  }
  .active-step {
    display: block !important;
  }
</style>

<x-header title="Peminjaman" breadcrumb="Peminjaman > Ajukan Peminjaman" />

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
    <x-form-peminjaman.tahap1 />
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

    // Jika semua valid, biarkan form disubmit dengan cara normal agar HTML5 "required" bekerja
    return true;
  }

  document.addEventListener('DOMContentLoaded', () => {
    toggleStep(1);

    document.getElementById('btn1')?.addEventListener('click', () => toggleStep(1));
    document.getElementById('btn2')?.addEventListener('click', () => toggleStep(2));

    // Eksternal check
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
