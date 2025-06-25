// resources/js/bootstrap.js

import axios from 'axios';
window.axios = axios;

// Set default header untuk semua request axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Laravel Echo dan Pusher
 * Komponen ini DIKOMENTARI agar tidak menimbulkan error jika belum setup .env
 * 
 * Untuk mengaktifkan kembali:
 * - Buka komentar `import Echo`, `Pusher`, dan konfigurasi Echo
 * - Pastikan .env sudah terisi VITE_PUSHER_APP_KEY dan lainnya
 */

/*
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
*/

document.addEventListener('DOMContentLoaded', function () {
    const userMeta = document.head.querySelector('meta[name="user-id"]');

    // Cek hanya jika window.Echo tersedia (tidak undefined)
    if (userMeta && typeof window.Echo !== 'undefined') {
        const userId = userMeta.content;

        window.Echo.private(`notifikasi.${userId}`)
            .listen('NotifikasiEvent', (e) => {
                console.log("🔔 Notifikasi Masuk:", e);
                alert("🔔 " + e.judul + "\n" + e.pesan);

                const list = document.getElementById('notifDropdown');
                if (list) {
                    const item = document.createElement('div');
                    item.className = 'p-2 border-b text-sm';
                    item.innerText = e.judul + ' - ' + e.pesan;
                    list.prepend(item);
                }
            });
    }
});
