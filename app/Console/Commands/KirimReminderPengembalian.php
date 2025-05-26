<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use App\Mail\NotifikasiEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class KirimReminderPengembalian extends Command
{
    protected $signature = 'notifikasi:pengingat-pengembalian';
    protected $description = 'Kirim notifikasi pengingat H-1 pengembalian';

    public function handle()
    {
        $besok = Carbon::tomorrow();
        $peminjamans = Peminjaman::whereDate('tgl_kegiatan', $besok)
            ->where('status_pengembalian', '!=', 'selesai')->get();

        foreach ($peminjamans as $p) {
            $judul = 'Pengingat Pengembalian';
            $pesan = 'Pengembalian untuk "' . $p->judul_kegiatan . '" besok.';
            Notifikasi::create([ 'user_id' => $p->user_id, 'judul' => $judul, 'pesan' => $pesan ]);
            if (env('NOTIF_EMAIL')) {
                Mail::to($p->user->email)->send(new NotifikasiEmail($judul, $pesan));
            }
        }
    }
}