@php use Illuminate\Support\Facades\Request; @endphp

<style>
  .fc .fc-toolbar-title {
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    flex: 1;
  }
  .fc .fc-button {
    background-color: transparent !important;
    border: none;
    color: #4b5563;
    font-size: 1.1rem;
    box-shadow: none;
  }
  .fc .fc-button:focus {
    outline: none;
    box-shadow: none;
  }
  .fc .fc-button:hover {
    background-color: transparent;
    color: #1f2937;
  }
  .fc-toolbar.fc-header-toolbar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
  }
  .fc-event {
    border: none !important;
    padding: 4px 8px;
    font-size: 0.75rem;
    font-weight: normal !important;
    color: white !important;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 4px;
    white-space: nowrap;
    cursor: pointer; /* supaya bisa di-klik */
  }
  .fc-event:hover {
    opacity: 0.9;
  }
</style>

<div class="bg-white rounded-lg shadow-md p-6">
  <!-- Header Judul dan Aksi -->
  <div class="flex justify-between items-center mb-4 pb-4 border-b">
    <h2 class="text-xl font-bold text-gray-800">Kalender</h2>
    <div class="flex gap-3">
      <!-- Dropdown Gedung -->
      <form method="GET" action="{{ Request::url() }}">
        <select name="gedung_id"
        onchange="this.form.submit()"
        class="border border-grey-500 text-sm px-4 py-2 h-10 rounded w-52"> 
        @foreach($gedungs as $gedung)
            <option value="{{ $gedung->id }}" {{ $gedung->id == $selectedGedungId ? 'selected' : '' }}>
            {{ $gedung->nama }}
            </option>
        @endforeach
        </select>
      </form>

      <!-- Tombol Ajukan -->
        @if(!empty($fromLanding))
        <a href="{{ route('login') }}"
              class="inline-flex items-center gap-1 bg-[#003366] text-white text-sm px-4 py-2 h-10 rounded hover:bg-[#002244] transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a7 7 0 1114 0 7 7 0 01-14 0zm6-1V6a1 1 0 112 0v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3z" clip-rule="evenodd" />
              </svg>
            Login
        </a>
        @else
          <a href="{{ route('peminjaman.create') }}"
            class="inline-flex items-center gap-1 bg-[#003366] text-white text-sm px-4 py-2 h-10 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Ajukan Peminjaman
          </a>
        @endif
    </div>
  </div>

<!-- Legend Status -->
<div class="flex items-center gap-6 text-sm text-gray-600 mb-4">
  {{-- Mahasiswa --}}
  <div class="flex items-center gap-2">
    {{-- Icon Mahasiswa --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#01425d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap-icon lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
    Mahasiswa
  </div>

  {{-- Staff / Dosen / Admin --}}
  <div class="flex items-center gap-2">
    {{-- Icon Briefcase --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#01425d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-icon lucide-briefcase"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
    Staff
  </div>

</div>

  <!-- Kalender -->
  <div id="calendar"></div>
  @include('components.card-detail-kalender')
</div>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events);

     console.log("Events loaded:", @json($events));

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
      eventDisplay: 'block',
      displayEventTime: false,

      eventClick: function(info) {
        fetch(`/mahasiswa/peminjaman/${info.event.id}`)
          .then(res => res.json())
          .then(data => {
            showDetailModal({
              organisasi: data.organisasi,
              tanggal: formatTanggal(data.tgl_kegiatan),
              kegiatan: data.judul_kegiatan,
              jam: `${data.waktu_mulai} - ${data.waktu_berakhir}`,
              jenis: data.keterangan ?? '-'
            });
          });
      },

      events: @json($events).map(event => {
        const startTime = event.start.substring(11, 16);
        const endTime = event.end.substring(11, 16);
        const role = event.title.split('(').pop()?.replace(')', '').trim().toLowerCase();
        const isMahasiswa = ['mahasiswa', 'bem', 'blm', 'ukm','hima'].includes(role);
        const labelColor = isMahasiswa ? '#28839D' : '#facc15';

        return {
          id: event.id,
          title: `${startTime} - ${endTime} (${role.toUpperCase()})`,
          start: event.start,
          end: event.end,
          color: labelColor
        };
      })
    });

    calendar.render();
  });
</script>
@endpush
@endonce
