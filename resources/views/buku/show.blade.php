@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('buku.index') }}" class="p-2 hover:bg-surface-container rounded-lg transition-all" title="Kembali ke Katalog Buku">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-md font-bold text-on-surface">Detail Koleksi Buku</h2>
                <p class="text-body-md text-on-surface-variant mt-0.5">Informasi buku dan log transaksi peminjaman.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('buku.edit', $buku->id) }}" class="btn-secondary">
                <span class="material-symbols-outlined text-lg">edit</span>
                <span>Ubah Data Buku</span>
            </a>
        </div>
    </div>

    {{-- Split Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Book Info Card (Left Column) --}}
        <div class="card space-y-6 self-start">
            <div class="flex flex-col items-center pb-6 border-b border-outline-variant">
                {{-- Book Cover Large Placeholder --}}
                <div class="w-32 h-48 bg-gradient-to-br from-primary via-indigo-950 to-slate-900 text-white flex flex-col justify-between p-4 rounded-xl shadow-xl text-center font-bold mb-4 relative overflow-hidden">
                    <div class="absolute inset-0 songket-pattern opacity-10 pointer-events-none"></div>
                    <span class="text-xs text-slate-300 uppercase tracking-widest">Pusda Sumsel</span>
                    <span class="line-clamp-4 text-sm leading-snug">{{ $buku->judul }}</span>
                    <span class="text-[9px] text-slate-400 font-normal">ISBN: {{ $buku->isbn }}</span>
                </div>
                <h3 class="text-title-lg font-bold text-on-surface text-center leading-snug">{{ $buku->judul }}</h3>
                <p class="text-body-md text-on-surface-variant mt-1 text-center">Pengarang: <span class="font-semibold">{{ $buku->pengarang }}</span></p>
                <div class="mt-3">
                    @if($buku->status === 'tersedia')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                        Buku Tersedia
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                        Stok Habis
                    </span>
                    @endif
                </div>
            </div>

            {{-- Detail Fields --}}
            <div class="space-y-4">
                <h4 class="text-label-md text-outline uppercase tracking-wider font-bold">Informasi Katalog</h4>
                
                {{-- ISBN --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Nomor ISBN</span>
                    <span class="text-body-md font-semibold text-on-surface">{{ $buku->isbn }}</span>
                </div>

                {{-- Penerbit & Tahun --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Penerbit & Tahun Terbit</span>
                    <span class="text-body-md font-semibold text-on-surface">
                        {{ $buku->penerbit ?? '-' }} ({{ $buku->tahun_terbit ?? '-' }})
                    </span>
                </div>

                {{-- Kategori --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Kategori</span>
                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant">
                        {{ $buku->kategori ? $buku->kategori->nama : 'Umum' }}
                    </span>
                </div>

                {{-- Rak Lokasi --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Lokasi Rak</span>
                    <span class="text-body-md font-semibold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">grid_on</span>
                        <span>{{ $buku->lokasi_rak ?? '-' }}</span>
                    </span>
                </div>

                {{-- Stok Details --}}
                <div class="pt-4 border-t border-outline-variant space-y-2">
                    <span class="text-xs text-on-surface-variant font-medium block">Detail Ketersediaan Stok</span>
                    <div class="grid grid-cols-3 gap-2 text-center text-sm font-semibold">
                        <div class="bg-surface-container-low p-2 rounded-lg border border-outline-variant/50">
                            <span class="text-[10px] text-slate-500 block">Total</span>
                            <span class="text-base font-bold text-slate-800">{{ $buku->stok_total }}</span>
                        </div>
                        <div class="bg-green-50 p-2 rounded-lg border border-green-100">
                            <span class="text-[10px] text-green-600 block">Tersedia</span>
                            <span class="text-base font-bold text-green-700">{{ $buku->stok_tersedia }}</span>
                        </div>
                        <div class="bg-amber-50 p-2 rounded-lg border border-amber-100">
                            <span class="text-[10px] text-amber-600 block">Dipinjam</span>
                            <span class="text-base font-bold text-amber-700">{{ $buku->stok_total - $buku->stok_tersedia }}</span>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                @if($buku->deskripsi)
                <div class="pt-4 border-t border-outline-variant">
                    <span class="text-xs text-on-surface-variant font-medium block mb-1">Sinopsis / Ringkasan</span>
                    <p class="text-sm text-on-surface-variant leading-relaxed text-justify">{{ $buku->deskripsi }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Borrow Log (Right Column) --}}
        <div class="card lg:col-span-2 space-y-4">
            <h3 class="text-title-lg font-bold text-on-surface">Log Aktivitas Peminjaman Buku</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px]">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-low">
                            <th class="table-header">Kode Transaksi</th>
                            <th class="table-header">Nama Anggota</th>
                            <th class="table-header">Tgl Pinjam</th>
                            <th class="table-header">Jatuh Tempo</th>
                            <th class="table-header">Tgl Kembali</th>
                            <th class="table-header">Status</th>
                            <th class="table-header text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($buku->transaksi as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="table-cell font-bold text-primary">
                                <a href="{{ route('riwayat.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->kode_transaksi }}
                                </a>
                            </td>
                            <td class="table-cell">
                                <div class="font-bold">{{ $trx->anggota->nama }}</div>
                                <div class="text-xs text-on-surface-variant">NIK: {{ $trx->anggota->nik }}</div>
                            </td>
                            <td class="table-cell text-sm">
                                {{ $trx->tanggal_pinjam->isoFormat('D MMM Y') }}
                            </td>
                            <td class="table-cell text-sm">
                                {{ $trx->tanggal_jatuh_tempo->isoFormat('D MMM Y') }}
                            </td>
                            <td class="table-cell text-sm">
                                {{ $trx->tanggal_kembali ? $trx->tanggal_kembali->isoFormat('D MMM Y') : '-' }}
                            </td>
                            <td class="table-cell">
                                @if($trx->status === 'dikembalikan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    Selesai
                                </span>
                                @elseif($trx->status === 'dipinjam')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    Dipinjam
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    Terlambat
                                </span>
                                @endif
                            </td>
                            <td class="table-cell text-right">
                                <a href="{{ route('riwayat.show', $trx->id) }}" 
                                   title="Detail Transaksi" 
                                   class="p-2 text-primary hover:bg-surface-container rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="table-cell text-center py-12 text-on-surface-variant">
                                Buku ini belum pernah dipinjam.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
