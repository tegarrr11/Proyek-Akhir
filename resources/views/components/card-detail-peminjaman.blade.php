<!-- resources/views/auth/components/card-detail-peminjaman.blade.php -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]">
    <button onclick="closeModal()" class="absolute top-3 right-4 text-gray-600 hover:text-black text-xl font-bold">&times;</button>
    
    <h2 class="text-xl font-semibold mb-4">Detail Peminjaman</h2>

    <div id="modalContent">
      <p>Memuat data...</p>
    </div>
  </div>
</div>
