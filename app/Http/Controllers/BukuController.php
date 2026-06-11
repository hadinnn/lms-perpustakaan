<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $kategori = $request->get('kategori');
        $status   = $request->get('status');

        $buku = Buku::with('kategori')
            ->when($search, fn($q) => $q->where('judul', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%")
                ->orWhere('pengarang', 'like', "%{$search}%"))
            ->when($kategori, fn($q) => $q->where('kategori_id', $kategori))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('judul')
            ->paginate(15)
            ->withQueryString();

        $kategoris = KategoriBuku::orderBy('nama')->get();

        return view('buku.index', compact('buku', 'kategoris', 'search', 'kategori', 'status'));
    }

    public function create()
    {
        $kategoris = KategoriBuku::orderBy('nama')->get();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn'         => 'required|string|max:20|unique:buku,isbn',
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:150',
            'penerbit'     => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'  => 'nullable|exists:kategori_buku,id',
            'stok_total'   => 'required|integer|min:1',
            'lokasi_rak'   => 'nullable|string|max:20',
            'deskripsi'    => 'nullable|string',
        ], [
            'isbn.unique' => 'ISBN sudah terdaftar dalam sistem.',
        ]);

        $validated['stok_tersedia'] = $validated['stok_total'];
        $validated['status'] = 'tersedia';

        Buku::create($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan ke katalog.');
    }

    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'transaksi.anggota']);
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = KategoriBuku::orderBy('nama')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'isbn'         => 'required|string|max:20|unique:buku,isbn,' . $buku->id,
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:150',
            'penerbit'     => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'  => 'nullable|exists:kategori_buku,id',
            'stok_total'   => 'required|integer|min:1',
            'lokasi_rak'   => 'nullable|string|max:20',
            'deskripsi'    => 'nullable|string',
        ]);

        // Recalculate available stock
        $dipinjam = $buku->stok_total - $buku->stok_tersedia;
        $validated['stok_tersedia'] = max(0, $validated['stok_total'] - $dipinjam);
        $validated['status'] = $validated['stok_tersedia'] > 0 ? 'tersedia' : 'habis';

        $buku->update($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->transaksi()->where('status', 'dipinjam')->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang dipinjam.');
        }

        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus dari katalog.');
    }

    public function cariIsbn(Request $request)
    {
        $isbn = $request->get('isbn');
        $buku = Buku::with('kategori')->where('isbn', $isbn)->first();

        if (!$buku) {
            return response()->json(['found' => false, 'message' => 'Buku tidak ditemukan.']);
        }

        return response()->json([
            'found' => true,
            'buku'  => [
                'id'            => $buku->id,
                'isbn'          => $buku->isbn,
                'judul'         => $buku->judul,
                'pengarang'     => $buku->pengarang,
                'stok_tersedia' => $buku->stok_tersedia,
                'status'        => $buku->status,
                'lokasi_rak'    => $buku->lokasi_rak,
            ],
        ]);
    }
}
