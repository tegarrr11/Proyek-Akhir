@extends('layouts.sidebar-admin')

@section('title', 'Fasilitas')

@section('content')
<x-header title="Fasilitas" breadcrumb="Admin > Fasilitas" />

@if(session('success'))
  <div   
    id="successToast"
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 2000)"
    x-show="show"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-6 right-6 z-50 flex items-center justify-between gap-4 bg-green-600 text-white px-4 py-2 rounded-md shadow-lg text-sm font-normal"
  >
    <div class="flex items-center gap-2">
      <div class="bg-white text-green-600 rounded-full p-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <span>{{ session('success') }}</span>
    </div>
    <button @click="show = false" class="text-white hover:text-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
@endif

@if($errors->any())
  <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
    <strong>Validasi Gagal:</strong>
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="bg-white rounded-md shadow !p-6 mb-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
    <h2 class="text-lg font-semibold">Tambah Fasilitas</h2>
    <form id="uploadExcelForm" action="{{ route('admin.fasilitas.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 md:mt-0 flex items-center gap-4">
      @csrf
      <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="hidden" onchange="document.getElementById('uploadExcelForm').submit()">
      <button type="button" onclick="document.getElementById('fileInput').click()" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Upload dari Excel</button>
      <a href="{{ asset('template_fasilitas.xlsx') }}" class="text-blue-600 text-sm underline hover:text-blue-800">Download Template</a>
    </form>
  </div>

  <form action="{{ route('admin.fasilitas.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label for="gedung_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gedung</label>
        <select name="gedung_id" id="gedung_id" class="w-full border rounded px-3 py-2 text-sm" required>
          <option value="">-- Pilih Gedung --</option>
          @foreach($gedungs as $gedung)
            <option value="{{ $gedung->id }}">{{ $gedung->nama }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
        <input type="text" name="nama_barang" id="nama_barang" class="w-full border rounded px-3 py-2 text-sm" placeholder="Contoh: Kursi" required>
      </div>
      <div>
        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
        <input type="number" name="stok" id="stok" class="w-full border rounded px-3 py-2 text-sm" placeholder="Contoh: 100" required>
      </div>
    </div>
    <div class="pt-2">
      <button type="submit" class="bg-[#003366] text-white px-5 py-2 rounded hover:bg-[#002244] text-sm">Simpan</button>
    </div>
  </form>
</div>

{{-- Komponen tab fasilitas per gedung --}}
<x-table-fasilitas :gedungs="$gedungs" />
@endsection
