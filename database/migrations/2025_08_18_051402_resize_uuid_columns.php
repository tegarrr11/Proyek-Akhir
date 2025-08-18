<?php
// database/migrations/2025_08_18_020722_resize_uid_columns.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    private function ensureUidColumn(string $table): void
    {
        // kalau tabelnya tidak ada, lewati
        if (!$this->tableExists($table)) {
            return;
        }

        // jika kolom uid belum ada → tambahkan
        if (!Schema::hasColumn($table, 'uid')) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('uid', 36)->nullable()->index();
            });
            return;
        }

        // kalau sudah ada → pastikan panjang 36 (untuk UUID v4)
        Schema::table($table, function (Blueprint $t) {
            $t->string('uid', 36)->nullable()->change();
        });

        // optional: coba tambahkan unique index kalau belum ada
        // (abaikan jika DB tidak mendukung pengecekan index dengan cara ini)
        try {
            $indexName = "{$table}_uid_unique";
            $hasIndex = collect(DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]))->isNotEmpty();

            if (!$hasIndex) {
                Schema::table($table, function (Blueprint $t) use ($indexName) {
                    $t->unique('uid', $indexName);
                });
            }
        } catch (\Throwable $e) {
            // diamkan saja; index unik opsional
        }
    }

    public function up(): void
    {
        // Daftar kemungkinan nama tabel (singular/plural berbeda di beberapa proyek)
        $candidates = [
            'gedungs',
            'peminjamans', // kebanyakan proyek Laravel
            'peminjaman',  // kalau proyekmu pakai singular
            'fasilitas',
        ];

        foreach ($candidates as $table) {
            $this->ensureUidColumn($table);
        }
    }

    public function down(): void
    {
        // Tidak perlu rollback ukuran kolom; aman dibiarkan.
        // (Kalau mau, bisa drop unique index—opsional)
        $tables = ['gedungs','peminjamans','peminjaman','fasilitas'];
        foreach ($tables as $table) {
            if (!$this->tableExists($table)) continue;

            try {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->dropUnique("{$table}_uid_unique");
                });
            } catch (\Throwable $e) {
                // lewati jika index tidak ada
            }
        }
    }
};