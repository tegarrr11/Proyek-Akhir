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
use App\Http\Controllers\DosenPeminjamanController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\SocialiteController;
use App\Models\Fasilitas;

// ========== LANDING PAGE ==========
Route::get('/', function () {
    $gedungs = Gedung::all();

    $selectedGedungId = request('gedung_id') ?? ($gedungs->first()->id ?? null);

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
                'end' => "{$item->tgl_kegiatan}T{$item->waktu_berakhir}",
            ];
        });

    return view('pages.landing', compact('gedungs', 'selectedGedungId', 'events'));
})->name('landing');

Route::get('/login', fn() => view('auth.login'))->name('login');


Route::get('/auth/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');

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

Route::get('pegawai/list', function () {
    $response = Http::withHeaders([
        'apikey' => 'Ovk9PikyPmncW649C0vzEMmRWoOz20Ng',
        // 'Accept' => 'application/json'
    ])->asForm()->post('https://v2.api.pcr.ac.id/api/pegawai?collection=pegawai-aktif', [
        'collection' => 'pegawai-aktif'
    ]);
    return $response->json();
});

// ========== NON-AUTH ROUTES (untuk popup/detail yang tidak masuk prefix auth) ==========
Route::get('/sarpras/peminjaman/{id}/detail', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.detail');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/bem/dashboard', [BemController::class, 'dashboard']);
Route::get('/dosen/dashboard', [BemController::class, 'dashboard']);
Route::get('/dosen/peminjaman/create', [DosenPeminjamanController::class, 'create']);
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('mahasiswa.peminjaman');
Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::get('/api/fasilitas-tambahan', function () {
    return Fasilitas::where('gedung_id', 4)->where('stok', '>', 0)->get();
});
Route::post('/admin/fasilitas/import', [AdminController::class, 'importFasilitas'])->name('admin.fasilitas.import');
Route::patch('/mahasiswa/peminjaman/{id}', [App\Http\Controllers\PeminjamanController::class, 'update'])->name('mahasiswa.peminjaman.update');
Route::patch('/admin/peminjaman/{id}/selesai', [PeminjamanController::class, 'selesai'])->name('admin.peminjaman.selesai');
Route::patch('/admin/peminjaman/{id}/selesai', [PeminjamanController::class, 'selesai']);
Route::prefix('admin')->name('admin.')->group(function () {
  Route::patch('peminjaman/{peminjaman}/pending', [\App\Http\Controllers\PeminjamanController::class, 'pending'])
    ->name('peminjaman.pending');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::patch('peminjaman/{peminjaman}/ajukan', [PeminjamanController::class, 'ajukan'])
        ->name('peminjaman.ajukan');
});

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
        Route::get('/peminjaman/create', function () {
            return view('pages.admin.peminjaman.create');
        })->name('admin.peminjaman.create');
        Route::post('/peminjaman/store', [AdminPeminjamanController::class, 'store'])->name('admin.peminjaman.store');
        Route::patch('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
        Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        Route::post('/peminjaman/{id}/verifikasi', [AdminPeminjamanController::class, 'verifikasi'])->name('admin.peminjaman.verifikasi');
        Route::post('/ruangan/update', [AdminController::class, 'update'])->name('admin.ruangan.update');
        Route::get('/peminjaman/download-proposal/{id}', [PeminjamanController::class, 'downloadProposal'])->middleware('auth')->name('admin.peminjaman.downloadProposal');
        Route::patch('/admin/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'adminKembalikan'])->name('admin.peminjaman.kembalikan');
        Route::patch('/peminjaman/{id}/ambil', [PeminjamanController::class, 'ambilBarang'])->name('admin.peminjaman.ambil');
        Route::patch('/peminjaman/{id}/selesai', [PeminjamanController::class, 'selesaiPeminjaman'])->name('admin.peminjaman.selesai');
        Route::patch('/peminjaman/{id}/ambil', [AdminPeminjamanController::class, 'ambilBarang'])->name('admin.peminjaman.ambil');
        Route::patch('/peminjaman/{id}/selesai', [AdminPeminjamanController::class, 'selesaiPeminjaman'])->name('admin.peminjaman.selesai');
    });

    // === MAHASISWA ===
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
        Route::get('/fasilitas', fn() => view('pages.mahasiswa.fasilitas'))->name('mahasiswa.fasilitas');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('mahasiswa.peminjaman');
        Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');
        Route::patch('/peminjaman/{id}/ambil', [PeminjamanController::class, 'ambil'])->name('mahasiswa.peminjaman.ambil');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('mahasiswa.peminjaman.kembalikan');
        Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('mahasiswa.peminjaman.show');
        Route::patch('/mahasiswa/peminjaman/ambil/{id}', [PeminjamanController::class, 'ambil'])->name('mahasiswa.peminjaman.ambil');
        Route::get('/auditorium', fn() => view('pages.mahasiswa.kalender', [
            'title' => 'Auditorium',
            'breadcrumb' => 'Dashboard > Ruangan > Auditorium'
        ]))->name('mahasiswa.auditorium');

        Route::get('/gsg', fn() => view('pages.mahasiswa.kalender', [
            'title' => 'Main Hall GSG',
            'breadcrumb' => 'Dashboard > Ruangan > Main Hall GSG'
        ]))->name('mahasiswa.gsg');

        Route::get('/gor', fn() => view('pages.mahasiswa.kalender', [
            'title' => 'GOR',
            'breadcrumb' => 'Dashboard > Ruangan > GOR'
        ]))->name('mahasiswa.gor');

        Route::get('/peminjaman/download-proposal/{id}', [PeminjamanController::class, 'downloadProposal'])->middleware('auth')->name('mahasiswa.peminjaman.downloadProposal');
    });

    // === BEM ===
    Route::prefix('bem')->group(function () {
        Route::get('/dashboard', [BemController::class, 'dashboard'])->name('bem.dashboard');
        Route::get('/peminjaman', [BemPeminjamanController::class, 'index'])->name('bem.peminjaman');
        Route::get('/bem/peminjaman/{id}', [BemPeminjamanController::class, 'show']);
        Route::post('/peminjaman/{id}/approve', [BemPeminjamanController::class, 'approve'])->name('bem.peminjaman.approve');
        Route::get('/peminjaman/{id}/detail', [BemPeminjamanController::class, 'show'])->name('bem.peminjaman.show');
        Route::post('/peminjaman/{id}/verifikasi', [BemPeminjamanController::class, 'verifikasi'])->name('bem.peminjaman.verifikasi');
        Route::patch('/bem/peminjaman/{id}/terima', [BEMPeminjamanController::class, 'terima'])->name('bem.peminjaman.terima');
        Route::get('/peminjaman/download-proposal/{id}', [PeminjamanController::class, 'downloadProposal'])->middleware('auth')->name('bem.peminjaman.downloadProposal');
    });

    // === DOSEN ===
    Route::prefix('dosen')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DosenController::class, 'dashboard'])->name('dosen.dashboard');
        Route::get('/peminjaman', [\App\Http\Controllers\DosenPeminjamanController::class, 'index'])->name('dosen.peminjaman');
        Route::get('/peminjaman/{id}', [\App\Http\Controllers\DosenPeminjamanController::class, 'show'])->name('dosen.peminjaman.show');
        Route::get('/peminjaman/create', [\App\Http\Controllers\DosenPeminjamanController::class, 'create'])->name('dosen.peminjaman.create');
        Route::post('/peminjaman/store', [\App\Http\Controllers\DosenPeminjamanController::class, 'store'])->name('dosen.peminjaman.store');
        Route::patch('/peminjaman/{id}/ambil', [\App\Http\Controllers\DosenPeminjamanController::class, 'ambil'])->name('dosen.peminjaman.ambil');
        Route::patch('/peminjaman/{id}/kembalikan', [\App\Http\Controllers\DosenPeminjamanController::class, 'kembalikan'])->name('dosen.peminjaman.kembalikan');
        Route::get('/peminjaman/download-proposal/{id}', [\App\Http\Controllers\PeminjamanController::class, 'downloadProposal'])->middleware('auth')->name('dosen.peminjaman.downloadProposal');
    });

    // === FORM DAN API ===
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show']);
    Route::get('/fasilitas/ajukan', fn() => view('pages.mahasiswa.peminjaman.create'))->name('peminjaman.create');
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
            ob_clean();
            flush();
            $file = fopen('php://output', 'w');
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
            ob_clean();
            flush();
            $file = fopen('php://output', 'w');
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
            ob_clean();
            flush();
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, array_keys((array) $data[0]), ';');
            foreach ($data as $row) fputcsv($file, (array) $row, ';');
            fclose($file);
        }, "riwayat_admin_" . now()->format('Ymd_His') . ".csv", [
            'Content-Type' => 'text/csv',
        ]);
    })->name('download.riwayat.admin');

    // === OPTIONAL: fallback route for download-proposal (if needed by JS without prefix) ===
    // Route::get('/peminjaman/download-proposal/{id}', [PeminjamanController::class, 'downloadProposal'])->middleware('auth')->name('peminjaman.downloadProposal');
});

Route::put('/admin/fasilitas/{id}', [FasilitasController::class, 'update'])->name('admin.fasilitas.update');
Route::patch('/mahasiswa/peminjaman/{id}/ambil', [MahasiswaController::class, 'ambilPeminjaman'])->name('mahasiswa.peminjaman.ambil');

// Route untuk fiturr diskusi
Route::middleware(['auth'])->group(function () {
    Route::post('/diskusi', [DiskusiController::class, 'store'])->name('diskusi.store');
});

Route::get('/ajax/fasilitas', function () {
    $slug = request('gedung');
    $gedung = \App\Models\Gedung::where('slug', $slug)->first();

    if (!$gedung) return response()->json([], 404);

    $fasilitas = \App\Models\Fasilitas::where('gedung_id', $gedung->id)
        ->where('stok', '>', 0)
        ->get(['id', 'nama_barang', 'stok']);

    return response()->json($fasilitas);
});

