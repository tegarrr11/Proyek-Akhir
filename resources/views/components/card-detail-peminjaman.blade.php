<!-- resources/views/components/card-detail-peminjaman.blade.php -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600 hover:text-black text-xl font-bold">&times;</button>

    <h2 class="text-xl font-bold text-[#003366] mb-2">Detail Peminjaman</h2>
    <hr class="border-t border-gray-300 mb-4">

    <div class="flex flex-col md:flex-row gap-6">
      <div class="flex-1 space-y-4 text-sm">
        <div><p class="font-bold text-[#003366]">Judul Kegiatan</p><p id="judulKegiatan" class="text-gray-800">-</p></div>
        <div><p class="font-bold text-[#003366]">Waktu Kegiatan</p><p id="waktuKegiatan" class="text-gray-800">-</p></div>
        <div><p class="font-bold text-[#003366]">Aktivitas</p><p id="aktivitas" class="text-gray-800">-</p></div>
        <div class="grid grid-cols-2 gap-4">
          <div><p class="font-bold text-[#003366]">Organisasi</p><p id="organisasi" class="text-gray-800">-</p></div>
          <div><p class="font-bold text-[#003366]">Penanggung Jawab</p><p id="penanggungJawab" class="text-gray-800">-</p></div>
        </div>
        <div><p class="font-bold text-[#003366]">Keterangan</p><p id="keterangan" class="text-gray-800">-</p></div>
        <div>
          <p class="font-bold text-[#003366]">Dokumen</p>
          <a id="linkDokumen" href="#" target="_blank"
             class="inline-flex items-center px-4 py-1 border border-[#003366] rounded-md text-[#003366] font-medium hover:bg-[#003366] hover:text-white transition">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7v10M17 7v10M12 7v10M5 17h14"/>
            </svg> Download
          </a>
        </div>
        <div><p class="font-bold text-[#003366]">Ruangan</p><p id="ruangan" class="text-gray-800">-</p></div>
        <div>
          <p class="font-bold text-[#003366]">Perlengkapan</p>
          <ul id="perlengkapan" class="list-disc ml-5 text-gray-800 space-y-1"></ul>
        </div>
      </div>

      <!-- KANAN -->
      <div class="w-full md:w-1/3 space-y-3">
        <p class="font-bold text-[#003366]">Diskusi</p>
        <div id="diskusiArea" class="border rounded-md p-3 h-40 overflow-y-auto text-sm text-gray-600">belum ada diskusi</div>
        <div class="mt-2">
          <input type="text" placeholder="Ketikkan di sini" class="w-full border rounded-md px-3 py-1 text-sm" disabled>
          <button class="mt-2 px-4 py-1 bg-gray-300 text-gray-700 text-sm rounded-md cursor-not-allowed" disabled>Kirim</button>
        </div>
      </div>
    </div>

    <div class="flex justify-end mt-6">
      <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Tutup</button>
    </div>
  </div>
</div>
