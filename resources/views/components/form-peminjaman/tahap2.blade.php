@props(['fasilitasList'])
<form>
{{-- Judul Kegiatan --}}
  <div>
    <label class="block text-sm font-medium mb-1">Judul Kegiatan *</label>
    <input type="text" name="judul_kegiatan" class="w-full border rounded px-3 py-2" required>
  </div>

  {{-- Waktu Kegiatan --}}
  <div>
    <label class="block text-sm font-medium mb-1">Waktu Kegiatan *</label>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
      <input type="date" class="border rounded px-2 py-1 w-full" name="tgl_kegiatan" required>
      <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_mulai" required>
      <span class="text-sm text-center">s/d</span>
      <input type="time" class="border rounded px-2 py-1 w-full" name="waktu_berakhir" required>
    </div>
  </div>

  {{-- Aktivitas --}}
  <div>
    <label class="block text-sm font-medium mb-1">Aktivitas *</label>
    <input type="text" name="aktivitas" class="w-full border rounded px-3 py-2" required>
  </div>

  {{-- Organisasi --}}
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

  {{-- Penanggung Jawab --}}
  <div>
    <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
    <select class="w-full border rounded px-3 py-2" name="penanggung_jawab" required>
      <option value="">Pilih penanggung jawab...</option>
      <option value="AAZ - Alvin Alvarez">AAZ - Alvin Alvarez</option>
      <option value="JKT - Jessica Kartika">JKT - Jessica Kartika</option>
      <option value="FZN - Fajar Zainuddin">FZN - Fajar Zainuddin</option>
    </select>
  </div>

  {{-- Keterangan --}}
  <div>
    <label class="block text-sm font-medium mb-1">Keterangan *</label>
    <textarea name="deskripsi_kegiatan" class="w-full border rounded px-3 py-2" rows="3" placeholder="Penjelasan singkat kegiatan" required></textarea>
  </div>

  {{-- Upload Proposal --}}
  <div>
    <label class="block text-sm font-medium mb-1">Lampirkan Proposal (PDF) *</label>
    <input type="file" name="proposal" class="w-full border rounded px-3 py-2" accept="application/pdf" required>
  </div>

  {{-- Upload Surat Undangan Pembicara (Jika eksternal) --}}
  <div id="undangan-wrapper" class="hidden">
    <label class="block text-sm font-medium mb-1">Surat Undangan Pembicara (PDF)</label>
    <input type="file" name="undangan_pembicara" class="w-full border rounded px-3 py-2" accept="application/pdf">
  </div>

  <div class="flex justify-end items-center gap-4 mt-4">
    <div id="validasi-form" class="text-red-600 text-sm hidden">
      ⚠️ Mohon lengkapi semua kolom sebelum menyimpan.
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

