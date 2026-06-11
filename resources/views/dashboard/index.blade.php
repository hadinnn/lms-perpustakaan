@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
{{-- ═══════════════════════════════════════════════════════════════════════════
     HERO BANNER — full-bleed, tidak ada card wrapper, flush ke semua sisi
     Seperti pola yang digunakan GitHub, Linear, Vercel, dan Notion:
     banner header melebar penuh tanpa rounded corner di tepi halaman.
════════════════════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #00236f 0%, #0c2d7a 50%, #1a1f5e 100%); padding: 36px 32px 32px;">
    {{-- Dekorasi pattern latar --}}
    <div class="absolute inset-0 songket-pattern pointer-events-none"></div>
    {{-- Glow effect di sudut kanan atas --}}
    <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(182,196,255,0.15) 0%, transparent 70%);"></div>

    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <p class="text-[12px] font-semibold uppercase tracking-widest mb-2" style="color: rgba(182,196,255,0.7);">
                Selamat datang kembali
            </p>
            <h1 class="font-bold text-white" style="font-size: 28px; line-height: 1.25; letter-spacing: -0.01em;">
                {{ $salam }}, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-2" style="font-size: 14px; line-height: 1.6; color: rgba(255,255,255,0.65);">
                Sistem Manajemen Perpustakaan Daerah siap digunakan.
                Anda masuk sebagai
                <span class="font-semibold" style="color: rgba(255,255,255,0.9); text-transform: capitalize;">{{ auth()->user()->role }}</span>.
            </p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('transaksi.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-[13px] transition-all"
               style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(4px);"
               onmouseover="this.style.background='rgba(255,255,255,0.25)'"
               onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <span class="material-symbols-outlined" style="font-size:18px; display:block;">swap_horiz</span>
                <span>Terminal Transaksi</span>
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════════
     KONTEN UTAMA — padding standar dimulai di sini
════════════════════════════════════════════════════════════════════════════ --}}
<div class="p-6 max-w-[1280px] mx-auto space-y-6 fade-in">

    {{-- Overdue Warning Alert --}}
    @if($terlambat > 0)
    <div class="flex items-start gap-4 p-4 bg-error-container border border-error/20 rounded-xl text-on-error-container">
        <span class="material-symbols-outlined text-error icon-filled animate-bounce flex-shrink-0" style="font-size:22px; display:block;">warning</span>
        <div class="flex-1 min-w-0">
            <h4 class="font-bold text-[14px]">Peringatan: Transaksi Terlambat Terdeteksi!</h4>
            <p class="text-[13px] mt-0.5 leading-relaxed">Terdapat <span class="font-bold">{{ $terlambat }} peminjaman</span> yang telah melewati batas jatuh tempo dan belum dikembalikan.</p>
        </div>
        <a href="{{ route('riwayat.index', ['status' => 'terlambat']) }}" class="btn-danger flex-shrink-0" style="font-size:12px; padding: 6px 12px;">
            Lihat Semua
        </a>
    </div>
    @endif

    {{-- Statistics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Total Buku --}}
        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-fixed text-primary rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined icon-filled" style="font-size:24px; display:block;">menu_book</span>
            </div>
            <div>
                <p class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider">Total Eksemplar Buku</p>
                <h3 class="text-[28px] font-bold mt-0.5 leading-none">{{ number_format($totalBuku) }}</h3>
            </div>
        </div>

        {{-- Card 2: Anggota Aktif --}}
        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined icon-filled" style="font-size:24px; display:block;">group</span>
            </div>
            <div>
                <p class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider">Anggota Aktif</p>
                <h3 class="text-[28px] font-bold mt-0.5 leading-none">{{ number_format($totalAnggota) }}</h3>
            </div>
        </div>

        {{-- Card 3: Transaksi Hari Ini --}}
        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined icon-filled" style="font-size:24px; display:block;">swap_horiz</span>
            </div>
            <div>
                <p class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider">Transaksi Hari Ini</p>
                <h3 class="text-[28px] font-bold mt-0.5 leading-none">{{ number_format($transaksiHariIni) }}</h3>
            </div>
        </div>

        {{-- Card 4: Total Denda Unpaid --}}
        <div class="card flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined icon-filled" style="font-size:24px; display:block;">payments</span>
            </div>
            <div>
                <p class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider">Tunggakan Denda</p>
                <h3 class="text-[22px] font-bold mt-0.5 leading-none">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Main Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Activity Table (Left - 2/3 width) --}}
        <div class="card lg:col-span-2 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-title-lg font-bold text-on-surface">Aktivitas Transaksi Terbaru</h3>
                <a href="{{ route('riwayat.index') }}" class="btn-ghost" style="font-size:12px; padding: 6px 12px;">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined" style="font-size:16px; display:block;">arrow_forward</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-low">
                            <th class="table-header">Kode</th>
                            <th class="table-header">Anggota</th>
                            <th class="table-header">Buku</th>
                            <th class="table-header">Tanggal Pinjam</th>
                            <th class="table-header">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($aktivitasTerbaru as $trx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="table-cell font-bold text-primary">
                                <a href="{{ route('riwayat.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->kode_transaksi }}
                                </a>
                            </td>
                            <td class="table-cell">
                                <div class="font-semibold text-[14px]">{{ $trx->anggota->nama }}</div>
                                <div class="text-[11px] text-on-surface-variant">NIK: {{ $trx->anggota->nik }}</div>
                            </td>
                            <td class="table-cell">
                                <div class="truncate max-w-[180px] font-medium text-[14px]" title="{{ $trx->buku->judul }}">{{ $trx->buku->judul }}</div>
                                <div class="text-[11px] text-on-surface-variant">ISBN: {{ $trx->buku->isbn }}</div>
                            </td>
                            <td class="table-cell text-[13px]">
                                {{ $trx->tanggal_pinjam->isoFormat('D MMM Y') }}
                            </td>
                            <td class="table-cell">
                                @if($trx->status === 'dikembalikan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-green-100 text-green-800">Dikembalikan</span>
                                @elseif($trx->status === 'dipinjam')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">Dipinjam</span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800 animate-pulse">Terlambat</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="table-cell text-center py-10 text-on-surface-variant">
                                Belum ada riwayat aktivitas peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sidebar Panel (Right - 1/3 width) --}}
        <div class="space-y-5">
            {{-- Near Due (Mendekati Jatuh Tempo) --}}
            <div class="card space-y-4">
                <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 icon-filled" style="font-size:20px; display:block;">hourglass_empty</span>
                    <span>Jatuh Tempo Terdekat</span>
                </h3>
                <div class="space-y-3 max-h-[280px] overflow-y-auto custom-scrollbar pr-1">
                    @forelse($mendekatiJatuhTempo as $trx)
                    <div class="p-3 bg-surface-container-low rounded-xl border border-outline-variant hover:border-amber-400 transition-all flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs uppercase">
                            {{ $trx->anggota->initials }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-semibold text-on-surface truncate">{{ $trx->anggota->nama }}</p>
                            <p class="text-[11px] text-on-surface-variant truncate mt-0.5">{{ $trx->buku->judul }}</p>
                            <div class="flex justify-between items-center mt-1.5 text-[11px]">
                                <span class="text-on-surface-variant">Jatuh Tempo:</span>
                                <span class="font-bold text-amber-700">{{ $trx->tanggal_jatuh_tempo->isoFormat('D MMM Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-on-surface-variant text-[13px] bg-surface-container-low rounded-xl border border-dashed border-outline-variant">
                        Tidak ada peminjaman mendekati jatuh tempo.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card space-y-3">
                <h3 class="text-title-lg font-bold text-on-surface">Aksi Cepat</h3>
                <div class="grid grid-cols-1 gap-2">
                    <a href="{{ route('anggota.create') }}" class="flex items-center gap-3 p-3 hover:bg-surface-container-high rounded-xl transition-all border border-outline-variant text-on-surface text-[13px] font-semibold">
                        <span class="material-symbols-outlined text-primary" style="font-size:20px; display:block;">person_add</span>
                        <span>Tambah Anggota Baru</span>
                    </a>
                    <a href="{{ route('buku.create') }}" class="flex items-center gap-3 p-3 hover:bg-surface-container-high rounded-xl transition-all border border-outline-variant text-on-surface text-[13px] font-semibold">
                        <span class="material-symbols-outlined text-primary" style="font-size:20px; display:block;">library_add</span>
                        <span>Tambah Buku Baru</span>
                    </a>
                    <a href="{{ route('transaksi.index') }}" class="flex items-center gap-3 p-3 hover:bg-surface-container-high rounded-xl transition-all border border-outline-variant text-on-surface text-[13px] font-semibold">
                        <span class="material-symbols-outlined text-primary" style="font-size:20px; display:block;">book_5</span>
                        <span>Pencatatan Peminjaman</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
