<style>[x-cloak] { display: none !important; }</style>

<div
  x-show="showPopup"
  x-cloak
  x-transition.opacity.duration.300ms
  class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
  <div class="bg-white p-4 sm:p-6 rounded-lg w-11/12 max-w-sm text-center">
    <div class="mb-4 text-yellow-500 text-5xl">!</div>
    <h2 class="font-semibold text-lg mb-2">Pastikan data yang diisi sudah benar!</h2>
    <p class="text-sm mb-4">Setelah disimpan, data tidak dapat diubah lagi. Apakah Anda yakin ingin melanjutkan?</p>

    <div class="flex justify-center space-x-4">
      <!-- Tombol Tidak -->
      <button @click="showPopup = false"
        class="border border-blue-700 text-blue-700 px-4 py-2 rounded hover:bg-blue-50">
        Tidak
      </button>

      <!-- Tombol Ya -->
      <button @click="
        showPopup = false;
        showSuccess = true;
        setTimeout(() => showSuccess = false, 2000);
        submitForm();
      "
        class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
        Ya
      </button>
    </div>
  </div>
</div>
