<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Landing - Kalender Fasilitas PCR')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js']) 
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js" defer></script>
  @stack('styles')
</head>
<body class="bg-[#eaf6fb] font-sans text-gray-800">

  {{-- Header Logo --}}
  <div class="flex items-center px-6 py-4 bg-[#eaf6fb]">
    <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo Sarpras" class="h-14 w-14 mr-3">
  </div>

  {{-- Konten --}}
  <main class="max-w-6xl mx-auto px-4 pb-10 w-full">
    @yield('content')
  </main>

  @stack('scripts')
</body>
</html>
