<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    // Nama tabel di database
    protected $table = 'gedungs';

    // Kolom yang boleh diisi
    protected $fillable = [
        'slug',
        'desc',
        'kapasitas',
        'jam_operasional'
    ];

    public function fasilitas()
    {
        return $this->hasMany(\App\Models\Fasilitas::class);
    }

}
