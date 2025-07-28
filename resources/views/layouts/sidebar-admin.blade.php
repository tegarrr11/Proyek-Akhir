  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title')</title>

    @include('layouts.partials.head')
    @stack('head')

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
      <!-- Notifikasi -->
      <div class="relative" x-data="{ open: false, toggle() { this.open = !this.open } }">
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
      <div class="!p-3">
        <div class="text-center mt-4 mb-6 md:mt-12 md:mb-8 block">
          <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="w-32 md:w-40 mx-auto mb-2">
        </div>

        <nav class="space-y-2">
          <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Dashboard
          </a>
          <a href="{{ route('admin.fasilitas') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.fasilitas') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
            Fasilitas
          </a>
          <a href="{{ route('admin.peminjaman') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.peminjaman') ? 'bg-[#c4f7fd] text-[#003366]' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history-icon lucide-history"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
            Persetujuan & Riwayat
          </a>
        </nav>
      </div>

      <!-- Logout -->
      <div class="p-6">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout flex items-center gap-2 text-[#003366] text-sm font-medium ">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
            stroke="#003366" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
            class="lucide lucide-log-out-icon lucide-log-out">
            <path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
            Log Out
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 !p-6 overflow-auto">
      @yield('content')
    </main>

    <!-- Modal Detail Peminjaman -->
    <x-modal-detail-peminjaman />

    <script>
      window.currentPeminjamanId = null;

      window.showDetail = function(id) {

        const el = id => document.getElementById(id);
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/admin/peminjaman/${id}`)
          .then(res => res.json())
          .then(data => {
            el('judulKegiatan').textContent = data.judul_kegiatan || '-';
            el('tglKegiatan').textContent = new Date(data.tgl_kegiatan).toLocaleDateString('id-ID', {
              day: '2-digit',
              month: 'long',
              year: 'numeric'
            });
            el('jamKegiatan').textContent = `${data.waktu_mulai?.slice(0,5) || '-'} - ${data.waktu_berakhir?.slice(0,5) || '-'}`;

            el('aktivitas').textContent = data.aktivitas || '-';
            el('organisasi').textContent = data.organisasi || '-';
            el('penanggungJawab').textContent = data.penanggung_jawab || '-';
            el('keterangan').textContent = data.deskripsi_kegiatan || '-';
            el('ruangan').textContent = data.nama_ruangan || '-';

            // dokumen
            const downloadUrl = `/admin/peminjaman/download-proposal/${data.id}`;
            if (data.link_dokumen === 'ada') {
              el('linkDokumen').href = downloadUrl;
              el('linkDokumen').classList.remove('hidden');
              el('dokumenNotFound').classList.add('hidden');
            } else {
              el('linkDokumen').classList.add('hidden');
              el('dokumenNotFound').classList.remove('hidden');
            }

            // perlengkapan
            const perlengkapanList = el('perlengkapan');
            perlengkapanList.innerHTML = '';
            if (data.perlengkapan?.length > 0) {
              data.perlengkapan.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.nama} - ${item.jumlah}`;
                perlengkapanList.appendChild(li);
              });
            } else {
              const li = document.createElement('li');
              li.className = 'italic text-gray-400';
              li.textContent = 'Tidak ada perlengkapan';
              perlengkapanList.appendChild(li);
            }

            // diskusi
            let html = 'belum ada diskusi';
            if (Array.isArray(data.diskusi) && data.diskusi.length > 0) {
              html = '';
              data.diskusi.forEach(d => {
                html += `<div class='mb-1'><span class='font-semibold text-xs text-blue-700'>${d.role}:</span> ${d.pesan}</div>`;
              });
            }
            el('diskusiArea').innerHTML = html;

            // aktifkan diskusi
            const input = el('inputDiskusi');
            const btn = el('btnKirimDiskusi');
            input.disabled = false;
            btn.disabled = false;
            input.value = '';
            btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');

            window.currentPeminjamanId = data.id;
            bindDiskusiHandler();
          });
      };

      window.closeModal = function() {
        document.getElementById('detailModal').classList.add('hidden');
      };

      window.bindDiskusiHandler = function () {
        const btn = document.getElementById('btnKirimDiskusi');
        const input = document.getElementById('inputDiskusi');
        if (!btn || !input) return;

        btn.onclick = function () {
          const pesan = input.value.trim();
          if (!pesan || !window.currentPeminjamanId) return;

          btn.setAttribute('disabled', true);

          const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

          fetch('/diskusi', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
              peminjaman_id: window.currentPeminjamanId,
              pesan: pesan
            })
          })
          .then(res => res.json())
          .then(resp => {
            if (resp.success) {
              showDetail(window.currentPeminjamanId);
            } else {
              alert(resp.error || 'Gagal mengirim pesan.');
            }
          })
          .catch(() => alert('Gagal mengirim pesan.'))
          .finally(() => btn.removeAttribute('disabled'));
        };
      };
    </script>

    <!-- Notifikasi -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const userId = document.querySelector('meta[name="user-id"]')?.content;
        if (userId && window.Echo) {
          window.Echo.private(`notifikasi.${userId}`)
            .listen('NotifikasiEvent', (e) => {
              const badge = document.getElementById('notif-badge');
              const list = document.getElementById('notif-list');
              if (badge) badge.innerText = parseInt(badge.innerText || 0) + 1;
              if (list) {
                if (list.children.length === 1 && list.children[0].innerText.includes('Belum ada')) list.innerHTML = '';
                const item = document.createElement('div');
                item.className = 'p-3 hover:bg-gray-100 cursor-pointer';
                item.innerHTML = `<strong>${e.judul}</strong><br><span>${e.pesan}</span>`;
                list.prepend(item);
              }
            });
        }
      });
    </script>

    <script src="https://unpkg.com/alpinejs" defer></script>
    @stack('scripts')
  </body>
  </html>
