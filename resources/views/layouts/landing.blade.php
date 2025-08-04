<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistem Informasi Manajemen Fasilitas PCR')</title>

  <link rel="icon" type="image/png" href="{{ asset('images/logo-sarpras-no-text.png') }}">

  @include('layouts.partials.head')
  @stack('head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(Auth::check())
    <meta name="user-id" content="{{ Auth::id() }}">
  @endif
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#eaf6fb] text-gray-800">

  {{-- Header Logo --}}
  <div class="flex items-center py-2 ml-8 bg-[#eaf6fb]">
    <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo Sarpras"
      class="h-12 sm:h-14 md:h-18 w-auto max-w-[100px] sm:max-w-[120px] md:max-w-[140px] object-contain mr-3">
  </div>

  {{-- Konten --}}
  <div class="w-full px-4">
    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
