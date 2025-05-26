import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//Laravel Echo dan Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Auto inject listener berdasarkan user ID
document.addEventListener('DOMContentLoaded', function () {
    const userMeta = document.head.querySelector('meta[name="user-id"]');
    if (userMeta) {
        const userId = userMeta.content;

        window.Echo.private(`notifikasi.${userId}`)
            .listen('NotifikasiEvent', (e) => {
                console.log("🔔 Notifikasi Masuk:", e);
                alert("🔔 " + e.judul + "\n" + e.pesan);

                // Optional: inject ke UI jika ada #notifDropdown
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