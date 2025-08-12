<div   
  id="successToast"
  x-data="{ show: false, message: '' }"
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
    <span x-text="message"></span>
  </div>
  <button @click="show = false" class="text-white hover:text-gray-200">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
</div>


<div class="bg-white rounded-md shadow !p-6" 
     x-data="{ tab: '<?php echo e(request('tab') ?? $gedungs->first()->id); ?>', confirmDeleteOpen: false, deleteFormId: '' }">
  <h2 class="text-xl font-semibold mb-4">Daftar Fasilitas per Gedung</h2>

  
  <div class="flex space-x-4 mb-4 overflow-x-auto">
    <?php $__currentLoopData = $gedungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <button type="button"
        @click="tab = '<?php echo e($gedung->id); ?>'"
        :class="tab === '<?php echo e($gedung->id); ?>' ? 'border-b-2 border-[#003366] font-semibold text-[#003366]' : 'text-gray-600'"
        class="px-4 py-2 whitespace-nowrap focus:outline-none">
        <?php echo e($gedung->nama); ?>

      </button>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <?php $__currentLoopData = $gedungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
      $tabId = $gedung->id;
      $perPage = 10;
      $currentPage = request("page_$tabId", 1);
      $totalItems = $gedung->fasilitas->count();
      $totalPages = ceil($totalItems / $perPage);
      $items = $gedung->fasilitas->slice(($currentPage - 1) * $perPage, $perPage);
    ?>

    <div x-show="tab === '<?php echo e($tabId); ?>'" x-transition>
      <?php if($items->isEmpty()): ?>
        <div class="text-sm text-gray-500">Belum ada fasilitas di gedung ini.</div>
      <?php else: ?>
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
              <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $isEditing = request('edit') == $item->id;
                  $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                  $nomor = ($currentPage - 1) * $perPage + $index + 1;
                ?>

                <?php if($isEditing): ?>
                <form id="formSimpan" action="<?php echo e(route('admin.fasilitas.update', $item->id)); ?>" method="POST" data-action="update">
                  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                  <input type="hidden" name="gedung_id" value="<?php echo e($item->gedung_id); ?>">
                  <tr class="<?php echo e($rowClass); ?> border-t border-gray-200">
                    <td class="px-4 py-2 align-middle"><?php echo e($nomor); ?></td>
                    <td class="px-4 py-2 align-middle">
                      <input type="text" name="nama_barang" value="<?php echo e(old('nama_barang', $item->nama_barang)); ?>" class="border px-2 py-1 rounded text-sm w-full" required>
                    </td>
                    <td class="px-4 py-2 align-middle">
                      <input type="number" name="stok" value="<?php echo e(old('stok', $item->stok)); ?>" class="border px-2 py-1 rounded text-sm w-full" required>
                    </td>
                    <td class="px-4 py-2 align-middle">
                      <button type="submit" class="btn-simpan bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">SIMPAN</button>
                      <button type="button" @click="tab = tab" class="text-gray-600 text-xs ml-2 hover:underline">Batal</button>
                    </td>
                  </tr>
                </form>
                <?php else: ?>
                <tr 
                  x-data="{
                    editing: false,
                    nama: '<?php echo e($item->nama_barang); ?>',
                    stok: '<?php echo e($item->stok); ?>'
                  }" 
                  class="<?php echo e($rowClass); ?> border-t border-gray-200"
                >
                  <td class="px-4 py-2 align-middle"><?php echo e($nomor); ?></td>

                  
                  <td class="px-4 py-2 align-middle">
                    <template x-if="editing">
                      <input type="text" x-model="nama" class="border px-2 py-1 rounded text-sm w-full">
                    </template>
                    <template x-if="!editing">
                      <span x-text="nama"></span>
                    </template>
                  </td>

                  
                  <td class="px-4 py-2 align-middle">
                    <template x-if="editing">
                      <input type="number" x-model="stok" class="border px-2 py-1 rounded text-sm w-16 text-right">
                    </template>
                    <template x-if="!editing">
                      <span x-text="stok"></span>
                    </template>
                  </td>

                  <td class="px-4 py-2 align-middle">
                    <div class="flex items-center gap-2">
                      <button type="button" @click="editing = true" x-show="!editing" class="text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#0071ff" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8.925l-2 2H5v14h14v-6.95l2-2V19q0 .825-.587 1.413T19 21zm4-6v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4t-.137.738t-.438.662L13.25 15zM21.025 4.4l-1.4-1.4zM11 13h1.4l5.8-5.8l-.7-.7l-.725-.7L11 11.575zm6.5-6.5l-.725-.7zl.7.7z"/></svg>
                      </button>

                      <template x-if="editing">
                        <form @submit.prevent="submitEdit($el, '<?php echo e(route('admin.fasilitas.update', $item->id)); ?>', nama, stok, '<?php echo e($item->gedung_id); ?>')" class="flex items-center gap-2">
                          <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                          <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">SIMPAN</button>
                          <button type="button" @click="editing = false" class="text-gray-600 text-xs ml-2 hover:underline">Batal</button>
                        </form>
                      </template>

                      
                      <form id="deleteForm-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.fasilitas.destroy', $item->id)); ?>" method="POST" data-action="delete" x-show="!editing">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <input type="hidden" name="tab" value="<?php echo e($tabId); ?>">
                        <button type="button" 
                          @click="confirmDeleteOpen = true; deleteFormId = 'deleteForm-<?php echo e($item->id); ?>'" 
                          class="text-red-500 hover:text-red-700">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-red-500" viewBox="0 0 24 24">
                            <path d="M9 3h6a1 1 0 011 1v1h5v2H4V5h5V4a1 1 0 011-1zm-4 6h14l-1.5 13.5a1 1 0 01-1 .5H7.5a1 1 0 01-1-.5L5 9z"/>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>

        
        <?php if($totalPages > 1): ?>
        <div class="mt-4 flex justify-center space-x-2">
          <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="#"
              class="px-3 py-1 border rounded text-sm <?php echo e($i == $currentPage ? 'bg-[#003366] text-white' : 'text-gray-600 hover:bg-gray-100'); ?>"
              data-page="<?php echo e($i); ?>"
              data-tab="<?php echo e($tabId); ?>">
              <?php echo e($i); ?>

            </a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  
  <div x-show="confirmDeleteOpen" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[9999]">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 text-center">
      <div class="flex justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h2 class="text-lg font-semibold mb-2">Anda yakin ingin menghapus barang default dari ruangan ini?</h2>
      <p class="text-gray-600 mb-6 text-sm">Data akan dihapus secara permanen dan tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>
      <div class="flex justify-center gap-4">
        <button @click="document.getElementById(deleteFormId).dispatchEvent(new Event('submit', {cancelable: true})); confirmDeleteOpen = false;" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ya</button>
        <button @click="confirmDeleteOpen = false" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Tidak</button>
      </div>
    </div>
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const currentTab = new URLSearchParams(window.location.search).get('tab') || '<?php echo e($gedungs->first()->id); ?>';

  // Tampilkan toast sukses
  const showSuccessToast = (msg) => {
    const toast = document.getElementById('successToast');
    if (toast && Alpine && Alpine.$data(toast)) {
      const comp = Alpine.$data(toast);
      comp.message = msg;
      comp.show = true;
      setTimeout(() => comp.show = false, 2000);
    }
  };

  // Tampilkan toast jika ada param sukses di URL
  const urlParams = new URLSearchParams(window.location.search);
  const successMsg = urlParams.get('success');
  if (successMsg) {
    showSuccessToast(successMsg);
    urlParams.delete('success');
    const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
    history.replaceState({}, '', newUrl);
  }

  // Fungsi bind ulang semua event (update, delete, pagination)
  const bindAllEvents = () => {
    // Form update
    document.querySelectorAll('form[data-action="update"]').forEach(form => {
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = this.action;

        try {
          const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (res.ok) {
            location.href = `?tab=${currentTab}&success=Fasilitas berhasil diperbarui`;
          } else {
            showSuccessToast('Gagal menyimpan data!');
          }
        } catch {
          showSuccessToast('Terjadi kesalahan saat menyimpan!');
        }
      });
    });

    // Form delete AJAX
    document.querySelectorAll('form[data-action="delete"]').forEach(form => {
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = this.action;
        const row = this.closest('tr'); // Baris tabel terdekat

        try {
          const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (res.ok) {
            if (row) row.remove();
            showSuccessToast('Fasilitas berhasil dihapus');
          } else {
            showSuccessToast('Gagal menghapus data!');
          }
        } catch {
          showSuccessToast('Terjadi kesalahan saat menghapus!');
        }
      });
    });

    // Pagination link AJAX
    document.querySelectorAll('a[data-page][data-tab]').forEach(link => {
      link.addEventListener('click', async function(e) {
        e.preventDefault();
        const page = this.dataset.page;
        const tabId = this.dataset.tab;

        const url = `?tab=${tabId}&page_${tabId}=${page}`;
        try {
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          const html = await res.text();

          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newTabContent = doc.querySelector(`div[x-show="tab === '${tabId}'"]`);
          const currentTabContent = document.querySelector(`div[x-show="tab === '${tabId}'"]`);

          if (newTabContent && currentTabContent) {
            currentTabContent.innerHTML = newTabContent.innerHTML;
            history.pushState({}, '', url);
            bindAllEvents();
          }
        } catch {
          showSuccessToast('Gagal memuat halaman.');
        }
      });
    });
  };

  bindAllEvents();
});

window.submitEdit = async function (el, url, nama, stok, gedungId) {
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
  formData.append('nama_barang', nama);
  formData.append('stok', stok);
  formData.append('gedung_id', gedungId);

  try {
    const res = await fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (res.ok) {
      const row = el.closest('tr');
      const alpineComponent = Alpine?.closestDataStack(row)?.[0];
      if (alpineComponent) alpineComponent.editing = false;

      showSuccessToast('Fasilitas berhasil diperbarui');
    } else {
      showSuccessToast('Gagal menyimpan data!');
    }
  } catch (err) {
    showSuccessToast('Terjadi kesalahan saat menyimpan!');
  }
};

window.showSuccessToast = function (message) {
  const toast = document.getElementById('successToast');
  if (!toast) return;

  const alpineComponent = Alpine?.closestDataStack(toast)?.[0];
  if (alpineComponent) {
    alpineComponent.message = message;
    alpineComponent.show = true;
    setTimeout(() => alpineComponent.show = false, 2000);
  }
};
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Acer\Documents\SIMFasilitas\Proyek-Akhir\resources\views/components/table-fasilitas.blade.php ENDPATH**/ ?>