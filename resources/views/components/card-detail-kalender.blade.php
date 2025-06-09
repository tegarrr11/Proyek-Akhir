<!-- Modal Detail Kalender -->
<div id="calendar-detail-modal" class="fixed inset-0 z-50 hidden bg-black/30 flex items-center justify-center">
  <div class="bg-[#f0f8fa] rounded-2xl w-[380px] p-5 shadow-lg relative">
    <button onclick="document.getElementById('calendar-detail-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Header Organisasi -->
    <div class="flex items-center gap-2 mb-1">
      <span id="modal-icon" class="h-5 w-5 text-[#d62828]"></span>
      <h2 id="modal-organisasi" class="text-sm font-semibold text-gray-800">Organisasi</h2>
    </div>
    <p id="modal-tanggal" class="text-xs text-gray-500 mb-4">Tanggal</p>

    <!-- Judul Kegiatan -->
    <div class="flex items-start gap-2 mb-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#808080" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-align-left-icon lucide-align-left">
        <path d="M15 12H3"/><path d="M17 18H3"/><path d="M21 6H3"/>
    </svg>
    <p id="modal-kegiatan" class="text-sm font-medium text-gray-700">Judul Kegiatan</p>
    </div>

    <!-- Jam Kegiatan -->
    <div class="flex items-start gap-2 mb-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#808080" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
    </svg>
    <p id="modal-jam" class="text-sm text-gray-700">08.00 - 16.00</p>
    </div>

    <!-- Jenis -->
    <div class="flex items-start gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#808080" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-icon lucide-user-round">
        <circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>
    </svg>
    <p id="modal-jenis" class="text-sm text-gray-700">Internal</p>
    </div>


<!-- SCRIPT -->
<script>
  function showDetailModal(data) {
    // Format waktu: 09:00:00 -> 09.00
    const formatWaktu = (waktu) => {
      if (!waktu) return '-';
      const [jam, menit] = waktu.split(':');
      return `${jam}.${menit}`;
    };

    // Jika data.jam masih dalam format string gabungan, pisahkan
    let jamTeks = '-';
    if (data.jam?.includes(' - ')) {
      const [mulai, selesai] = data.jam.split(' - ');
      jamTeks = `${formatWaktu(mulai)} - ${formatWaktu(selesai)}`;
    }

    // Isi ke elemen
    document.getElementById('modal-organisasi').innerText = data.organisasi || '-';
    document.getElementById('modal-tanggal').innerText = data.tanggal || '-';
    document.getElementById('modal-kegiatan').innerText = data.kegiatan || '-';
    document.getElementById('modal-jam').innerText = jamTeks;
    document.getElementById('modal-jenis').innerText = data.jenis || '-';

    // SVG Mahasiswa
    const svgMahasiswa = `
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#E33C45" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap-icon lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>`;

    // SVG Staff/Admin
    const svgStaff = `
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#28839D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-icon lucide-briefcase"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>`;

    const organisasi = (data.organisasi || '').toLowerCase();
    const isMahasiswa = ['hima', 'bem', 'ukm', 'blm'].some(o => organisasi.includes(o));

    document.getElementById('modal-icon').innerHTML = isMahasiswa ? svgMahasiswa : svgStaff;

    // Tampilkan modal
    document.getElementById('calendar-detail-modal').classList.remove('hidden');
  }
</script>
