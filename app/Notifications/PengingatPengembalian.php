<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengingatPengembalian extends Notification
{
    use Queueable;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengingat Pengembalian Fasilitas')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Kegiatan Anda akan berakhir pada: ' . $this->peminjaman->tgl_kegiatan_berakhir)
            ->line('Mohon pastikan fasilitas dikembalikan tepat waktu.')
            ->action('Lihat Peminjaman', url('/mahasiswa/peminjaman'))
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
