<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan Tailwind untuk tampilan paginasi
        Paginator::useTailwind();

        // ── Authorization Gates ─────────────────────────────────────────────────
        // Gate ini mengatur apa yang boleh dilakukan oleh masing-masing role.
        // Admin: akses penuh ke semua fitur termasuk hapus data.
        // Pustakawan: bisa melihat, tambah, dan edit — tapi TIDAK bisa menghapus.

        // Hanya Admin yang boleh menghapus buku
        Gate::define('hapus-buku', function (User $user) {
            return $user->isAdmin();
        });

        // Hanya Admin yang boleh menghapus anggota
        Gate::define('hapus-anggota', function (User $user) {
            return $user->isAdmin();
        });

        // Hanya Admin yang boleh menambah atau mengedit data buku
        Gate::define('kelola-buku', function (User $user) {
            return $user->isAdmin() || $user->isPustakawan();
        });

        // Hanya Admin yang boleh menambah atau mengedit data anggota
        Gate::define('kelola-anggota', function (User $user) {
            return $user->isAdmin() || $user->isPustakawan();
        });

        // Semua petugas aktif boleh mencatat transaksi peminjaman dan pengembalian
        Gate::define('catat-transaksi', function (User $user) {
            return $user->isAdmin() || $user->isPustakawan();
        });

        // Hanya Admin yang dapat melihat dan mengelola laporan penuh
        Gate::define('lihat-laporan', function (User $user) {
            return $user->isAdmin();
        });
    }
}
