<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ───────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Protected Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Manajemen Anggota ────────────────────────────────────────────
    // Explicit routes to avoid Laravel pluralizer bug: 'Anggota' → {anggotum}
    Route::get('/anggota',              [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create',       [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota',             [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{anggota}',    [AnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/anggota/{anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{anggota}',    [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{anggota}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

    // ─── Katalog Buku ─────────────────────────────────────────────────
    Route::resource('buku', BukuController::class);
    Route::get('/buku/cari/isbn', [BukuController::class, 'cariIsbn'])->name('buku.cariIsbn');

    // ─── Transaksi ────────────────────────────────────────────────────
    Route::get('/transaksi',        [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/pinjam', [TransaksiController::class, 'peminjaman'])->name('transaksi.peminjaman');
    Route::post('/transaksi/kembali', [TransaksiController::class, 'pengembalian'])->name('transaksi.pengembalian');
    Route::post('/transaksi/{transaksi}/bayar-denda', [TransaksiController::class, 'bayarDenda'])->name('transaksi.bayarDenda');
    Route::get('/transaksi/cari/nik', [TransaksiController::class, 'cariNik'])->name('transaksi.cariNik');

    // ─── Riwayat ──────────────────────────────────────────────────────
    Route::get('/riwayat',          [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{transaksi}', [RiwayatController::class, 'show'])->name('riwayat.show');
});
