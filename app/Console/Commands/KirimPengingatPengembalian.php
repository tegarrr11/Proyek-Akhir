<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Notifications\PengingatPengembalian;
use Carbon\Carbon;

class KirimPengingatPengembalian extends Command
{
    protected $signature = 'pengingat:pengembalian';
    protected $description = 'Kirim email pengingat H-2 sebelum tanggal berakhir kegiatan';

    public function handle()
    {
        $targetTanggal = Carbon::now()->addDays(2)->toDateString();

        $peminjamans = Peminjaman::with('user')
            ->whereDate('tgl_kegiatan_berakhir', $targetTanggal)
            ->where('status_peminjaman', 'ambil') // Sudah diambil
            ->where('status_pengembalian', '!=', 'selesai') // Belum dikembalikan
            ->get();

        foreach ($peminjamans as $peminjaman) {
            $user = $peminjaman->user;
            if ($user && $user->email) {
                $user->notify(new PengingatPengembalian($peminjaman));
                $this->info("Notifikasi dikirim ke: " . $user->email);
            }
        }

        return Command::SUCCESS;
    }
}
