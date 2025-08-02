<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanKeAdmin extends Notification
{
    use Queueable;

    public $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Menunggu Persetujuan Anda')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Pengajuan dari mahasiswa ' . $this->peminjaman->user->name . ' telah disetujui oleh BEM.')
            ->action('Lihat Detail Pengajuan', url('/admin/peminjaman'))
            ->line('Segera lakukan verifikasi akhir sebagai admin.');
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