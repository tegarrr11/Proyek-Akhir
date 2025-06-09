<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman'; // nama tabel sesuai migrasi kamu

    protected $fillable = [
    'user_id', 
    'judul_kegiatan', 
    'tgl_kegiatan', 
    'waktu_mulai', 
    'waktu_berakhir',
    'aktivitas', 
    'organisasi', 
    'penanggung_jawab', 
    'deskripsi_kegiatan',
    'gedung_id', 
    'jenis_kegiatan', 
    'proposal', 
    'undangan_pembicara',
    'status_peminjaman', 
    'status_pengembalian',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(\App\Models\DetailPeminjaman::class);
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
