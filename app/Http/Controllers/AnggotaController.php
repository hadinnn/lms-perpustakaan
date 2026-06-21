<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $anggota = Anggota::query()
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('telepon', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->withCount(['transaksi', 'transaksiAktif'])
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'search', 'status'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'              => 'required|string|size:16|unique:anggota,nik',
            'nama'             => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'nullable|date|before:today',
            'alamat'           => 'nullable|string|max:255',
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:100',
            'tanggal_bergabung' => 'required|date',
            'status'           => 'required|in:aktif,nonaktif',
        ], [
            'nik.required'   => 'NIK wajib diisi.',
            'nik.size'       => 'NIK harus tepat 16 digit.',
            'nik.unique'     => 'NIK sudah terdaftar.',
            'nama.required'  => 'Nama wajib diisi.',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Anggota $anggota)
    {
        $transaksi = $anggota->transaksi()
            ->with('buku')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('anggota.show', compact('anggota', 'transaksi'));
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nik'              => 'required|string|size:16|unique:anggota,nik,' . $anggota->id,
            'nama'             => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'nullable|date|before:today',
            'alamat'           => 'nullable|string|max:255',
            'telepon'          => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:100',
            'tanggal_bergabung' => 'required|date',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        // Hanya Admin yang boleh menghapus data anggota
        Gate::authorize('hapus-anggota');

        // Validasi: tidak boleh hapus jika ada transaksi aktif
        if ($anggota->transaksiAktif()->exists()) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena memiliki transaksi aktif.');
        }

        // Validasi: ada denda belum dibayar
        $dendaBelumLunas = Transaksi::where('anggota_id', $anggota->id)
            ->where('denda_dibayar', false)
            ->where('denda', '>', 0)
            ->exists();

        if ($dendaBelumLunas) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena memiliki tunggakan denda yang belum dilunasi.');
        }

        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
}
