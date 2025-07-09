@extends('layouts.sidebar-dosen')

@section('title', 'Ajukan Peminjaman (staff)')

@section('content')
<style>
  #step1, #step2 {
    display: none;
  }
  .active-step {
    display: block !important;
  }
</style>

<x-header title="Peminjaman" breadcrumb="Peminjaman > Staff - Ajukan Peminjaman" />

<form id="peminjamanForm" method="POST" action="{{ route('dosen.peminjaman.store') }}" onsubmit="return validateAndSubmit(event)">
  @csrf
  <div class="px-4 pt-2 pb-3 border-b border-gray-300 mb-4">
    <h2 class="text-lg font-semibold text-[#003366]">Form Pengajuan Peminjaman (Staff)</h2>
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
    <div>
      <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
      <input type="text" name="judul_kegiatan"
        value="{{ old('judul_kegiatan') }}"
        class="w-full border rounded px-3 py-2 @error('judul_kegiatan') border-red-500 @enderror" required>
      @error('judul_kegiatan')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
        <input type="date" name="tgl_kegiatan" value="{{ old('tgl_kegiatan') }}"
          class="border rounded px-2 py-1 w-full @error('tgl_kegiatan') border-red-500 @enderror" required>
        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
          class="border rounded px-2 py-1 w-full @error('waktu_mulai') border-red-500 @enderror" required>
        <span class="text-sm text-center">s/d</span>
        <input type="time" name="waktu_berakhir" value="{{ old('waktu_berakhir') }}"
          class="border rounded px-2 py-1 w-full @error('waktu_berakhir') border-red-500 @enderror" required>
      </div>
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

    <div>
      <label class="block text-sm font-medium mb-1">Aktivitas *</label>
      <input type="text" name="aktivitas"
        value="{{ old('aktivitas') }}"
        class="w-full border rounded px-3 py-2 @error('aktivitas') border-red-500 @enderror" required>
      @error('aktivitas')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Keterangan *</label>
      <textarea name="deskripsi_kegiatan" class="w-full border rounded px-3 py-2 @error('deskripsi_kegiatan') border-red-500 @enderror"
        rows="3" required>{{ old('deskripsi_kegiatan') }}</textarea>
      @error('deskripsi_kegiatan')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Organisasi *</label>
      <input type="text" name="organisasi" class="w-full border rounded px-3 py-2 bg-gray-100" value="Staff" readonly>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
      <input type="text" name="penanggung_jawab"
        value="{{ old('penanggung_jawab') }}"
        class="w-full border rounded px-3 py-2 @error('penanggung_jawab') border-red-500 @enderror" required>
      @error('penanggung_jawab')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>


    <div class="flex justify-end mt-4">
      <button id="btn-simpan" type="submit"
        class="bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">
        Simpan
      </button>
    </div>
  </div>
</form>

@php
  $errorStep2 = $errors->has('judul_kegiatan') || $errors->has('tgl_kegiatan') || $errors->has('waktu_mulai') ||
                $errors->has('waktu_berakhir') || $errors->has('aktivitas') || $errors->has('deskripsi_kegiatan') ||
                $errors->has('penanggung_jawab');
@endphp

@if ($errorStep2)
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      toggleStep(2);
    });
  </script>
@endif

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
@endsection
