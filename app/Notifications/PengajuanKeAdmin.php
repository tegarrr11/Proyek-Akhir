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

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('Pengajuan Menunggu Persetujuan Anda')
    //         ->greeting('Halo, ' . $notifiable->name)
    //         ->line('Pengajuan dari mahasiswa ' . $this->peminjaman->user->name . ' telah disetujui oleh BEM.')
    //         ->action('Lihat Detail Pengajuan', url('/admin/peminjaman'))
    //         ->line('Segera lakukan verifikasi akhir sebagai admin.');
    // }

    public function toMail(object $notifiable): MailMessage
    {
        $fasilitasList = $this->peminjaman->detailPeminjaman->map(function ($detail) {
            return $detail->fasilitas->nama_barang . ' (Jumlah: ' . $detail->jumlah . ')';
        })->implode(", ");

        $mail = (new MailMessage)
            ->subject('Pengajuan Menunggu Persetujuan Anda')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Pengajuan dari mahasiswa **' . $this->peminjaman->user->name . '** telah disetujui oleh BEM.')
            ->line('**Judul Kegiatan:** ' . $this->peminjaman->judul_kegiatan)
            ->line('**Fasilitas:** ' . $fasilitasList)
            ->line('**Tanggal Kegiatan:** ' . \Carbon\Carbon::parse($this->peminjaman->tgl_kegiatan)->format('d-m-Y'))
            ->line('**Tanggal Berakhir:** ' . \Carbon\Carbon::parse($this->peminjaman->tgl_kegiatan_berakhir)->format('d-m-Y'))
            ->action('Approve Peminjaman', route('admin.peminjaman.approve.email', $this->peminjaman->verification_token))
            ->line('👉 [Lihat Detail Pengajuan](' . url('/admin/peminjaman') . ')')
            ->line('Segera lakukan verifikasi akhir sebagai admin.');

        if ($this->peminjaman->proposal && \Storage::disk('public')->exists($this->peminjaman->proposal)) {
            $mail->attach(
                storage_path('app/public/' . $this->peminjaman->proposal)
            );
        }

        if ($this->peminjaman->undangan_pembicara && \Storage::disk('public')->exists($this->peminjaman->undangan_pembicara)) {
            $mail->attach(
                storage_path('app/public/' . $this->peminjaman->undangan_pembicara)
            );
        }

        return $mail;
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
