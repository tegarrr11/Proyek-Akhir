@extends('layouts.sidebar-admin')

@section('title', 'Fasilitas')

@section('content')
<x-header title="Fasilitas" breadcrumb="Admin > Fasilitas" />

@if(session('success'))
  <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
    {{ session('success') }}
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

{{-- Form Tambah --}}
<div class="bg-white rounded-md shadow p-6 mb-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
    <h2 class="text-lg font-semibold">Tambah Fasilitas</h2>
    <form id="uploadExcelForm" action="{{ route('admin.fasilitas.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 md:mt-0 flex items-center gap-4">
      @csrf
      <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="hidden" onchange="document.getElementById('uploadExcelForm').submit()">
      <button type="button" onclick="document.getElementById('fileInput').click()" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Upload dari Excel</button>
      <a href="{{ asset('template_fasilitas.xlsx') }}" class="text-blue-600 text-sm underline hover:text-blue-800">Download Template</a>
    </form>
  </div>


  {{-- Form Manual --}}
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
      <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 text-sm">Simpan</button>
    </div>
  </form>

</div>


{{-- Daftar Fasilitas per Gedung --}}
<div class="bg-white rounded-md shadow p-6">
  <h2 class="text-xl font-semibold mb-4">Daftar Fasilitas per Gedung</h2>

  @forelse ($gedungs as $gedung)
    <div class="mb-6 border rounded">
      <div class="bg-gray-100 px-4 py-2 font-semibold">
        {{ $gedung->nama }}
      </div>

      @if($gedung->fasilitas->isEmpty())
        <div class="px-4 py-2 text-sm text-gray-500">Belum ada fasilitas.</div>
      @else
        <div class="overflow-x-auto">
          <table class="w-full table-auto text-sm">
<thead class="bg-gray-100 text-left">
  <tr>
    <th class="px-4 py-2 w-12">No.</th>
    <th class="px-4 py-2">Nama Fasilitas</th>
    <th class="px-4 py-2">Stok</th>
    <th class="px-4 py-2">Aksi</th>
  </tr>
</thead>
<tbody>
  @foreach($gedung->fasilitas as $index => $item)
    @php
      $isEditing = request('edit') == $item->id;
      $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-gray-50'; // warna selang-seling
    @endphp

    @if($isEditing)
      <form action="{{ route('admin.fasilitas.update', $item->id) }}" method="POST">
        @csrf @method('PUT')
        <input type="hidden" name="gedung_id" value="{{ $item->gedung_id }}">
        <tr class="{{ $rowClass }} border-t border-gray-200">
          <td class="px-4 py-2 align-middle">{{ $index + 1 }}</td>
          <td class="px-4 py-2 align-middle">
            <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" class="border px-2 py-1 rounded text-sm w-full" required>
          </td>
          <td class="px-4 py-2 align-middle">
            <input type="number" name="stok" value="{{ old('stok', $item->stok) }}" class="border px-2 py-1 rounded text-sm w-full" required>
          </td>
          <td class="px-4 py-2 align-middle">
            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-500">SIMPAN</button>
            <a href="{{ url()->current() }}" class="text-gray-600 text-xs ml-2 hover:underline">Batal</a>
          </td>
        </tr>
      </form>
    @else
      <tr class="{{ $rowClass }} border-t border-gray-200">
        <td class="px-4 py-2 align-middle">{{ $index + 1 }}</td>
        <td class="px-4 py-2 align-middle">{{ $item->nama_barang }}</td>
        <td class="px-4 py-2 align-middle">{{ $item->stok }}</td>
        <td class="px-4 py-2 align-middle">
          <div class="flex space-x-2">
            <a href="{{ url()->current() }}?edit={{ $item->id }}" class="text-blue-600 hover:text-blue-800">
              {{-- SVG Edit --}}
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#0071ff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.587 1.413T19 21zm4-6v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4t-.137.738t-.438.662L13.25 15zM21.025 4.4l-1.4-1.4zM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575zm6.5-6.5l-.725-.7zl.7.7z"/></svg>
            </a>
            <form action="{{ route('admin.fasilitas.destroy', $item->id) }}" method="POST" class="inline-block">
              @csrf
              @method('DELETE')
              <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-500 hover:text-red-700">
                {{-- SVG Trash --}}
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                    <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
                  </svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
    @endif
  @endforeach
</tbody>
          </table>
        </div>
      @endif
    </div>
  @empty
    <p class="text-sm text-gray-500">Tidak ada gedung yang tersedia.</p>
  @endforelse
</div>
@endsection
