<!-- FullCalendar CSS & JS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/png" href="{{ asset('images/logo-sarpras-no-text.png') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])

