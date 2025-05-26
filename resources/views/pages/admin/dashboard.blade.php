@extends('layouts.sidebar-admin')

@section('title', 'Dashboard Admin')

@section('content')
  <x-header title="Dashboard" breadcrumb="Dashboard > Ruangan" />

  <div class="inline-block bg-white rounded-md p-6 shadow">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ruangan</h2>

 <div class="flex flex-wrap gap-4">
      @forelse ($gedungs as $gedung)
        @if($gedung->slug === 'fasilitas-lainnya')
          @continue
        @endif

        <div class="w-64 bg-white border rounded-lg shadow hover:shadow-lg transition cursor-pointer" data-slug="{{ $gedung->slug }}">
          <img src="{{ asset('images/' . $gedung->slug . '.png') }}"
              alt="{{ $gedung->slug }}"
              class="w-full h-40 object-cover rounded-t-lg">
          <div class="p-4">
            <h3 class="text-lg font-bold">{{ $gedung->nama }}</h3>
            <p id="{{ $gedung->slug }}-deskripsi" class="text-sm text-gray-600">{{ $gedung->deskripsi }}</p>
            <p id="{{ $gedung->slug }}-kapasitas" class="text-sm text-gray-600">Kapasitas: {{ $gedung->kapasitas }} orang</p>
            <p id="{{ $gedung->slug }}-jam" class="text-sm text-gray-600">Jam Operasional: {{ $gedung->jam_operasional }}</p>
            <button onclick="editGedung('{{ $gedung->slug }}')" class="mt-3 text-blue-600 text-sm hover:underline">Edit</button>
          </div>
        </div>
      @empty
        <div class="text-gray-500">Tidak ada data ruangan.</div>
      @endforelse
</div>

  </div>

  <!-- Modal Edit -->
  <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-40 items-center justify-center z-30 hidden">
    <div class="bg-white w-[90%] sm:w-full max-w-sm p-4 sm:p-6 rounded-lg shadow">
      <h3 class="text-lg font-semibold mb-4">Edit Ruangan: <span id="form-slug-display"></span></h3>
      <form onsubmit="simpanEdit(event)">
        <input type="hidden" id="form-slug">
        <div class="space-y-4">
          <div>
            <label class="text-sm text-gray-700">Nama Ruangan</label>
            <input type="text" class="w-full border px-3 py-2 rounded mt-1" id="form-nama">
          </div>
          <div>
            <label class="text-sm text-gray-700">Deskripsi</label>
            <input type="text" class="w-full border px-3 py-2 rounded mt-1" id="form-desc">
          </div>
          <div>
            <label class="text-sm text-gray-700">Kapasitas</label>
            <input type="number" class="w-full border px-3 py-2 rounded mt-1" id="form-kapasitas" min="1">
          </div>
          <div class="flex gap-2 mt-1">
            <input type="time" class="border px-3 py-2 rounded w-full" id="form-jam-mulai">
            <span class="self-center">s/d</span>
            <input type="time" class="border px-3 py-2 rounded w-full" id="form-jam-selesai">
          </div>
        </div>
        <div class="mt-4 text-right">
          <button type="button" onclick="closeModal()" class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="success-toast" class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow-lg text-sm hidden z-50">
    Data berhasil disimpan!
  </div>
@endsection

@section('scripts')
<script>
  function editGedung(slug) {
    fetch(`/api/ruangan/${slug}`)
      .then(res => {
        if (!res.ok) throw new Error('Data tidak ditemukan');
        return res.json();
      })
      .then(data => {
        const jamParts = data.jam_operasional.split(' - ');
        const jamMulai = jamParts[0] || '';
        const jamSelesai = jamParts[1] || '';

        document.getElementById('form-slug').value = data.slug || '';
        document.getElementById('form-slug-display').innerText = data.slug || '';
        document.getElementById('form-nama').value = data.nama || '';
        document.getElementById('form-desc').value = data.deskripsi || '';
        document.getElementById('form-kapasitas').value = data.kapasitas || '';
        document.getElementById('form-jam-mulai').value = jamMulai;
        document.getElementById('form-jam-selesai').value = jamSelesai;

        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');
      })
      .catch(error => {
        console.error('Gagal mengambil data ruangan:', error);
        alert('Terjadi kesalahan saat mengambil data ruangan.');
      });
  }

  function closeModal() {
    document.getElementById('modalEdit').classList.remove('flex');
    document.getElementById('modalEdit').classList.add('hidden');
  }

  function showToast(message = 'Berhasil!') {
    const toast = document.getElementById('success-toast');
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => {
      toast.classList.add('hidden');
    }, 2000);
  }

  function simpanEdit(event) {
    event.preventDefault();

    const slug = document.getElementById('form-slug').value;
    const nama = document.getElementById('form-nama').value;
    const deskripsi = document.getElementById('form-desc').value;
    const kapasitas = parseInt(document.getElementById('form-kapasitas').value);
    const jamMulai = document.getElementById('form-jam-mulai').value;
    const jamSelesai = document.getElementById('form-jam-selesai').value;

    if (isNaN(kapasitas) || kapasitas <= 0) {
      alert('Kapasitas harus berupa angka lebih dari 0.');
      return;
    }

    fetch('/admin/ruangan/update', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        slug,
        nama,
        deskripsi,
        kapasitas,
        jam_mulai: jamMulai,
        jam_selesai: jamSelesai
      })
    })
    .then(res => {
      if (!res.ok) throw new Error('Gagal menyimpan data');
      return res.json();
    })
    .then(() => {
      document.querySelector(`[data-slug="${slug}"] h3`).innerText = nama;
      document.getElementById(`${slug}-deskripsi`).innerText = deskripsi;
      document.getElementById(`${slug}-kapasitas`).innerText = 'Kapasitas: ' + kapasitas + ' orang';
      document.getElementById(`${slug}-jam`).innerText = 'Jam Operasional: ' + jamMulai + ' - ' + jamSelesai;

      closeModal();
      showToast('Data berhasil disimpan!');
    })
    .catch(error => {
      console.error('Gagal menyimpan:', error);
      alert('Terjadi kesalahan saat menyimpan data.');
    });
  }
</script>
@endsection
