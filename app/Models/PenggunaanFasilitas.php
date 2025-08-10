<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaanFasilitas extends Model
{
    protected $fillable = ['fasilitas_id', 'tanggal', 'jumlah'];
}