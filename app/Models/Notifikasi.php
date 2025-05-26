<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = ['user_id', 'judul', 'pesan', 'dibaca'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


