@extends('layouts.app')

@section('title', 'Detail Anggota - ' . $anggota->nama)

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('anggota.index') }}" class="p-2 hover:bg-surface-container rounded-lg transition-all" title="Kembali ke Daftar Anggota">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-md font-bold text-on-surface">Profil Anggota</h2>
                <p class="text-body-md text-on-surface-variant mt-0.5">Detail informasi dan riwayat peminjaman buku.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn-secondary">
                <span class="material-symbols-outlined text-lg">edit</span>
                <span>Ubah Profil</span>
            </a>
        </div>
    </div>

    {{-- Split Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card (Left Column) --}}
        <div class="card space-y-6 self-start">
            <div class="flex flex-col items-center text-center pb-6 border-b border-outline-variant">
                {{-- Initials Avatar --}}
                <div class="w-24 h-24 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-headline-lg shadow-lg uppercase mb-4">
                    {{ $anggota->initials }}
                </div>
                <h3 class="text-title-lg font-bold text-on-surface">{{ $anggota->nama }}</h3>
                <p class="text-body-md text-on-surface-variant mt-0.5">NIK: {{ $anggota->nik }}</p>
                <div class="mt-3">
                    @if($anggota->status === 'aktif')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                        Anggota Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                        Nonaktif
                    </span>
                    @endif
                </div>
            </div>

            {{-- Detail Fields --}}
            <div class="space-y-4">
                <h4 class="text-label-md text-outline uppercase tracking-wider font-bold">Informasi Pribadi</h4>
                
                {{-- Gender --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Jenis Kelamin</span>
                    <span class="text-body-md font-semibold text-on-surface">
                        {{ $anggota->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                    </span>
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Tanggal Lahir</span>
                    <span class="text-body-md font-semibold text-on-surface">
                        {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->isoFormat('D MMMM Y') : '-' }}
                    </span>
                </div>

                {{-- Telepon --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Nomor Telepon</span>
                    <span class="text-body-md font-semibold text-on-surface">{{ $anggota->telepon ?? '-' }}</span>
                </div>

                {{-- Email --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">E-mail</span>
                    <span class="text-body-md font-semibold text-on-surface truncate block" title="{{ $anggota->email }}">{{ $anggota->email ?? '-' }}</span>
                </div>

                {{-- Alamat --}}
                <div>
                    <span class="text-xs text-on-surface-variant font-medium block">Alamat</span>
                    <span class="text-body-md font-semibold text-on-surface block leading-relaxed">{{ $anggota->alamat ?? '-' }}</span>
                </div>

                <div class="pt-4 border-t border-outline-variant">
                    <span class="text-xs text-on-surface-variant font-medium block">Bergabung Sejak</span>
                    <span class="text-body-md font-semibold text-on-surface">
                        {{ $anggota->tanggal_bergabung ? $anggota->tanggal_bergabung->isoFormat('D MMMM Y') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Borrowing History (Right Column) --}}
        <div class="card lg:col-span-2 space-y-4">
            <h3 class="text-title-lg font-bold text-on-surface">Riwayat Peminjaman Buku</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px]">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-low">
                            <th class="table-header">Kode Transaksi</th>
                            <th class="table-header">Buku</th>
                            <th class="table-header">Tgl Pinjam</th>
                            <th class="table-header">Jatuh Tempo</th>
                            <th class="table-header">Status</th>
                            <th class="table-header text-right">Denda</th>
                            <th class="table-header text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transaksi as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="table-cell font-bold text-primary">
                                <a href="{{ route('riwayat.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->kode_transaksi }}
                                </a>
                            </td>
                            <td class="table-cell">
                                <div class="font-bold truncate max-w-[200px]" title="{{ $trx->buku->judul }}">{{ $trx->buku->judul }}</div>
                                <div class="text-xs text-on-surface-variant">ISBN: {{ $trx->buku->isbn }}</div>
                            </td>
                            <td class="table-cell text-sm">
                                {{ $trx->tanggal_pinjam->isoFormat('D MMM Y') }}
                            </td>
                            <td class="table-cell text-sm">
                                {{ $trx->tanggal_jatuh_tempo->isoFormat('D MMM Y') }}
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
                            <td class="table-cell text-right font-medium">
                                @if($trx->denda > 0)
                                    <div class="text-sm font-bold text-red-700">Rp {{ number_format($trx->denda, 0, ',', '.') }}</div>
                                    @if($trx->denda_dibayar)
                                        <span class="text-[10px] font-bold text-green-700 bg-green-50 px-1.5 py-0.5 rounded">Lunas</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-700 bg-red-50 px-1.5 py-0.5 rounded animate-pulse">Belum Bayar</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('riwayat.show', $trx->id) }}" 
                                       title="Detail Transaksi" 
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
                            <td colspan="7" class="table-cell text-center py-12 text-on-surface-variant">
                                Belum ada riwayat peminjaman buku oleh anggota ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transaksi->hasPages())
            <div class="pt-4 border-t border-outline-variant">
                {{ $transaksi->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
