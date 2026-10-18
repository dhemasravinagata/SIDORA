<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RumahSakitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect dashboard utama sesuai peran pengguna
Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }
    return match (Auth::user()->role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'petugas'    => redirect()->route('petugas.dashboard'),
        'rumahsakit' => redirect()->route('rs.dashboard'),
        default      => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

// Halaman index / publik
Route::match(['get', 'post'], '/', function (Request $request) {
    if ($request->has('page')) {
        return handleLegacyPage($request);
    }
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.index');
})->name('home');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register-rs', [AuthController::class, 'showRegisterRs'])->name('register.rs');
Route::post('/register-rs', [AuthController::class, 'registerRs'])->name('register.rs.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------------
// Rute Role Admin
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/stok-darah', [AdminController::class, 'stokDarah'])->name('stok-darah');
    Route::post('/alert-stok', [AdminController::class, 'alertStok'])->name('alert-stok');
    Route::post('/pesan-stok', [AdminController::class, 'pesanStok'])->name('pesan-stok');
    Route::get('/permintaan-darah', [AdminController::class, 'permintaanDarah'])->name('permintaan-darah');
    Route::match(['get', 'post'], '/terima-permintaan', [AdminController::class, 'terimaPermintaan'])->name('terima-permintaan');
    Route::post('/tolak-permintaan', [AdminController::class, 'tolakPermintaan'])->name('tolak-permintaan');
    Route::post('/kirim-permintaan', [AdminController::class, 'kirimPermintaan'])->name('kirim-permintaan');
    Route::get('/kelola-petugas', [AdminController::class, 'kelolaPetugas'])->name('kelola-petugas');
    Route::get('/form-petugas', [AdminController::class, 'formPetugas'])->name('form-petugas');
    Route::post('/tambah-petugas', [AdminController::class, 'tambahPetugasProcess'])->name('tambah-petugas');
    Route::post('/edit-petugas', [AdminController::class, 'editPetugasProcess'])->name('edit-petugas');
    Route::match(['get', 'post'], '/hapus-petugas', [AdminController::class, 'hapusPetugasProcess'])->name('hapus-petugas');
    Route::match(['get', 'post'], '/hapus-rs', [AdminController::class, 'hapusRsProcess'])->name('hapus-rs');
    Route::post('/approve-rs', [AdminController::class, 'approveRs'])->name('approve-rs');
    Route::get('/jadwal-donor', [AdminController::class, 'jadwalDonor'])->name('jadwal-donor');
    Route::get('/form-jadwal', [AdminController::class, 'formJadwal'])->name('form-jadwal');
    Route::post('/tambah-jadwal', [AdminController::class, 'formJadwalProcess'])->name('tambah-jadwal');
    Route::post('/edit-jadwal', [AdminController::class, 'editJadwalProcess'])->name('edit-jadwal');
    Route::match(['get', 'post'], '/hapus-jadwal', [AdminController::class, 'hapusJadwalProcess'])->name('hapus-jadwal');
    Route::get('/daftar-pendonor', [AdminController::class, 'daftarPendonor'])->name('daftar-pendonor');
    Route::get('/export-semua', [AdminController::class, 'exportSemuaLaporan'])->name('export-semua');
    Route::get('/export-permintaan', [AdminController::class, 'exportPermintaanLaporan'])->name('export-permintaan');
});

// -------------------------------------------------------------
// Rute Role Petugas
// -------------------------------------------------------------
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/daftar-pendonor', [PetugasController::class, 'daftarPendonor'])->name('daftar-pendonor');
    Route::get('/form-pendonor', [PetugasController::class, 'formPendonor'])->name('form-pendonor');
    Route::post('/tambah-pendonor', [PetugasController::class, 'tambahPendonorProcess'])->name('tambah-pendonor');
    Route::post('/edit-pendonor', [PetugasController::class, 'editPendonorProcess'])->name('edit-pendonor');
    Route::match(['get', 'post'], '/hapus-pendonor', [PetugasController::class, 'hapusPendonorProcess'])->name('hapus-pendonor');
    Route::get('/riwayat-donasi', [PetugasController::class, 'riwayatDonasi'])->name('riwayat-donasi');
    Route::get('/form-riwayat', [PetugasController::class, 'formRiwayat'])->name('form-riwayat');
    Route::post('/tambah-riwayat', [PetugasController::class, 'formRiwayatProcess'])->name('tambah-riwayat');
    Route::post('/edit-riwayat', [PetugasController::class, 'editRiwayatProcess'])->name('edit-riwayat');
    Route::match(['get', 'post'], '/hapus-riwayat', [PetugasController::class, 'hapusRiwayatProcess'])->name('hapus-riwayat');
    Route::get('/jadwal-donor', [PetugasController::class, 'jadwalDonor'])->name('jadwal-donor');
    Route::get('/stok-darah', [PetugasController::class, 'stokDarah'])->name('stok-darah');
});

// -------------------------------------------------------------
// Rute Role Rumah Sakit
// -------------------------------------------------------------
Route::middleware(['auth', 'role:rumahsakit'])->prefix('rs')->name('rs.')->group(function () {
    Route::get('/dashboard', [RumahSakitController::class, 'dashboard'])->name('dashboard');
    Route::get('/permintaan', [RumahSakitController::class, 'permintaan'])->name('permintaan');
    Route::post('/permintaan', [RumahSakitController::class, 'permintaanStore'])->name('permintaan.store');
    Route::get('/history-permintaan', [RumahSakitController::class, 'historyPermintaan'])->name('history-permintaan');
    Route::match(['get', 'post'], '/konfirmasi-terima', [RumahSakitController::class, 'konfirmasiTerima'])->name('konfirmasi-terima');
    Route::get('/stok-darah', [RumahSakitController::class, 'stokDarah'])->name('stok-darah');
    Route::get('/profil', [RumahSakitController::class, 'profil'])->name('profil');
    Route::post('/profil', [RumahSakitController::class, 'profilUpdate'])->name('profil.update');
    Route::post('/ubah-password', [RumahSakitController::class, 'ubahPassword'])->name('ubah-password');
});

// -------------------------------------------------------------
// Dispatcher Kompatibilitas URL Lama: index.php?page=...
// -------------------------------------------------------------
Route::match(['get', 'post'], '/index.php/{path?}', function (Request $request, ?string $path = null) {
    $cleanPath = trim((string) $path, '/');
    if ($cleanPath === 'login') {
        if ($request->isMethod('POST')) {
            return app(AuthController::class)->login($request);
        }
        return app(AuthController::class)->showLogin();
    }
    if ($cleanPath === 'register-rs') {
        if ($request->isMethod('POST')) {
            return app(AuthController::class)->registerRs($request);
        }
        return app(AuthController::class)->showRegisterRs();
    }
    if ($cleanPath === 'logout') {
        return app(AuthController::class)->logout($request);
    }
    return handleLegacyPage($request);
})->where('path', '.*');

Route::match(['get', 'post'], '{prefix}/index.php/{path?}', function (Request $request, string $prefix, ?string $path = null) {
    $cleanPath = trim((string) $path, '/');
    if ($cleanPath === 'login') {
        if ($request->isMethod('POST')) {
            return app(AuthController::class)->login($request);
        }
        return app(AuthController::class)->showLogin();
    }
    if ($cleanPath === 'register-rs') {
        if ($request->isMethod('POST')) {
            return app(AuthController::class)->registerRs($request);
        }
        return app(AuthController::class)->showRegisterRs();
    }
    return handleLegacyPage($request);
})->where('path', '.*');

// Fallback aset agar tidak 404 saat diakses dari sub-path (/admin/assets/..., /index.php/..., dll)
Route::get('assets/{path}', function (string $path) {
    $fullPath = public_path('assets/' . $path);
    if (file_exists($fullPath) && is_file($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*');

Route::get('{prefix}/assets/{path}', function (string $prefix, string $path) {
    $fullPath = public_path('assets/' . $path);
    if (file_exists($fullPath) && is_file($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*');

if (!function_exists('handleLegacyPage')) {
function handleLegacyPage(Request $request)
{
    $page = $request->query('page', $request->input('page', 'auth-index'));

    // 1. Rute Otentikasi & Umum
    if ($page === 'auth-index') {
        return view('auth.index');
    }
    if ($page === 'login') {
        return app(AuthController::class)->showLogin();
    }
    if ($page === 'login-process') {
        return app(AuthController::class)->login($request);
    }
    if ($page === 'register-rs') {
        return app(AuthController::class)->showRegisterRs();
    }
    if ($page === 'register-rs-process') {
        return app(AuthController::class)->registerRs($request);
    }
    if ($page === 'logout') {
        return app(AuthController::class)->logout($request);
    }

    // 2. Rute Admin
    $adminPages = [
        'admin-dashboard'           => ['dashboard', 'GET'],
        'admin-stok-darah'          => ['stokDarah', 'GET'],
        'admin-permintaan-darah'    => ['permintaanDarah', 'GET'],
        'admin-kelola-petugas'      => ['kelolaPetugas', 'GET'],
        'admin-jadwal-donor'        => ['jadwalDonor', 'GET'],
        'admin-form-jadwal'         => ['formJadwal', 'GET'],
        'admin-form-petugas'        => ['formPetugas', 'GET'],
        'admin-daftar-pendonor'     => ['daftarPendonor', 'GET'],
        'admin-form-jadwal-process' => ['formJadwalProcess', 'POST'],
        'admin-edit-jadwal'         => ['editJadwalProcess', 'POST'],
        'admin-hapus-jadwal'        => ['hapusJadwalProcess', 'ANY'],
        'admin-approve-rs'          => ['approveRs', 'POST'],
        'admin-hapus-rs'            => ['hapusRsProcess', 'ANY'],
        'admin-tambah-petugas'      => ['tambahPetugasProcess', 'POST'],
        'admin-edit-petugas'        => ['editPetugasProcess', 'POST'],
        'admin-hapus-petugas'       => ['hapusPetugasProcess', 'ANY'],
        'admin-terima-permintaan'   => ['terimaPermintaan', 'ANY'],
        'admin-tolak-permintaan'    => ['tolakPermintaan', 'POST'],
        'admin-permintaan-process'  => ['tolakPermintaan', 'POST'],
        'admin-kirim-permintaan'    => ['kirimPermintaan', 'POST'],
        'admin-permintaan-kirim'    => ['kirimPermintaan', 'POST'],
        'admin-alert-stok'          => ['alertStok', 'ANY'],
        'admin-pesan-stok'          => ['pesanStok', 'POST'],
        'admin-export-semua'        => ['exportSemuaLaporan', 'GET'],
        'admin-export-permintaan'   => ['exportPermintaanLaporan', 'GET'],
    ];

    if (isset($adminPages[$page])) {
        if (! Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses menu Administrator.');
        }
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Administrator.');
        }
        [$method] = $adminPages[$page];
        return app(AdminController::class)->$method($request);
    }

    // 3. Rute Petugas
    $petugasPages = [
        'petugas-dashboard'           => 'dashboard',
        'petugas-daftar-pendonor'     => 'daftarPendonor',
        'petugas-riwayat-donasi'      => 'riwayatDonasi',
        'petugas-jadwal-donor'        => 'jadwalDonor',
        'petugas-stok-darah'          => 'stokDarah',
        'petugas-form-riwayat'        => 'formRiwayat',
        'petugas-form-pendonor'       => 'formPendonor',
        'petugas-tambah-pendonor'     => 'tambahPendonorProcess',
        'petugas-edit-pendonor'       => 'editPendonorProcess',
        'petugas-hapus-pendonor'      => 'hapusPendonorProcess',
        'petugas-form-riwayat-process'=> 'formRiwayatProcess',
        'petugas-edit-riwayat'        => 'editRiwayatProcess',
        'petugas-hapus-riwayat'       => 'hapusRiwayatProcess',
    ];

    if (isset($petugasPages[$page])) {
        if (! Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses menu Petugas.');
        }
        if (Auth::user()->role !== 'petugas') {
            abort(403, 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Petugas SIDORA.');
        }
        $method = $petugasPages[$page];
        return app(PetugasController::class)->$method($request);
    }

    // 4. Rute Rumah Sakit
    $rsPages = [
        'rs-dashboard'          => 'dashboard',
        'rs-permintaan'         => 'permintaan',
        'rs-permintaan-store'   => 'permintaanStore',
        'rs-history-permintaan' => 'historyPermintaan',
        'rs-konfirmasi-terima'  => 'konfirmasiTerima',
        'rs-terima-permintaan'  => 'konfirmasiTerima',
        'rs-stok-darah'         => 'stokDarah',
        'rs-profil'             => 'profil',
        'rs-profil-update'      => 'profilUpdate',
        'rs-ubah-password'      => 'ubahPassword',
    ];

    if (isset($rsPages[$page])) {
        if (! Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mengakses portal Rumah Sakit.');
        }
        if (Auth::user()->role !== 'rumahsakit') {
            abort(403, 'Akses Ditolak: Halaman ini hanya diperuntukkan bagi Akun Rumah Sakit.');
        }
        $method = $rsPages[$page];
        return app(RumahSakitController::class)->$method($request);
    }

    abort(404, "Halaman '{$page}' tidak ditemukan dalam sistem SIDORA.");
}
}
