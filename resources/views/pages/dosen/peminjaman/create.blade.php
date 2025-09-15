@extends('layouts.sidebar-dosen')

@section('title', 'Ajukan Peminjaman (Admin)')

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
    [btn1, btn2].forEach(b => b.classList.remove('bg-[#ccf3f9]', 'font-semibold'));
    if (step === 1) {
      step1.classList.add('active-step');
      btn1.classList.add('bg-[#ccf3f9]', 'font-semibold');
    } else {
      step2.classList.add('active-step');
      btn1.classList.add('bg-green-100', 'text-green-800');
      btn2.classList.add('bg-[#ccf3f9]', 'font-semibold');
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
        const penanggungInput = document.getElementById('penanggungInput');
    const penanggungList = document.getElementById('penanggungList');
    let allPegawai = [];

    // Fetch data dari API
    fetch('/pegawai/list')
      .then(res => res.json())
      .then(data => {
        if (!data.items) {
          console.error('Data pegawai tidak ditemukan.');
          return;
        }
        allPegawai = data.items.map(d => `${d.inisial} - ${d.nama}`);
      })
      .catch(err => console.error('Gagal memuat data pegawai:', err));

    // Tampilkan list saat focus/input
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

    document.addEventListener('click', function (e) {
      if (!penanggungInput.contains(e.target) && !penanggungList.contains(e.target)) {
        penanggungList.classList.add('hidden');
      }
    });
  });
</script>
@endsection
