@extends('layouts.app')

@section('title', 'Detail Transaksi - ' . $transaksi->kode_transaksi)

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden">
        <div class="flex items-center gap-4">
            <a href="{{ route('riwayat.index') }}" class="p-2 hover:bg-surface-container rounded-lg transition-all" title="Kembali ke Riwayat">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-md font-bold text-on-surface">Detail Transaksi</h2>
                <p class="text-body-md text-on-surface-variant mt-0.5">Informasi rincian transaksi peminjaman.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            {{-- Print Button --}}
            <button onclick="window.print()" class="btn-secondary">
                <span class="material-symbols-outlined text-lg">print</span>
                <span>Cetak Transaksi</span>
            </button>
        </div>
    </div>

    {{-- Detail Transaction Content --}}
    <div class="max-w-3xl mx-auto bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl overflow-hidden p-8 space-y-8 print:border-none print:shadow-none print:p-0">
        {{-- Header Bukti Transaksi --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-6 border-b border-outline-variant gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center flex-shrink-0 text-white">
                    <span class="material-symbols-outlined text-2xl font-variation-settings: 'FILL' 1">local_library</span>
                </div>
                <div>
                    <h1 class="text-title-lg font-bold text-primary leading-tight">LMS Perpustakaan</h1>
                    <p class="text-caption text-on-surface-variant uppercase tracking-wider">Provinsi Sumatera Selatan</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Kode Transaksi</span>
                <h2 class="text-headline-md font-extrabold text-primary tracking-tight mt-0.5">{{ $transaksi->kode_transaksi }}</h2>
            </div>
        </div>

        {{-- Status Badge Section --}}
        <div class="p-4 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4
            @if($transaksi->status === 'dikembalikan') bg-green-50 text-green-900 border border-green-200
            @elseif($transaksi->status === 'dipinjam') bg-blue-50 text-blue-900 border border-blue-200
            @else bg-red-50 text-red-900 border border-red-200 animate-pulse @endif">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-3xl icon-filled
                    @if($transaksi->status === 'dikembalikan') text-green-700
                    @elseif($transaksi->status === 'dipinjam') text-blue-700
                    @else text-red-700 @endif">
                    @if($transaksi->status === 'dikembalikan') task_alt
                    @elseif($transaksi->status === 'dipinjam') schedule
                    @else warning @endif
                </span>
                <div>
                    <h3 class="font-bold text-label-md capitalize">Status: {{ $transaksi->status }}</h3>
                    <p class="text-xs mt-0.5">
                        @if($transaksi->status === 'dikembalikan')
                            Buku telah dikembalikan dan transaksi telah selesai.
                        @elseif($transaksi->status === 'dipinjam')
                            Buku sedang dalam masa peminjaman aktif.
                        @else
                            Buku belum dikembalikan dan telah melewati masa jatuh tempo.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Info Grid (Dates & Officer) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 py-6 border-b border-outline-variant">
            <div>
                <span class="text-xs text-on-surface-variant font-medium block">Tanggal Pinjam</span>
                <span class="text-body-md font-bold text-on-surface">{{ $transaksi->tanggal_pinjam->isoFormat('D MMMM Y') }}</span>
            </div>
            <div>
                <span class="text-xs text-on-surface-variant font-medium block">Batas Jatuh Tempo</span>
                <span class="text-body-md font-bold text-on-surface text-amber-700">{{ $transaksi->tanggal_jatuh_tempo->isoFormat('D MMMM Y') }}</span>
            </div>
            <div>
                <span class="text-xs text-on-surface-variant font-medium block">Tanggal Pengembalian</span>
                <span class="text-body-md font-bold text-on-surface">
                    {{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->isoFormat('D MMMM Y') : '-' }}
                </span>
            </div>
        </div>

        {{-- Member & Book Details Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Member Info --}}
            <div class="space-y-4">
                <h4 class="text-label-md text-outline uppercase tracking-wider font-bold">Informasi Anggota</h4>
                <div class="p-4 bg-surface rounded-xl border border-outline-variant/60 space-y-3">
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Nama Lengkap</span>
                        <a href="{{ route('anggota.show', $transaksi->anggota->id) }}" class="text-body-md font-bold text-primary hover:underline">
                            {{ $transaksi->anggota->nama }}
                        </a>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">NIK Anggota</span>
                        <span class="text-body-md font-medium text-on-surface">{{ $transaksi->anggota->nik }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Nomor Telepon</span>
                        <span class="text-body-md font-medium text-on-surface">{{ $transaksi->anggota->telepon ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Book Info --}}
            <div class="space-y-4">
                <h4 class="text-label-md text-outline uppercase tracking-wider font-bold">Informasi Buku</h4>
                <div class="p-4 bg-surface rounded-xl border border-outline-variant/60 space-y-3">
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Judul Buku</span>
                        <a href="{{ route('buku.show', $transaksi->buku->id) }}" class="text-body-md font-bold text-primary hover:underline">
                            {{ $transaksi->buku->judul }}
                        </a>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Nomor ISBN</span>
                        <span class="text-body-md font-medium text-on-surface">{{ $transaksi->buku->isbn }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Lokasi Penyimpanan Rak</span>
                        <span class="text-body-md font-bold text-secondary">{{ $transaksi->buku->lokasi_rak ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fine (Denda) & Catatan Section --}}
        <div class="pt-6 border-t border-outline-variant flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            {{-- Catatan --}}
            <div class="flex-1 w-full">
                <span class="text-xs text-on-surface-variant font-medium block">Catatan Transaksi</span>
                <p class="text-sm text-on-surface-variant mt-1 italic leading-relaxed">
                    {{ $transaksi->catatan ?? 'Tidak ada catatan khusus.' }}
                </p>
            </div>

            {{-- Fine Details --}}
            @if($transaksi->denda > 0 || ($transaksi->status === 'terlambat' && $transaksi->denda_perkiraan > 0))
            <div class="w-full md:w-auto p-4 bg-red-50 border border-red-200 rounded-xl flex flex-col md:items-end text-left md:text-right gap-3 flex-shrink-0">
                <div>
                    <span class="text-[10px] text-red-600 font-semibold uppercase">Total Denda</span>
                    @php
                        $nominalDenda = $transaksi->denda > 0 ? $transaksi->denda : $transaksi->denda_perkiraan;
                    @endphp
                    <h3 class="text-headline-md font-black text-red-700">Rp {{ number_format($nominalDenda, 0, ',', '.') }}</h3>
                    <div class="mt-1">
                        @if($transaksi->denda_dibayar)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800">Lunas / Dibayar</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 animate-pulse">Belum Dilunasi</span>
                        @endif
                    </div>
                </div>

                {{-- Action: Pay fine --}}
                @if($transaksi->denda > 0 && !$transaksi->denda_dibayar && !request()->routeIs('transaksi.index'))
                <form method="POST" action="{{ route('transaksi.bayarDenda', $transaksi->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan denda sebesar Rp {{ number_format($transaksi->denda, 0, ',', '.') }}?')">
                    @csrf
                    <button type="submit" class="btn-primary w-full md:w-auto py-2 px-4 justify-center bg-green-700 hover:bg-green-800">
                        <span class="material-symbols-outlined text-sm">payments</span>
                        <span>Bayar Denda Sekarang</span>
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>

        {{-- Footer Receipt --}}
        <div class="pt-6 border-t border-outline-variant flex flex-col sm:flex-row justify-between text-xs text-on-surface-variant font-medium gap-2">
            <div>
                <span>Petugas: </span>
                <span class="font-bold text-slate-800">{{ $transaksi->petugas->name }} (NIP: {{ $transaksi->petugas->nip ?? '-' }})</span>
            </div>
            <div class="text-left sm:text-right">
                <span>Dicetak pada: </span>
                <span class="font-bold">{{ now()->setTimezone('Asia/Jakarta')->isoFormat('D MMM Y, HH:mm') }} WIB</span>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
