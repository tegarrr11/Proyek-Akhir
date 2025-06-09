<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->date('tgl_kegiatan');
            $table->time('waktu_mulai');
            $table->time('waktu_berakhir');
            $table->string('aktivitas', 50);
            $table->string('organisasi');
            $table->string('penanggung_jawab');
            $table->text('deskripsi_kegiatan');
            $table->string('proposal')->nullable();
            $table->string('undangan_pembicara')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak']);
            $table->foreignId('gedung_id')->constrained('gedungs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // peminjam
            $table->foreignId('approver_dosen_id')->nullable()->constrained('users');
            $table->foreignId('approver_rt_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->enum('verifikasi_bem', ['diajukan', 'diterima', 'ditolak'])->default('diajukan');
            $table->enum('verifikasi_sarpras', ['diajukan', 'diterima', 'ditolak'])->default('diajukan');
            $table->enum('status_peminjaman', ['ambil', 'kembalikan'])->nullable();
            $table->enum('status_pengembalian', ['proses', 'selesai'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['status_peminjaman', 'status_pengembalian']);
    });
    }
};
