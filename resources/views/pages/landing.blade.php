@extends('layouts.landing')

@section('title', 'Kalender Fasilitas PCR')

@section('content')

@php use Illuminate\Support\Facades\Request; @endphp
@vite(['resources/css/app.css', 'resources/js/app.js'])

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

  .fc-daygrid-day {
    min-height: 80px !important;
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
    cursor: pointer;
  }

  .fc-event:hover {
    opacity: 0.9;
  }

  .p-6 {
    padding: 1rem !important;
  }
</style>

<div class="font-poppins w-full max-w-full bg-white rounded-xl shadow px-4 sm:px-6 md:px-8 lg:px-12 py-6 pb-18">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-2">
    <h2 class="text-2xl font-bold text-gray-800">Kalender</h2>
    <div class="flex items-center gap-3">
      <!-- Dropdown Gedung -->
      <form method="GET" action="{{ Request::url() }}" class="mr-2">
        <select
          name="gedung_id"
          onchange="this.form.submit()"
          class="text-[#003366] border border-[#003366] text-xs pl-4 pr-10 py-2 h-8 w-40 rounded-md shadow-sm appearance-none bg-white font-medium">
          @foreach($gedungs as $gedung)
            <option value="{{ $gedung->id }}" {{ $gedung->id == $selectedGedungId ? 'selected' : '' }}>
              {{ $gedung->nama }}
            </option>
          @endforeach
        </select>
      </form>

      <!-- Tombol Login -->
      <a href="{{ route('login') }}"
        class="inline-flex items-center justify-center bg-[#003366] hover:bg-[#002244] text-white w-32 text-xs font-medium h-8 px-4 rounded-md shadow">
        Login
      </a>
    </div>
  </div>

  <div class="h-[2px] bg-gray-200 mb-4"></div>

  <!-- Legenda -->
  <div class="flex items-center gap-8 text-sm text-gray-600 mb-4">
    <div class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#28839D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.42 10.92a1 1 0 0 0-.02-1.84L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.83l8.57 3.91a2 2 0 0 0 1.66 0zM22 10v6M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
      </svg>
      <span>Mahasiswa</span>
    </div>
    <div class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#f87171]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16M2 6h20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/>
      </svg>
      <span>Staff</span>
    </div>
  </div>

  <!-- Kalender -->
  <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events);

    function formatTanggal(tanggal) {
      const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      const d = new Date(tanggal);
      return `${hari[d.getDay()]}, ${String(d.getDate()).padStart(2, '0')} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'id',
      contentHeight: 'auto',
      height: 'auto',
      expandRows: true,
      fixedWeekCount: false,
      headerToolbar: {
        left: '',
        center: 'prev title next',
        right: ''
      },
      eventDisplay: 'block',
      displayEventTime: false,
      eventClick: function(info) {
        fetch(`/mahasiswa/peminjaman/${info.event.id}`)
          .then(res => res.json())
          .then(data => {
            alert(`📌 ${data.judul_kegiatan}\n📅 ${formatTanggal(data.tgl_kegiatan)}\n🕒 ${data.waktu_mulai} - ${data.waktu_berakhir}\n📚 ${data.organisasi}`);
          });
      },
      events: events.map(event => {
        const startTime = event.start.substring(11, 16);
        const endTime = event.end.substring(11, 16);
        const role = event.title.split('(').pop()?.replace(')', '').trim().toLowerCase();
        const isMahasiswa = ['mahasiswa', 'bem', 'blm', 'ukm','hima','km'].includes(role);
        const labelColor = isMahasiswa ? '#28839D' : '#f87171';
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
@endsection
