@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h2 class="text-headline-md font-bold text-on-surface">Riwayat Transaksi</h2>
        <p class="text-body-md text-on-surface-variant mt-0.5">Daftar rekaman seluruh transaksi peminjaman dan pengembalian buku.</p>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-fixed text-primary rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl icon-filled">history</span>
            </div>
            <div>
                <p class="text-caption text-on-surface-variant font-medium uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-headline-md font-bold mt-0.5">{{ number_format($totalTransaksi) }}</h3>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 text-red-800 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl icon-filled">payments</span>
            </div>
            <div>
                <p class="text-caption text-on-surface-variant font-medium uppercase tracking-wider">Total Denda Belum Dilunasi</p>
                <h3 class="text-headline-md font-bold mt-0.5 text-red-700">Rp {{ number_format($totalDendaBelumLunas, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="card">
        <form method="GET" action="{{ route('riwayat.index') }}" class="space-y-4">
            {{-- Row 1: Search & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="form-label font-semibold">Kata Kunci</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ $search }}"
                               placeholder="Cari Kode Transaksi, NIK, Nama Anggota, Judul Buku, ISBN..."
                               class="w-full pl-10 pr-4 py-2 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="form-label font-semibold">Status Peminjaman</label>
                    <select name="status" 
                            id="status" 
                            class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
            </div>

            {{-- Row 2: Date Pickers & Actions --}}
            <div class="flex flex-col md:flex-row gap-4 items-end justify-between border-t border-outline-variant/50 pt-4">
                {{-- Date Pickers --}}
                <div class="flex flex-wrap gap-4 items-center w-full md:w-auto">
                    <div>
                        <label for="tgl_mulai" class="form-label font-semibold">Tanggal Awal Pinjam</label>
                        <input type="date" 
                               name="tgl_mulai" 
                               id="tgl_mulai" 
                               value="{{ $tglMulai }}"
                               class="px-3 py-2 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                    <span class="text-slate-400 font-bold self-end mb-2.5 hidden sm:inline">s/d</span>
                    <div>
                        <label for="tgl_akhir" class="form-label font-semibold">Tanggal Akhir Pinjam</label>
                        <input type="date" 
                               name="tgl_akhir" 
                               id="tgl_akhir" 
                               value="{{ $tglAkhir }}"
                               class="px-3 py-2 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full md:w-auto justify-end">
                    <button type="submit" class="btn-secondary py-2 px-4 flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">filter_list</span>
                        <span>Filter</span>
                    </button>
                    @if($search || $status || $tglMulai || $tglAkhir)
                    <a href="{{ route('riwayat.index') }}" class="btn-ghost border border-outline-variant">
                        <span>Reset</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-low">
                        <th class="table-header">Kode</th>
                        <th class="table-header">Anggota</th>
                        <th class="table-header">Buku</th>
                        <th class="table-header">Tgl Pinjam</th>
                        <th class="table-header">Jatuh Tempo</th>
                        <th class="table-header">Tgl Kembali</th>
                        <th class="table-header text-center">Status</th>
                        <th class="table-header text-right">Denda</th>
                        <th class="table-header text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($riwayat as $trx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        {{-- Kode --}}
                        <td class="table-cell font-bold text-primary">
                            <a href="{{ route('riwayat.show', $trx->id) }}" class="hover:underline">
                                {{ $trx->kode_transaksi }}
                            </a>
                        </td>
                        
                        {{-- Anggota --}}
                        <td class="table-cell">
                            <div class="font-bold text-on-surface">{{ $trx->anggota->nama }}</div>
                            <div class="text-xs text-on-surface-variant">NIK: {{ $trx->anggota->nik }}</div>
                        </td>

                        {{-- Buku --}}
                        <td class="table-cell">
                            <div class="font-medium truncate max-w-[200px]" title="{{ $trx->buku->judul }}">{{ $trx->buku->judul }}</div>
                            <div class="text-xs text-on-surface-variant">ISBN: {{ $trx->buku->isbn }}</div>
                        </td>

                        {{-- Tgl Pinjam --}}
                        <td class="table-cell text-sm">
                            {{ $trx->tanggal_pinjam->isoFormat('D MMM Y') }}
                        </td>

                        {{-- Jatuh Tempo --}}
                        <td class="table-cell text-sm">
                            {{ $trx->tanggal_jatuh_tempo->isoFormat('D MMM Y') }}
                        </td>

                        {{-- Tgl Kembali --}}
                        <td class="table-cell text-sm">
                            {{ $trx->tanggal_kembali ? $trx->tanggal_kembali->isoFormat('D MMM Y') : '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="table-cell text-center">
                            @if($trx->status === 'dikembalikan')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @elseif($trx->status === 'dipinjam')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                Dipinjam
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                                Terlambat
                            </span>
                            @endif
                        </td>

                        {{-- Denda --}}
                        <td class="table-cell text-right font-semibold">
                            @if($trx->denda > 0)
                                <div class="text-sm font-bold text-red-700">Rp {{ number_format($trx->denda, 0, ',', '.') }}</div>
                                @if($trx->denda_dibayar)
                                    <span class="text-[10px] font-bold text-green-700 bg-green-50 px-1.5 py-0.5 rounded">Lunas</span>
                                @else
                                    <span class="text-[10px] font-bold text-red-700 bg-red-50 px-1.5 py-0.5 rounded animate-pulse">Belum Lunas</span>
                                @endif
                            @else
                                <span class="text-slate-400 font-normal">-</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('riwayat.show', $trx->id) }}" 
                                   title="Lihat Detail Transaksi" 
                                   class="p-2 text-primary hover:bg-surface-container rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                @if($trx->denda > 0 && !$trx->denda_dibayar)
                                <form method="POST" action="{{ route('transaksi.bayarDenda', $trx->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membayar denda sebesar Rp {{ number_format($trx->denda, 0, ',', '.') }}?')">
                                    @csrf
                                    <button type="submit" 
                                            title="Bayar Denda" 
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">payments</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="table-cell text-center py-12 text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300">receipt_long</span>
                                <p class="text-body-md font-semibold">Data riwayat transaksi tidak ditemukan</p>
                                <p class="text-caption">Coba ubah filter pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($riwayat->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</div>
</div>
@endsection
