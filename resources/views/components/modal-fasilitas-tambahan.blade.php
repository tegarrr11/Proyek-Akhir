@props(['fasilitasLainnya' => []])

<!-- Tombol buka modal fasilitas tambahan -->
<button onclick="showFasilitasModal()" type="button"
    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm rounded">
    Tambah Fasilitas Lainnya
</button>

<!-- Modal fasilitas tambahan -->
<div id="fasilitasModal"
    class="fixed inset-0 bg-black bg-opacity-40 justify-center items-center z-50 hidden">
    <div class="bg-white w-full max-w-2xl p-4 rounded shadow-lg">
        <h2 class="text-lg font-semibold mb-2">Pilih Fasilitas Tambahan</h2>

        <!-- Search Input -->
        <input type="text" id="searchFasilitas" placeholder="Cari fasilitas..."
            class="w-full p-2 border border-gray-300 rounded mb-3 text-sm" />

        <!-- Fasilitas Grid -->
        <div id="fasilitasList" class="grid grid-cols-2 gap-4 max-h-[300px] overflow-y-auto">
            @foreach($fasilitasLainnya as $fasilitas)
            <label class="flex items-center space-x-2 fasilitas-item">
                <input type="checkbox" name="fasilitas_tambahan[]" value="{{ $fasilitas->id }}"
                    class="form-checkbox">
                <span class="fasilitas-nama">{{ $fasilitas->nama }}</span>
            </label>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-between mt-3 text-sm">
            <button onclick="paginateFasilitas('prev')" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                &laquo; Prev
            </button>
            <span id="pageIndicator">Page 1</span>
            <button onclick="paginateFasilitas('next')" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                Next &raquo;
            </button>
        </div>

        <!-- Close Modal -->
        <div class="mt-4 flex justify-end space-x-2">
            <button type="button" onclick="hideFasilitasModal()"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Tutup</button>
        </div>
    </div>
</div>

<script>
  let fasilitasPerPage = 8;
  let currentPage = 1;

  function showFasilitasModal() {
    document.getElementById('fasilitasModal').classList.remove('hidden');
    document.getElementById('fasilitasModal').classList.add('flex');
    updateFasilitasDisplay();
  }

  function hideFasilitasModal() {
    document.getElementById('fasilitasModal').classList.remove('flex');
    document.getElementById('fasilitasModal').classList.add('hidden');
  }

  function updateFasilitasDisplay() {
    const items = Array.from(document.querySelectorAll('.fasilitas-item'));
    const searchTerm = document.getElementById('searchFasilitas').value.toLowerCase();
    const filtered = items.filter(item =>
      item.querySelector('.fasilitas-nama').textContent.toLowerCase().includes(searchTerm)
    );

    const totalPages = Math.ceil(filtered.length / fasilitasPerPage);
    if (currentPage > totalPages) currentPage = totalPages || 1;
    const start = (currentPage - 1) * fasilitasPerPage;
    const end = start + fasilitasPerPage;

    items.forEach((item, i) => item.style.display = 'none');
    filtered.slice(start, end).forEach(item => item.style.display = 'flex');

    document.getElementById('pageIndicator').textContent = `Page ${currentPage} of ${totalPages || 1}`;
  }

  function paginateFasilitas(direction) {
    const items = Array.from(document.querySelectorAll('.fasilitas-item'));
    const searchTerm = document.getElementById('searchFasilitas').value.toLowerCase();
    const filtered = items.filter(item =>
      item.querySelector('.fasilitas-nama').textContent.toLowerCase().includes(searchTerm)
    );
    const totalPages = Math.ceil(filtered.length / fasilitasPerPage);

    if (direction === 'next' && currentPage < totalPages) currentPage++;
    if (direction === 'prev' && currentPage > 1) currentPage--;
    updateFasilitasDisplay();
  }

  document.getElementById('searchFasilitas')?.addEventListener('input', function () {
    currentPage = 1;
    updateFasilitasDisplay();
  });

  document.addEventListener('DOMContentLoaded', () => {
    updateFasilitasDisplay();
  });
</script>
