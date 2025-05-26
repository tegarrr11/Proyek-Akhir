<?php

use App\Models\Gedung;
use App\Models\Fasilitas;

$gedungSlug = strtolower($_GET['gedung'] ?? '');
$gedung = Gedung::where('slug', $gedungSlug)->first();
$fasilitasList = [];

if ($gedung) {
    $fasilitasList = Fasilitas::where('gedung_id', $gedung->id)
        ->where('stok', '>', 0)
        ->get();
}
?>

<x-header title="Fasilitas" breadcrumb="Fasilitas > Ajukan Peminjaman" />

<form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" enctype="multipart/form-data" class="bg-white rounded-md shadow-md p-6">
    @csrf

    <h2 class="text-xl font-semibold text-[#003366] mb-2">Form Pengajuan Peminjaman</h2>
    <hr class="border-t border-gray-300 mb-4">

    <style>
        #step1, #step2 {
            display: none;
        }
        .active-step {
            display: block !important;
        }
    </style>

    <script>
        function toggleStep(stepNumber) {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const btn1 = document.getElementById('btn1');
            const btn2 = document.getElementById('btn2');

            if (stepNumber === 1) {
                step1.classList.add('active-step');
                step2.classList.remove('active-step');
                btn1.classList.add('bg-cyan-100', 'text-[#003366]');
                btn1.classList.remove('bg-gray-100', 'text-gray-700');
                btn2.classList.add('bg-gray-100', 'text-gray-700');
                btn2.classList.remove('bg-cyan-100', 'text-[#003366]');
            } else {
                step2.classList.add('active-step');
                step1.classList.remove('active-step');
                btn2.classList.add('bg-cyan-100', 'text-[#003366]');
                btn2.classList.remove('bg-gray-100', 'text-gray-700');
                btn1.classList.add('bg-gray-100', 'text-gray-700');
                btn1.classList.remove('bg-cyan-100', 'text-[#003366]');
            }
        }
        window.addEventListener('DOMContentLoaded', () => {
            const step2 = document.getElementById('step2');
            const errorExists = step2.querySelector('.required');

            // Jika ada error di tahap 2 atau submit gagal, tetap tampilkan step 2
            if (errorExists) {
                toggleStep(2);
            } else {
                toggleStep(1);
            }
        });

    </script>

    <div class="mb-6 border rounded overflow-hidden">

        <button type="button" onclick="toggleStep(1)" id="btn1"
            class="w-full flex items-center justify-between px-4 py-3 font-semibold text-sm">
            <span>Tahap 1 - Fasilitas</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="step1" class="bg-white border-t p-4 space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Ruangan *</label>
                <select class="w-full border rounded px-3 py-2" name="gedung" onchange="window.location.href='?gedung=' + this.value;">
                    <option value="">-- Pilih Ruangan --</option>
                    <option value="auditorium" {{ request('gedung') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                    <option value="gsg" {{ request('gedung') == 'gsg' ? 'selected' : '' }}>Main Hall GSG</option>
                    <option value="gor" {{ request('gedung') == 'gor' ? 'selected' : '' }}>GOR</option>
                </select>
            </div>

            @if (!empty($fasilitasList))
            <div>
                <label class="block mb-1 text-sm font-medium">Barang dan Perlengkapan *</label>
                <table class="w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">No</th>
                            <th class="border px-2 py-1">Nama Barang</th>
                            <th class="border px-2 py-1">Jumlah</th>
                            <th class="border px-2 py-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="fasilitas-body">
                        @foreach ($fasilitasList as $index => $item)
                        <tr data-index="{{ $index }}">
                            <td class="border px-2 text-center">{{ $index + 1 }}</td>
                            <td class="border px-2">
                                {{ $item->nama_barang }}
                                <input type="hidden" name="barang[{{ $index }}][id]" value="{{ $item->id }}">
                            </td>
                            <td class="border px-2 text-center">
                                <input type="number"
                                      name="barang[{{ $index }}][jumlah]"
                                      class="jumlah-barang border rounded w-20 text-center"
                                      max="{{ $item->stok }}"
                                      value="{{ $item->stok }}"
                                      min="0"
                                      onchange="cekBatas()">
                                <small class="text-gray-400 block">Max: {{ $item->stok }}</small>
                            </td>
                            <td class="border px-2 text-center">
                                <button type="button" onclick="hapusBaris(this)" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 8h12M9 8v10m6-10v10M5 6h14l-1 14H6L5 6z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                  <div class="flex justify-between items-start mt-4">
                      <!-- Peringatan Validasi -->
                      <div id="peringatan" class="text-red-600 text-sm hidden">
                          ⚠️ Jumlah barang melebihi batas maksimal stok!
                      </div>

                      <!-- Tombol Selanjutnya -->
                      <div class="text-right w-full">
                          <button type="button" onclick="lanjutTahap2()"
                              class="bg-[#003366] hover:bg-[#002244] text-white px-5 py-2 rounded text-sm float-right">
                              Selanjutnya
                          </button>
                      </div>
                  </div>
            </div>
            @endif
        </div>

        <div class="h-6"></div>

        <button type="button" onclick="toggleStep(2)" id="btn2"
            class="w-full flex items-center justify-between px-4 py-3 font-semibold text-sm">
            <span>Tahap 2 - Detail Kegiatan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="step2" class="bg-white border-t p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
                <input type="text" class="w-full border rounded px-3 py-2" name="judul_kegiatan" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Jenis Kegiatan *</label>
                <div class="flex gap-4">
                    <label><input type="radio" name="jenis_kegiatan" value="internal"> Internal</label>
                    <label><input type="radio" name="jenis_kegiatan" value="eksternal"> Eksternal</label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
                    <input type="date" class="border rounded px-2 py-1 w-full" name="tgl_kegiatan" required>
                    <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_mulai" required>
                    <span class="text-sm text-center">sd</span>
                    <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_berakhir">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Aktivitas *</label>
                <input type="text" class="w-full border rounded px-3 py-2" name="aktivitas" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Organisasi *</label>
                <select class="w-full border rounded px-3 py-2" name="organisasi" required>
                    <option>BEM</option>
                    <option>BLM</option>
                    <option>HIMA</option>
                    <option>UKM</option>
                    <option>KM</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
                <select class="w-full border rounded px-3 py-2" name="penanggung_jawab" required>
                    <option value="">Pilih penanggung jawab...</option>
                    <option value="AAZ - Alvin Alvarez">AAZ - Alvin Alvarez</option>
                    <option value="JKT - Jessica Kartika">JKT - Jessica Kartika</option>
                    <option value="FZN - Fajar Zainuddin">FZN - Fajar Zainuddin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Keterangan *</label>
                <textarea class="w-full border rounded px-3 py-2" name="deskripsi_kegiatan" rows="3" placeholder="Penjelasan singkat kegiatan" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Proposal (PDF) *</label>
                <input type="file" name="proposal" accept="application/pdf">
            </div>
            <div class="flex justify-end items-center mt-6 gap-4">
                <div id="validasi-form" class="text-red-600 text-sm hidden">
                    ⚠️ Mohon lengkapi semua kolom sebelum menyimpan.
                </div>

                <button id="btn-simpan" type="submit"
                    class="px-5 py-2 bg-green-400 text-white rounded text-sm opacity-60 cursor-not-allowed"
                    disabled>
                    Simpan
                </button>
            </div>
        </div>

    </div>
</form>
<script>
    function hapusBaris(button) {
        const row = button.closest('tr');
        row.remove();
        cekBatas();
    }

    function cekBatas() {
        let over = false;
        document.querySelectorAll('.jumlah-barang').forEach(input => {
            const max = parseInt(input.max);
            const val = parseInt(input.value);
            if (val > max) over = true;
        });

        const peringatan = document.getElementById('peringatan');
        peringatan.classList.toggle('hidden', !over);
    }

    function lanjutTahap2() {
        cekBatas();
        if (!document.getElementById('peringatan').classList.contains('hidden')) {
            alert("Periksa jumlah barang! Ada yang melebihi batas stok.");
            return;
        }
        toggleStep(2);
        setTimeout(cekFormLengkap, 200);
    }

    const form = document.querySelector('form');
    const btnSimpan = document.getElementById('btn-simpan');
    const validasiMsg = document.getElementById('validasi-form');

    function cekFormLengkap() {
        let lengkap = true;
        document.querySelectorAll('#step2 label .required').forEach(el => el.remove());

        form.querySelectorAll('#step2 input, #step2 select, #step2 textarea').forEach(input => {
            const wrapper = input.closest('div');
            const label = wrapper ? wrapper.querySelector('label') : null;

            const isRadio = input.type === 'radio';
            const isFile = input.type === 'file';

            if (!input.disabled && ((input.type !== 'radio' && input.type !== 'file' && !input.value.trim()) ||
                (isFile && input.required && !input.files.length))) {
                lengkap = false;
                if (label && !label.querySelector('.required')) {
                    const span = document.createElement('span');
                    span.className = 'required text-red-500 ml-1';
                    span.textContent = '*';
                    label.appendChild(span);
                }
            }

            if (isRadio) {
                const group = form.querySelectorAll(`input[name="${input.name}"]`);
                const checked = Array.from(group).some(r => r.checked);
                if (!checked && label && !label.querySelector('.required')) {
                    lengkap = false;
                    const span = document.createElement('span');
                    span.className = 'required text-red-500 ml-1';
                    span.textContent = '*';
                    label.appendChild(span);
                }
            }
        });

        btnSimpan.disabled = !lengkap;
        btnSimpan.classList.toggle('opacity-60', !lengkap);
        btnSimpan.classList.toggle('cursor-not-allowed', !lengkap);
        btnSimpan.classList.toggle('bg-green-600', lengkap);
        btnSimpan.classList.toggle('bg-green-400', !lengkap);

        validasiMsg.classList.toggle('hidden', lengkap);

        if (!lengkap) {
            const firstInvalid = document.querySelector('#step2 input:invalid, #step2 select:invalid, #step2 textarea:invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    }

    document.querySelectorAll('#step2 input, #step2 select, #step2 textarea').forEach(el => {
        el.addEventListener('input', cekFormLengkap);
        el.addEventListener('change', cekFormLengkap);
    });

    form.addEventListener('submit', function (e) {
        cekFormLengkap();
        if (btnSimpan.disabled) {
            e.preventDefault();
            validasiMsg.classList.remove('hidden');
            toggleStep(2);
            document.getElementById('step2').scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>

