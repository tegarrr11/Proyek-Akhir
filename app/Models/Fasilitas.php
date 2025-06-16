<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'gedung_id',
        'nama_barang',
        'stok',
        'is_available',
    ];

    protected static function booted()
    {
        static::saving(function ($fasilitas) {
            $fasilitas->is_available = $fasilitas->stok > 0;
        });
    }

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

}
