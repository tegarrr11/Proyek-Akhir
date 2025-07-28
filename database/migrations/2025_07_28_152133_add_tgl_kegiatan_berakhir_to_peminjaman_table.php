<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tgl_kegiatan_berakhir')->nullable()->after('tgl_kegiatan'); // sesuaikan posisi jika perlu
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('tgl_kegiatan_berakhir');
        });
    }
};
