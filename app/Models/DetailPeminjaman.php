<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'fasilitas_id',
        'jumlah',
    ];

    // Relasi ke tabel peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Relasi ke tabel fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}
