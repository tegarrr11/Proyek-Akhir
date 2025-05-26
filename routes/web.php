<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\BemController;
use App\Http\Controllers\BemPeminjamanController;
use App\Http\Controllers\AdminPeminjamanController;

// ========== LOGIN ==========
Route::get('/', fn () => view('auth.login'))->name('login');

// ========== QUICK LOGIN DUMMY ==========
Route::get('/quick-login/{role}', function ($role) {
    $user = User::firstOrCreate(
        ['email' => $role . '@example.com'],
        [
            'name' => ucfirst($role) . ' User',
            'role' => $role,
            'password' => bcrypt('password')
        ]
    );

    Auth::login($user);
    session()->regenerate();

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'bem' => redirect()->route('bem.dashboard'),
        'dosen' => redirect()->route('dosen.dashboard'),
        default => redirect()->route('login'),
    };
});

// ========== LOGOUT ==========
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ========== AUTHENTICATED USER ==========
Route::middleware(['auth'])->group(function () {
    // === DASHBOARD ===
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/bem/dashboard', fn () => view('pages.bem.dashboard'))->name('bem.dashboard');
    Route::get('/dosen/dashboard', fn () => view('pages.dosen.dashboard'))->name('dosen.dashboard');
    Route::get('/bem/dashboard', [BemController::class, 'dashboard'])->name('bem.dashboard');


    // === FASILITAS ADMIN ===
    Route::get('/admin/fasilitas', [FasilitasController::class, 'index'])->name('admin.fasilitas');
    Route::post('/admin/fasilitas/store', [FasilitasController::class, 'store'])->name('admin.fasilitas.store');
    Route::put('/admin/fasilitas/{id}', [FasilitasController::class, 'update'])->name('admin.fasilitas.update');
    Route::delete('/admin/fasilitas/{id}', [FasilitasController::class, 'destroy'])->name('admin.fasilitas.destroy');

    // === KALENDER MAHASISWA ===
    Route::get('/mahasiswa/auditorium', fn () => view('pages.mahasiswa.kalender', [
        'title' => 'Auditorium',
        'breadcrumb' => 'Dashboard > Ruangan > Auditorium'
    ]))->name('mahasiswa.auditorium');

    Route::get('/mahasiswa/gsg', fn () => view('pages.mahasiswa.kalender', [
        'title' => 'Main Hall GSG',
        'breadcrumb' => 'Dashboard > Ruangan > Main Hall GSG'
    ]))->name('mahasiswa.gsg');

    Route::get('/mahasiswa/gor', fn () => view('pages.mahasiswa.kalender', [
        'title' => 'GOR',
        'breadcrumb' => 'Dashboard > Ruangan > GOR'
    ]))->name('mahasiswa.gor');

    // === MAHASISWA - PEMINJAMAN ===
    Route::get('/mahasiswa/fasilitas', fn () => view('pages.mahasiswa.fasilitas'))->name('mahasiswa.fasilitas');
    Route::get('/mahasiswa/peminjaman', [PeminjamanController::class, 'index'])->name('mahasiswa.peminjaman');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/mahasiswa/peminjaman/store', [PeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');
    Route::patch('/mahasiswa/peminjaman/{id}/ambil', [PeminjamanController::class, 'ambil'])->name('mahasiswa.peminjaman.ambil');
    Route::patch('/mahasiswa/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('mahasiswa.peminjaman.kembalikan');

    

    // === ADMIN - PEMINJAMAN ===
    Route::prefix('admin')->group(function () {
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('admin.peminjaman');
        Route::post('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
        Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        Route::post('/ruangan/update', [AdminController::class, 'update'])->name('admin.ruangan.update');
    });

    // === BEM - PEMINJAMAN ===
    Route::prefix('bem')->group(function () {
        Route::get('/peminjaman', [BemPeminjamanController::class, 'index'])->name('bem.peminjaman');
        Route::post('/peminjaman/{id}/approve', [BemPeminjamanController::class, 'approve'])->name('bem.peminjaman.approve');
        Route::get('/peminjaman/{id}/detail', [BemPeminjamanController::class, 'show'])->name('bem.peminjaman.show');
    });
    
    //Detail Card
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // === API ===
    Route::get('/api/barang', [PeminjamanController::class, 'getBarangByRuangan']);
    Route::get('/api/ruangan/{slug}', [AdminController::class, 'getGedung']);

    // === FORM PEMINJAMAN ===
    Route::get('/fasilitas/ajukan', fn () => view('pages.mahasiswa.peminjaman.create'))->name('peminjaman.create');

    // === Notifikasi ===
    Route::get('/notif/list', function () {
    return Notifikasi::where('user_id', Auth::id())
                     ->latest()
                     ->take(10)
                     ->get(['judul', 'pesan']);
    })->middleware('auth');

    // === Download Riwayat ===
    //PDF
    // Mahasiswa
    // Route::get('/peminjaman/download/mahasiswa', [PeminjamanController::class, 'exportMahasiswa'])->middleware('auth');
    // // BEM
    // Route::get('/verifikasi/download/bem', [VerifikasiController::class, 'exportBem'])->middleware('auth');
    // // Admin
    // Route::get('/approval/download/admin', [ApprovalController::class, 'exportAdmin'])->middleware('auth');

    //Excel-Spreadsheet-CSV
    // Route::get('/download-riwayat', function () {
    // $rows = \App\Models\Peminjaman::all();

    // $handle = fopen('php://temp', 'r+');

    // fputcsv($handle, ['Judul Kegiatan', 'Tanggal', 'Mulai', 'Selesai', 'Organisasi', 'Penanggung Jawab']);

    // foreach ($rows as $row) {
    //     // Tambahkan log debug sebelum fputcsv
    //     logger()->info("EXPORT CSV: ", $row->toArray());

    //     fputcsv($handle, [
    //         $row->judul_kegiatan ?? 'NULL',
    //         $row->tgl_kegiatan ?? 'NULL',
    //         $row->waktu_mulai ?? 'NULL',
    //         $row->waktu_berakhir ?? 'NULL',
    //         $row->organisasi ?? 'NULL',
    //         $row->penanggung_jawab ?? 'NULL',
    //     ]);
    // }

    // rewind($handle);
    // $csvContent = stream_get_contents($handle);
    // fclose($handle);

    // return response($csvContent)
    //     ->header('Content-Type', 'text/csv')
    //     ->header('Content-Disposition', 'attachment; filename="riwayat_peminjaman.csv"');
    // });

    //Mahasiswa
    Route::get('/download-riwayat', function () {
    $data = DB::table('peminjaman')->get();

    if ($data->isEmpty()) {
        return response('Data kosong.', 404);
    }

    $filename = "riwayat_peminjaman_" . now()->format('Ymd_His') . ".csv";

    return response()->streamDownload(function () use ($data) {
        ob_clean();
        flush();

        $file = fopen('php://output', 'w');

        // Tambahkan BOM
        fwrite($file, "\xEF\xBB\xBF");

        // Pakai titik koma sebagai delimiter
        $header = array_keys((array) $data[0]);
        fputcsv($file, $header, ';');

        foreach ($data as $row) {
            fputcsv($file, (array) $row, ';');
        }

        fclose($file);
    }, $filename, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ]);
    })->name('download.riwayat');

    //BEM
    Route::get('/download-riwayat-bem', function () {
    $data = DB::table('peminjaman')
        ->join('users', 'peminjaman.user_id', '=', 'users.id')
        ->select('peminjaman.*', 'users.name as nama_user')
        ->where('verifikasi_bem', 'diterima')
        ->get();

    return response()->streamDownload(function () use ($data) {
        ob_clean(); flush();
        $file = fopen('php://output', 'w');
        fwrite($file, "\xEF\xBB\xBF"); // BOM untuk Excel

        fputcsv($file, array_keys((array) $data[0]), ';');
        foreach ($data as $row) {
            fputcsv($file, (array) $row, ';');
        }

        fclose($file);
    }, "riwayat_bem_" . now()->format('Ymd_His') . ".csv", [
        'Content-Type' => 'text/csv',
    ]);
    })->name('download.riwayat.bem');

    //Admin Sarpras
    Route::get('/download-riwayat-admin', function () {
    $data = DB::table('peminjaman')
        ->join('users', 'peminjaman.user_id', '=', 'users.id')
        ->select('peminjaman.*', 'users.name as nama_user')
        ->where('verifikasi_sarpras', 'diterima')
        ->get();

    return response()->streamDownload(function () use ($data) {
        ob_clean(); flush();
        $file = fopen('php://output', 'w');
        fwrite($file, "\xEF\xBB\xBF"); // BOM untuk Excel

        fputcsv($file, array_keys((array) $data[0]), ';');
        foreach ($data as $row) {
            fputcsv($file, (array) $row, ';');
        }

        fclose($file);
    }, "riwayat_admin_" . now()->format('Ymd_His') . ".csv", [
        'Content-Type' => 'text/csv',
    ]);
    })->name('download.riwayat.admin');
});
