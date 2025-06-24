<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('peminjaman', 'jenis_kegiatan')) {
                $table->string('jenis_kegiatan')->nullable()->after('gedung_id');
            }
            if (!Schema::hasColumn('peminjaman', 'proposal')) {
                $table->string('proposal')->nullable()->after('jenis_kegiatan');
            }
            if (!Schema::hasColumn('peminjaman', 'undangan_pembicara')) {
                $table->string('undangan_pembicara')->nullable()->after('proposal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['jenis_kegiatan', 'proposal', 'undangan_pembicara']);
        });
    }
};
