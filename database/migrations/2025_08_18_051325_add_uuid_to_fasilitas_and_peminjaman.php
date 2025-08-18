<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('gedungs', 'uid')) {
            Schema::table('gedungs', function (Blueprint $t) {
                $t->char('uid', 26)->nullable()->after('id')->index(); // no unique, nullable
            });
        }

        if (!Schema::hasColumn('fasilitas', 'uid')) {
            Schema::table('fasilitas', function (Blueprint $t) {
                $t->char('uid', 26)->nullable()->after('id')->index();
            });
        }

        if (!Schema::hasColumn('peminjaman', 'uid')) {
            Schema::table('peminjaman', function (Blueprint $t) {
                $t->char('uid', 26)->nullable()->after('id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('gedungs', 'uid')) {
            Schema::table('gedungs', fn (Blueprint $t) => $t->dropColumn('uid'));
        }
        if (Schema::hasColumn('fasilitas', 'uid')) {
            Schema::table('fasilitas', fn (Blueprint $t) => $t->dropColumn('uid'));
        }
        if (Schema::hasColumn('peminjaman', 'uid')) {
            Schema::table('peminjaman', fn (Blueprint $t) => $t->dropColumn('uid'));
        }
    }
};