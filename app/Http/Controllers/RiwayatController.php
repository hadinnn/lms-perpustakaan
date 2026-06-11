<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $status      = $request->get('status');
        $tglMulai    = $request->get('tgl_mulai');
        $tglAkhir    = $request->get('tgl_akhir');
        $tipe        = $request->get('tipe'); // peminjaman/pengembalian

        $riwayat = Transaksi::with(['anggota', 'buku', 'petugas'])
            ->when($search, fn($q) => $q->whereHas('anggota', fn($a) => $a->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
                ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$search}%")->orWhere('isbn', 'like', "%{$search}%"))
                ->orWhere('kode_transaksi', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($tglMulai, fn($q) => $q->whereDate('tanggal_pinjam', '>=', $tglMulai))
            ->when($tglAkhir, fn($q) => $q->whereDate('tanggal_pinjam', '<=', $tglAkhir))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $totalTransaksi  = Transaksi::count();
        $totalDendaBelumLunas = Transaksi::where('denda_dibayar', false)->where('denda', '>', 0)->sum('denda');

        return view('riwayat.index', compact(
            'riwayat',
            'search',
            'status',
            'tglMulai',
            'tglAkhir',
            'totalTransaksi',
            'totalDendaBelumLunas'
        ));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['anggota', 'buku', 'petugas']);
        return view('riwayat.show', compact('transaksi'));
    }
}
