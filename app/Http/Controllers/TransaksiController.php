<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        // Active/ongoing transactions
        $transaksiAktif = Transaksi::with(['anggota', 'buku', 'petugas'])
            ->where('status', 'dipinjam')
            ->orWhere('status', 'terlambat')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($t) {
                if ($t->status === 'dipinjam' && Carbon::today()->gt($t->tanggal_jatuh_tempo)) {
                    $t->status = 'terlambat';
                    $t->save();
                }
                return $t;
            });

        return view('transaksi.index', compact('transaksiAktif'));
    }

    public function peminjaman(Request $request)
    {
        $request->validate([
            'nik'  => 'required|string',
            'isbn' => 'required|string',
        ], [
            'nik.required'  => 'NIK anggota wajib diisi.',
            'isbn.required' => 'ISBN buku wajib diisi.',
        ]);

        $anggota = Anggota::where('nik', $request->nik)->first();
        if (!$anggota) {
            return back()->withErrors(['nik' => 'Anggota dengan NIK tersebut tidak ditemukan.'])->withInput();
        }

        if ($anggota->status !== 'aktif') {
            return back()->withErrors(['nik' => 'Anggota tidak aktif, tidak dapat meminjam buku.'])->withInput();
        }

        // Cek maksimum pinjaman (3 buku)
        $jumlahPinjaman = $anggota->transaksiAktif()->count();
        if ($jumlahPinjaman >= 3) {
            return back()->withErrors(['nik' => 'Anggota sudah mencapai batas maksimum peminjaman (3 buku).'])->withInput();
        }

        $buku = Buku::where('isbn', $request->isbn)->first();
        if (!$buku) {
            return back()->withErrors(['isbn' => 'Buku dengan ISBN tersebut tidak ditemukan.'])->withInput();
        }

        if ($buku->stok_tersedia <= 0) {
            return back()->withErrors(['isbn' => 'Buku sedang tidak tersedia (stok habis).'])->withInput();
        }

        // Cek apakah anggota sudah pinjam buku yang sama
        $sudahPinjam = Transaksi::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors(['isbn' => 'Anggota sudah meminjam buku ini dan belum dikembalikan.'])->withInput();
        }

        // Cek denda belum lunas
        $dendaBelumLunas = Transaksi::where('anggota_id', $anggota->id)
            ->where('denda_dibayar', false)
            ->where('denda', '>', 0)
            ->exists();

        if ($dendaBelumLunas) {
            return back()->withErrors(['nik' => 'Anggota memiliki denda yang belum dilunasi. Selesaikan denda terlebih dahulu.'])->withInput();
        }

        DB::transaction(function () use ($anggota, $buku) {
            $durasiPinjam = (int) env('BORROW_DURATION_DAYS', 14);

            Transaksi::create([
                'kode_transaksi'      => Transaksi::generateKode(),
                'anggota_id'          => $anggota->id,
                'buku_id'             => $buku->id,
                'petugas_id'          => Auth::id(),
                'tanggal_pinjam'      => Carbon::today(),
                'tanggal_jatuh_tempo' => Carbon::today()->addDays($durasiPinjam),
                'status'              => 'dipinjam',
            ]);

            $buku->decrement('stok_tersedia');
            $buku->updateStatusStok();
        });

        return redirect()->route('transaksi.index')
            ->with('success', "Peminjaman buku \"{$buku->judul}\" untuk anggota {$anggota->nama} berhasil dicatat.");
    }

    public function pengembalian(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|string',
        ], [
            'kode_transaksi.required' => 'Kode transaksi atau ISBN wajib diisi.',
        ]);

        // Search by kode_transaksi or ISBN
        $transaksi = Transaksi::with(['anggota', 'buku'])
            ->where('kode_transaksi', $request->kode_transaksi)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->first();

        if (!$transaksi) {
            // Try by ISBN
            $buku = Buku::where('isbn', $request->kode_transaksi)->first();
            if ($buku) {
                $transaksi = Transaksi::with(['anggota', 'buku'])
                    ->where('buku_id', $buku->id)
                    ->whereIn('status', ['dipinjam', 'terlambat'])
                    ->latest()
                    ->first();
            }
        }

        if (!$transaksi) {
            return back()->withErrors(['kode_transaksi' => 'Transaksi aktif tidak ditemukan.'])->withInput();
        }

        $today = Carbon::today();
        $denda = $transaksi->hitungDenda();

        DB::transaction(function () use ($transaksi, $today, $denda) {
            $transaksi->update([
                'tanggal_kembali' => $today,
                'status'          => 'dikembalikan',
                'denda'           => $denda,
                'denda_dibayar'   => $denda === 0,
            ]);

            $buku = $transaksi->buku;
            $buku->increment('stok_tersedia');
            $buku->updateStatusStok();
        });

        $msg = "Buku \"{$transaksi->buku->judul}\" berhasil dikembalikan.";
        if ($denda > 0) {
            $msg .= " Denda: Rp " . number_format($denda, 0, ',', '.');
        }

        return redirect()->route('transaksi.index')->with('success', $msg);
    }

    public function cariNik(Request $request)
    {
        $nik     = $request->get('nik');
        $anggota = Anggota::where('nik', $nik)->first();

        if (!$anggota) {
            return response()->json(['found' => false, 'message' => 'Anggota tidak ditemukan.']);
        }

        return response()->json([
            'found'   => true,
            'anggota' => [
                'id'      => $anggota->id,
                'nik'     => $anggota->nik,
                'nama'    => $anggota->nama,
                'status'  => $anggota->status,
                'pinjaman_aktif' => $anggota->transaksiAktif()->count(),
            ],
        ]);
    }

    public function bayarDenda(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->denda <= 0 || $transaksi->denda_dibayar) {
            return back()->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        $transaksi->update(['denda_dibayar' => true]);

        return back()->with('success', "Denda sebesar Rp " . number_format($transaksi->denda, 0, ',', '.') . " berhasil dibayar.");
    }
}
