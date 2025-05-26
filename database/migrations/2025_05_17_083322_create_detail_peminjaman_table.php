<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('detail_peminjaman', function (Blueprint $table) {
        $table->id();

        // relasi ke tabel peminjaman (nama tabel TIDAK jamak!)
        $table->foreignId('peminjaman_id')
            ->constrained('peminjaman')
            ->onDelete('cascade');

        // relasi ke tabel fasilitas
        $table->foreignId('fasilitas_id')
            ->constrained('fasilitas')
            ->onDelete('cascade');

        $table->unsignedInteger('jumlah');
        $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
