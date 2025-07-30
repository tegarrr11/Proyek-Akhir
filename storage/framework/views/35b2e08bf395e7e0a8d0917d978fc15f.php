<?php use Illuminate\Support\Facades\Request; ?>

<style>

h2.text-2xl {
  font-size: 1.125rem;
}

.legend-icon svg {
  width: 0.75rem !important;
  height: 0.75rem !important;
}
.legend-icon span {
  font-size: 0.75rem;
}

#calendar {
  width: 100%;
  max-width: 100%;
  min-height: 500px;
  overflow: hidden;
  font-size: 0.75rem !important; 
}

.fc .fc-toolbar-title {
  font-size: 0.875rem;
  font-weight: 600;
}

.fc .fc-button {
  background-color: transparent !important;
  border: none;
  color: #4b5563; 
  font-size: 1.1rem;
  box-shadow: none; 
}

.fc .fc-prev-button,
.fc .fc-next-button {
  background-color: transparent !important;
  border: none;
  font-size: 1.1rem;
  color: #003366;
  box-shadow: none;
}

.fc .fc-prev-button:hover,
.fc .fc-next-button:hover {
  color: #001d3d; 
}

.fc .fc-prev-button:focus,
.fc .fc-next-button:focus {
  outline: none !important;
  box-shadow: none !important;
  border: none !important;
}

.fc .fc-header-toolbar {
  display: flex !important;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.fc .fc-header-toolbar .fc-toolbar-chunk {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.fc .fc-scrollgrid {
  min-height: 100% !important;
}

.fc .fc-day-today {
  background-color: transparent !important;
}

.fc .fc-daygrid-day-frame {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  padding: 4px !important;
}

.fc .fc-daygrid-day-number {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  font-size: 0.75rem;
  color: #1f2937; /* Warna abu-abu tua */
  width: 28px;
  height: 28px;
}

.fc .fc-day-today .fc-daygrid-day-number {
  background-color: #0d6efd; 
  color: white !important;
  font-weight: 600;
  border-radius: 9999px;
}

  /* Set label width yang tetap hanya untuk event satu hari */
  .fc-event-main {
    max-width: 100px;
    margin: 2px 4px;
    font-size: 0.75rem; 
    font-weight: 200; 
    border-radius: 4px;
    overflow: hidden; /* Menyembunyikan teks yang terlalu panjang */
    text-overflow: ellipsis; /* Menambahkan "..." untuk teks yang terpotong */
    white-space: nowrap; /* Menjaga teks tetap dalam satu baris */
  }

</style>

<div class="flex-1 bg-white rounded-xl shadow px-4 sm:px-6 md:px-8 lg:px-12 py-6 pb-18">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4 border-b pb-4">
    <h2 class="text-xl font-bold text-gray-800">Kalender</h2>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
      <div class="h-[2px] bg-gray-200 mb-4"></div>
      <!-- Dropdown -->
        <form method="GET" action="<?php echo e(Request::url()); ?>">
          <select name="gedung_id"
            onchange="this.form.submit()"
            class="border border-grey-500 text-sm px-4 py-2 h-10 rounded w-52">
            <?php $__currentLoopData = $gedungs->where('id', '!=', 8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gedung): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($gedung->id); ?>" <?php echo e($gedung->id == $selectedGedungId ? 'selected' : ''); ?>>
                <?php echo e($gedung->nama); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </form>
      <!-- Tombol Ajukan -->
      <?php $user = auth()->user(); ?>
      <?php if($user && $user->role === 'admin'): ?>
      <a href="<?php echo e(route('admin.peminjaman.create')); ?>"
        class="inline-flex items-center gap-1 bg-[#003366] text-white text-sm px-4 py-2 h-10 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Ajukan Peminjaman
      </a>
      <?php elseif($user && $user->role === 'dosen'): ?>
      <a href="<?php echo e(route('dosen.peminjaman.create')); ?>"
        class="inline-flex items-center gap-1 bg-[#003366] text-white text-sm px-4 py-2 h-10 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Ajukan Peminjaman
      </a>
      <?php elseif($user && $user->role === 'mahasiswa'): ?>
      <a href="<?php echo e(route('peminjaman.create')); ?>"
        class="inline-flex items-center gap-1 bg-[#003366] text-white text-sm px-4 py-2 h-10 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Ajukan Peminjaman
      </a>
      <?php else: ?>
      <?php endif; ?>
    </div>
  </div>
  <!-- Legend Status -->
  <div class="flex items-center gap-6 text-sm text-gray-600 mb-4">
    
    <div class="flex items-center gap-2">
      
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#23839d" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"/></svg>
      Mahasiswa
    </div>

    
    <div class="flex items-center gap-2">
      
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#E33C45" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22"/></svg>
      Staff
    </div>

  </div>
  <!-- <pre class="bg-gray-100 p-4 text-sm text-gray-800 rounded-md overflow-auto">
  <?php echo e(json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)); ?>

  </pre> -->

  <!-- Kalender -->
  <div id="calendar"></div>
  <?php echo $__env->make('components.card-detail-kalender', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>



<?php $__env->startPush('head'); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>
<?php $__env->stopPush(); ?>

<?php if (! $__env->hasRenderedOnce('4ca2ec96-ae40-44ca-9d06-dbcbc275ce69')): $__env->markAsRenderedOnce('4ca2ec96-ae40-44ca-9d06-dbcbc275ce69'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const events = <?php echo json_encode($events, 15, 512) ?>;

    function formatTanggal(tanggal) {
      const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const d = new Date(tanggal);
      return `${hari[d.getDay()]}, ${String(d.getDate()).padStart(2, '0')} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'id',
      height: 600,
      headerToolbar: {
        left: 'prev',
        center: 'title',
        right: 'next'
      },

      eventDisplay: 'flex',
      displayEventTime: false,

      eventClick: function(info) {
        const role = document.querySelector('meta[name="user-role"]')?.content || 'mahasiswa';
        const detailUrl = `/${role}/peminjaman/${info.event.id}`;

        fetch(detailUrl)
          .then(res => res.json())
          .then(data => {
            console.log(data);
            showDetailModal({
              organisasi: data.organisasi || '-',
              tanggal: data.tgl_kegiatan ? formatTanggal(data.tgl_kegiatan) : '-',
              kegiatan: data.judul_kegiatan || '-',
              jam: (data.waktu_mulai && data.waktu_berakhir) ? `${data.waktu_mulai} - ${data.waktu_berakhir}` : '-',
              penanggung_jawab: data.penanggung_jawab || '-'
            });
          })
          .catch(() => {
            showDetailModal({
              organisasi: '-',
              tanggal: '-',
              kegiatan: '-',
              jam: '-',
              penanggung_jawab: '-'
            });
          });
      },

      events: events.map(event => {
          const startTime = event.start.substring(11, 16);
          const endTime = event.end.substring(11, 16);
          const role = event.title.split('(').pop()?.replace(')', '').trim().toLowerCase();

          const isMahasiswa = [
              'aet', 'itsa', 'himasistifo', 'himatrik', 'hmm', 'himaksi', 'himatel',
              'himika', 'himakom', 'himatron',
              'ukm basket', 'ukm futsal', 'ukm volly', 'ukm badminton',
              'pcr-rohil', 'pcr-sumbar','bem','blm'
          ].includes(role);

          const labelColor = isMahasiswa ? '#28839D' : '#E33C45';

          // Cek apakah event berlangsung satu hari
          const isSingleDayEvent = event.start.substring(0, 10) === event.end.substring(0, 10);
          
          // Tentukan apakah event hanya satu hari, dan tambahkan kelas 'single-day'
          const labelClass = isSingleDayEvent ? 'single-day' : '';

          return {
              id: event.id,
              title: `${startTime} - ${endTime} (${role.toUpperCase()})`,
              start: event.start,
              end: event.end,
              color: labelColor,
              classNames: [labelClass], // Tambahkan kelas single-day untuk event satu hari
          };
      }),

      eventContent: function(info) {
          return { html: info.event.title }; // Mengembalikan konten event
      }

    });

    

    calendar.render();
  });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?><?php /**PATH C:\Users\User\Documents\Proyek-Akhir\resources\views/components/kalender-mahasiswa.blade.php ENDPATH**/ ?>