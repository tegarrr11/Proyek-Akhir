<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Gedung extends Model
{

    use HasFactory;
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
