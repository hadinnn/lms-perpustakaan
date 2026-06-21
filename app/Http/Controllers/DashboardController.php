<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Stats
        $totalBuku      = Buku::sum('stok_total');
        $totalAnggota   = Anggota::where('status', 'aktif')->count();
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();
        $totalDenda     = Transaksi::where('denda_dibayar', false)->where('denda', '>', 0)->sum('denda');

        // Recent activity (last 10)
        $aktivitasTerbaru = Transaksi::with(['anggota', 'buku'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Overdue transactions
        $terlambat = Transaksi::where('status', 'terlambat')
            ->orWhere(function ($q) use ($today) {
                $q->where('status', 'dipinjam')
                  ->where('tanggal_jatuh_tempo', '<', $today);
            })
            ->count();

        // Near due (H-1 and H-2)
        $mendekatiJatuhTempo = Transaksi::where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<=', $today->copy()->addDays(2))
            ->whereDate('tanggal_jatuh_tempo', '>=', $today)
            ->with(['anggota', 'buku'])
            ->get();

        // Greeting by time
        $jam = now()->setTimezone('Asia/Jakarta')->hour;
        $salam = match(true) {
            $jam >= 5  && $jam < 12 => 'Selamat Pagi',
            $jam >= 12 && $jam < 15 => 'Selamat Siang',
            $jam >= 15 && $jam < 19 => 'Selamat Sore',
            default                  => 'Selamat Malam',
        };

        // ── Chart 1: Transaksi per bulan (6 bulan terakhir) ─────────────────────
        $chartLabels = [];
        $chartPinjam = [];
        $chartKembali = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->startOfMonth()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M Y');

            $chartPinjam[] = Transaksi::whereYear('tanggal_pinjam', $bulan->year)
                ->whereMonth('tanggal_pinjam', $bulan->month)
                ->count();

            $chartKembali[] = Transaksi::whereNotNull('tanggal_kembali')
                ->whereYear('tanggal_kembali', $bulan->year)
                ->whereMonth('tanggal_kembali', $bulan->month)
                ->count();
        }

        // ── Chart 2: Status stok buku (donut) ───────────────────────────────────
        $bukuTersedia  = Buku::where('status', 'tersedia')->count();
        $bukuHabis     = Buku::where('status', 'habis')->count();
        $bukuDipinjam  = Transaksi::where('status', 'dipinjam')
                            ->orWhere('status', 'terlambat')
                            ->distinct('buku_id')->count('buku_id');

        return view('dashboard.index', compact(
            'totalBuku',
            'totalAnggota',
            'transaksiHariIni',
            'totalDenda',
            'aktivitasTerbaru',
            'terlambat',
            'mendekatiJatuhTempo',
            'salam',
            // Chart data
            'chartLabels',
            'chartPinjam',
            'chartKembali',
            'bukuTersedia',
            'bukuHabis',
            'bukuDipinjam'
        ));
    }
}

