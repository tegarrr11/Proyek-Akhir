@extends('layouts.sidebar-mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('head')
  <!-- FullCalendar CDN & Indonesia locale -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

  <style>
    .fc .fc-daygrid-day-top {
      justify-content: center;
      align-items: flex-start;
      padding-top: 4px;
    }

    .fc-header-custom {
      display: flex;
      align-items: center;
      font-weight: 600;
      font-size: 1.1rem;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .fc-header-custom button {
      background: transparent;
      border: none;
      font-size: 1.25rem;
      font-weight: bold;
      color: #333;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 6px;
      transition: background 0.2s;
    }

    .fc-header-custom button:hover {
      background-color: #f3f4f6;
    }

    .fc-header-custom svg {
      width: 1.25rem;
      height: 1.25rem;
    }
  </style>
@endsection

@section('content')
  <x-header :title="$title" :breadcrumb="$breadcrumb" />

  <div class="bg-white rounded-md shadow p-6">
    <div class="mb-4">
      <h2 class="text-lg font-semibold text-gray-800">Jadwal Ruangan: {{ $title }}</h2>
      <hr class="border-t border-gray-300 mt-2">
    </div>

    <div id="custom-toolbar" class="fc-header-custom"></div>
    <div id="calendar"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      const toolbarEl = document.getElementById('custom-toolbar');

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        locale: 'id',
        headerToolbar: false,
        datesSet: function () {
          renderCustomToolbar();
        },
      });

      calendar.render();

      function renderCustomToolbar() {
        const date = calendar.getDate();
        const bulan = date.toLocaleString('id-ID', { month: 'long' });
        const tahun = date.getFullYear();

        toolbarEl.innerHTML = `
          <button id="btn-prev" title="Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <span>${bulan} ${tahun}</span>
          <button id="btn-next" title="Berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        `;

        document.getElementById('btn-prev').onclick = () => calendar.prev();
        document.getElementById('btn-next').onclick = () => calendar.next();
      }
    });
  </script>
@endsection
