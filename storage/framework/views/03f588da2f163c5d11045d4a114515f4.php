<div id="modalSelesai" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
    <h2 class="text-lg font-semibold mb-4">Checklist Barang Dikembalikan</h2>
    <form id="formSelesai" method="POST" action="">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PATCH'); ?>
      <div id="checklistContainer" class="space-y-2 mb-4">
        <!-- Checkbox barang akan dimuat via JS -->
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModalSelesai()" class="bg-gray-300 px-3 py-1 rounded">Batal</button>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModalSelesai(id) {
  fetch(`/admin/peminjaman/${id}`)
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('checklistContainer');
      container.innerHTML = '';
      if (data.perlengkapan && data.perlengkapan.length > 0) {
        data.perlengkapan.forEach(item => {
          const div = document.createElement('div');
          div.innerHTML = `
            <label class="flex items-center gap-2">
              <input type="checkbox" name="checklist[]" value="${item.id}" class="rounded border-gray-300">
              ${item.nama} - ${item.jumlah}
            </label>`;
          container.appendChild(div);
        });
      } else {
        container.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada perlengkapan.</p>';
      }
      const form = document.getElementById('formSelesai');
      form.action = `/admin/peminjaman/${id}/selesai`;
      document.getElementById('modalSelesai').classList.remove('hidden');
    });
}

function closeModalSelesai() {
  document.getElementById('modalSelesai').classList.add('hidden');
}
</script>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/modal-selesai.blade.php ENDPATH**/ ?>