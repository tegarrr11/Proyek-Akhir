<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman'; // nama tabel sesuai migrasi kamu

    protected $fillable = [
        'judul_kegiatan',
        'tgl_kegiatan',
        'waktu_mulai',
        'waktu_berakhir',
        'aktivitas',
        'organisasi',
        'penanggung_jawab',
        'deskripsi_kegiatan',
        'status',
        'verifikasi_bem',
        'verifikasi_sarpras',
        'gedung_id',
        'user_id',
        'approver_dosen_id',
        'approver_rt_id',
        'status_peminjaman',
        'status_pengembalian',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gedung()
{
    return $this->belongsTo(Gedung::class);
}

}
