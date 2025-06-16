<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>

  @include('layouts.partials.head')
  @yield('head')

  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(Auth::check())
    <meta name="user-id" content="{{ Auth::id() }}">
  @endif

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 h-screen flex flex-col md:flex-row">
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>
  <!-- Mobile Header -->
  <header class="md:hidden flex justify-between items-center px-4 py-3 bg-white border-b">
    <div class="flex items-center gap-4">
      <button id="sidebarToggleMobile" class="focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="h-8">
    </div>
    <!-- Notification Icon with Badge -->
    <div class="relative" x-data="notifDropdown()">
      <button @click="toggle" class="relative focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">0</span>
      </button>
      <div x-show="open" x-cloak @click.away="open = false"
           class="absolute right-0 mt-2 w-72 bg-white border shadow-lg rounded-lg z-50"
           x-transition>
        <div id="notif-list" class="max-h-80 overflow-y-auto text-sm divide-y divide-gray-200">
          <div class="p-3 text-gray-500 text-sm text-center">Belum ada notifikasi</div>
        </div>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-white border-r flex flex-col justify-between md:static fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-6">
      <div class="text-center mt-4 mb-6 md:mt-12 md:mb-8 block">
        <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="w-32 md:w-40 mx-auto mb-2">
      </div>

      <nav class="space-y-2">
        <a href="{{ route('bem.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
           {{ request()->routeIs('bem.dashboard') || request()->routeIs('bem.auditorium') || request()->routeIs('bem.gsg') || request()->routeIs('bem.gor') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          Dashboard
        </a>

        <a href="{{ route('bem.peminjaman') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
           {{ request()->routeIs('bem.peminjaman') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
          Peminjaman
        </a>
      </nav>
    </div>

    <div class="p-6">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout flex items-center gap-2 text-[#003366] text-sm font-medium hover:underline">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7" />
          </svg>
          Log Out
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-6 overflow-auto">
    @yield('content')
  </main>

  <div id="notif-toast" class="fixed bottom-6 right-6 z-50 space-y-2 text-sm"></div>

  <!-- Script -->
  <script>
  const mobileToggle = document.getElementById('sidebarToggleMobile');
  const overlay = document.getElementById('sidebarOverlay');
  const sidebar = document.getElementById('sidebar');

  overlay?.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');     // sembunyikan sidebar
    overlay.classList.add('hidden');                // sembunyikan overlay
  });

  mobileToggle?.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden'); 
  });

    function notifDropdown() {
      return {
        open: false,
        toggle() {
          this.open = !this.open;
          if (this.open) this.fetchNotif();
        },
        fetchNotif() {
          fetch('/notif/list')
            .then(res => res.json())
            .then(data => {
              const list = document.getElementById('notif-list');
              const badge = document.getElementById('notif-badge');

              if (list && badge) {
                list.innerHTML = '';
                if (data.length > 0) {
                  data.forEach(n => {
                    const item = document.createElement('div');
                    item.className = 'p-3 hover:bg-gray-100 cursor-pointer';
                    item.innerHTML = `<strong>${n.judul}</strong><br><span>${n.pesan}</span>`;
                    list.appendChild(item);
                  });
                  badge.innerText = data.length;
                } else {
                  list.innerHTML = '<div class="p-3 text-gray-500 text-center">Belum ada notifikasi</div>';
                  badge.innerText = 0;
                }
              }
            });
        }
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      const userId = document.querySelector('meta[name="user-id"]')?.content;

      if (userId && window.Echo) {
        window.Echo.private(`notifikasi.${userId}`)
          .listen('NotifikasiEvent', (e) => {
            const badge = document.getElementById('notif-badge');
            if (badge) badge.innerText = parseInt(badge.innerText || 0) + 1;

            const list = document.getElementById('notif-list');
            if (list) {
              if (list.children.length === 1 && list.children[0].innerText.includes('Belum ada')) {
                list.innerHTML = '';
              }
              const item = document.createElement('div');
              item.className = 'p-3 hover:bg-gray-100 cursor-pointer';
              item.innerHTML = `<strong>${e.judul}</strong><br><span>${e.pesan}</span>`;
              list.prepend(item);
            }

            const toast = document.getElementById('notif-toast');
            if (toast) {
              const box = document.createElement('div');
              box.className = 'bg-white border-l-4 border-blue-500 shadow-lg rounded px-4 py-2';
              box.innerHTML = `<strong class="block text-blue-800 mb-1">${e.judul}</strong><span>${e.pesan}</span>`;
              toast.prepend(box);
              setTimeout(() => box.remove(), 7000);
            }
          });
      }
    });
  </script>
  <script src="https://unpkg.com/alpinejs" defer></script>
  @stack('scripts')
</body>
</html>

