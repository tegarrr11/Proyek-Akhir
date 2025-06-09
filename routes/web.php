<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\BemController;
use App\Http\Controllers\BemPeminjamanController;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\KalenderController;

// ========== LANDING PAGE ==========
/* Route::get('/', function () {
    $gedungs = Gedung::all();

    $selectedGedungId = request('gedung_id') ?? $gedungs->first()?->id;
    if (!$selectedGedungId) {
        return view('pages.landing', [
            'gedungs' => [],
            'selectedGedungId' => null,
            'events' => [],
        ]);
    }

    $events = Peminjaman::where('gedung_id', $selectedGedungId)
        ->select('id', 'judul_kegiatan', 'tgl_kegiatan', 'waktu_mulai', 'waktu_berakhir', 'organisasi')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->organisasi ? "({$item->organisasi})" : '(MAHASISWA)',
                'start' => "{$item->tgl_kegiatan}T{$item->waktu_mulai}",
                'end' => "{$item->tgl_kegiatan}T{$item->waktu_berakhir}"
            ];
        });

    return view('pages.landing', compact('gedungs', 'selectedGedungId', 'events'));
})->name('landing'); */

Route::get('/', fn () => view('auth.login'))->name('login');

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
        'admin'     => redirect()->route('admin.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'bem'       => redirect()->route('bem.dashboard'),
        'dosen'     => redirect()->route('dosen.dashboard'),
        default     => redirect()->route('login'),
    };
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ========== NON-AUTH ROUTES (untuk popup/detail yang tidak masuk prefix auth) ==========
Route::get('/sarpras/peminjaman/{id}/detail', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.detail');
Route::get('/admin/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.show');
Route::get('/kalender', [KalenderController::class, 'index'])->middleware('auth')->name('kalender.index');
Route::get('/mahasiswa/peminjaman/{id}', [MahasiswaController::class, 'show']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/bem/dashboard', [BemController::class, 'dashboard']);
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('mahasiswa.peminjaman');
Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

// ========== AUTH GROUP ==========
Route::middleware(['auth'])->group(function () {

    // === ADMIN ===
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('admin.fasilitas');
        Route::post('/fasilitas/store', [FasilitasController::class, 'store'])->name('admin.fasilitas.store');
        Route::put('/fasilitas/{id}', [FasilitasController::class, 'update'])->name('admin.fasilitas.update');
        Route::delete('/fasilitas/{id}', [FasilitasController::class, 'destroy'])->name('admin.fasilitas.destroy');
        Route::get('/peminjaman/{id}/detail', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.detail');
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('admin.peminjaman');
        Route::post('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
        Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        Route::post('/peminjaman/{id}/verifikasi', [AdminPeminjamanController::class, 'verifikasi'])->name('admin.peminjaman.verifikasi');
        Route::post('/ruangan/update', [AdminController::class, 'update'])->name('admin.ruangan.update');
    });

    // === MAHASISWA ===
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
        Route::get('/fasilitas', fn () => view('pages.mahasiswa.fasilitas'))->name('mahasiswa.fasilitas');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('mahasiswa.peminjaman');
        Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');
        Route::patch('/peminjaman/{id}/ambil', [PeminjamanController::class, 'ambil'])->name('mahasiswa.peminjaman.ambil');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('mahasiswa.peminjaman.kembalikan');
        Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('mahasiswa.peminjaman.show');

        Route::get('/auditorium', fn () => view('pages.mahasiswa.kalender', [
            'title' => 'Auditorium',
            'breadcrumb' => 'Dashboard > Ruangan > Auditorium'
        ]))->name('mahasiswa.auditorium');

        Route::get('/gsg', fn () => view('pages.mahasiswa.kalender', [
            'title' => 'Main Hall GSG',
            'breadcrumb' => 'Dashboard > Ruangan > Main Hall GSG'
        ]))->name('mahasiswa.gsg');

        Route::get('/gor', fn () => view('pages.mahasiswa.kalender', [
            'title' => 'GOR',
            'breadcrumb' => 'Dashboard > Ruangan > GOR'
        ]))->name('mahasiswa.gor');
    });

    // === BEM ===
    Route::prefix('bem')->group(function () {
        Route::get('/dashboard', [BemController::class, 'dashboard'])->name('bem.dashboard');
        Route::get('/peminjaman', [BemPeminjamanController::class, 'index'])->name('bem.peminjaman');
        Route::post('/peminjaman/{id}/approve', [BemPeminjamanController::class, 'approve'])->name('bem.peminjaman.approve');
        Route::get('/peminjaman/{id}/detail', [BemPeminjamanController::class, 'show'])->name('bem.peminjaman.show');
        Route::post('/peminjaman/{id}/verifikasi', [BemPeminjamanController::class, 'verifikasi'])->name('bem.peminjaman.verifikasi');
    });

    // === DOSEN ===
    Route::get('/dosen/dashboard', fn () => view('pages.dosen.dashboard'))->name('dosen.dashboard');

    // === FORM DAN API ===
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show']);
    Route::get('/fasilitas/ajukan', fn () => view('pages.mahasiswa.peminjaman.create'))->name('peminjaman.create');
    Route::get('/api/barang', [PeminjamanController::class, 'getBarangByRuangan']);
    Route::get('/api/ruangan/{slug}', [AdminController::class, 'getGedung']);

    // === NOTIFIKASI ===
    Route::get('/notif/list', function () {
        return Notifikasi::where('user_id', Auth::id())->latest()->take(10)->get(['judul', 'pesan']);
    });

    // === DOWNLOAD RIWAYAT ===
    Route::get('/download-riwayat', function () {
        $data = DB::table('peminjaman')->get();
        if ($data->isEmpty()) return response('Data kosong.', 404);

        $filename = "riwayat_peminjaman_" . now()->format('Ymd_His') . ".csv";
        return response()->streamDownload(function () use ($data) {
            ob_clean(); flush(); $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, array_keys((array) $data[0]), ';');
            foreach ($data as $row) fputcsv($file, (array) $row, ';');
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    })->name('download.riwayat');

    Route::get('/download-riwayat-bem', function () {
        $data = DB::table('peminjaman')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select('peminjaman.*', 'users.name as nama_user')
            ->where('verifikasi_bem', 'diterima')->get();

        return response()->streamDownload(function () use ($data) {
            ob_clean(); flush(); $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, array_keys((array) $data[0]), ';');
            foreach ($data as $row) fputcsv($file, (array) $row, ';');
            fclose($file);
        }, "riwayat_bem_" . now()->format('Ymd_His') . ".csv", [
            'Content-Type' => 'text/csv',
        ]);
    })->name('download.riwayat.bem');

    Route::get('/download-riwayat-admin', function () {
        $data = DB::table('peminjaman')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->select('peminjaman.*', 'users.name as nama_user')
            ->where('verifikasi_sarpras', 'diterima')->get();

        return response()->streamDownload(function () use ($data) {
            ob_clean(); flush(); $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, array_keys((array) $data[0]), ';');
            foreach ($data as $row) fputcsv($file, (array) $row, ';');
            fclose($file);
        }, "riwayat_admin_" . now()->format('Ymd_His') . ".csv", [
            'Content-Type' => 'text/csv',
        ]);
    })->name('download.riwayat.admin');
});
