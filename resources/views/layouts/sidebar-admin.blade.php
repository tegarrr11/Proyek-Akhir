<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @yield('head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(Auth::check())
  <meta name="user-id" content="{{ Auth::id() }}">
  @endif
  @vite('resources/js/app.js')
</head>
@yield('scripts')
<body class="bg-gray-50 h-screen flex flex-col md:flex-row">

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
      <div class="text-center mt-12 mb-8 hidden md:block">
        <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="w-40 mx-auto mb-2">
      </div>

      <nav class="space-y-2">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
           {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.auditorium') || request()->routeIs('admin.gsg') || request()->routeIs('admin.gor') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" 
            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 
            2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 6zM3.75 15.75A2.25 2.25 0 
            0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 
            2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25zM13.5 6a2.25 2.25 0 
            0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 
            10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 
            0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 
            2.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25z" />
          </svg>
          Dashboard
        </a>

        <a href="{{ route('admin.fasilitas') }}"
          class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
          {{ request()->routeIs('admin.fasilitas') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 
                  6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 
                  21h18M12 6.75h.008v.008H12V6.75Z" />
          </svg>
          Fasilitas
        </a>


        <a href="{{ route('admin.peminjaman') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
           {{ request()->routeIs('admin.peminjaman') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" 
            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 
            2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 
            48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 
            0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 
            0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 
            1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 
            4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 
            1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
          </svg>
          Peminjaman
        </a>
      </nav>
    </div>

    <!-- Logout -->
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

  <!-- Toast Container -->
  <div id="notif-toast" class="fixed bottom-6 right-6 z-50 space-y-2 text-sm"></div>

  <!-- Script -->
  <script>
    const mobileToggle = document.getElementById('sidebarToggleMobile');
    const sidebar = document.getElementById('sidebar');

    mobileToggle?.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
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
</body>
</html>



