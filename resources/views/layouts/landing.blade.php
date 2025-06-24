<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistem Informasi Manajemen Fasilitas PCR')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
  @stack('head')
</head>
<body class="bg-[#eaf6fb] font-sans text-gray-800">

  {{-- Header Logo --}}
  <div class="flex items-center px-12 py-4 bg-[#eaf6fb]">
    <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo Sarpras"
      class="h-10 w-auto max-w-[80px] object-contain mr-3">
  </div>

  {{-- Konten --}}
  <div class="w-full px-12">
    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
