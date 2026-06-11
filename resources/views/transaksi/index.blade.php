@extends('layouts.app')

@section('title', 'Terminal Transaksi')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <h2 class="text-headline-md font-bold text-on-surface">Terminal Transaksi</h2>
        <p class="text-body-md text-on-surface-variant mt-0.5">Layanan peminjaman, pengembalian buku, dan pembayaran denda secara langsung.</p>
    </div>

    {{-- Main Panels Grid: Borrow & Return --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ═══ Panel 1: Peminjaman Buku ═══════════════════════════════════════ --}}
        <div class="card space-y-5">
            <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary icon-filled" style="font-size:22px;display:block;">book_5</span>
                <span>Peminjaman Baru</span>
            </h3>

            <form id="borrowForm" method="POST" action="{{ route('transaksi.peminjaman') }}" class="space-y-5">
                @csrf

                {{-- ── Field NIK Anggota ─────────────────────────────────────── --}}
                <div class="space-y-1.5">
                    <label for="nikInput" class="form-label font-semibold">NIK Anggota <span style="color:#ba1a1a;">*</span></label>

                    {{-- Input + Tombol Verifikasi sejajar --}}
                    <div class="flex gap-2 items-stretch">
                        {{-- Wrapper input dengan ikon di dalam --}}
                        <div class="relative flex-1">
                            {{--
                                FIX SIMETRI: Ikon menggunakan position absolute dengan
                                top:0 bottom:0 dan flex untuk centering vertikal,
                                bukan translate-y yang tidak presisi saat tinggi berubah.
                            --}}
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px; display:block;">badge</span>
                            </span>
                            <input type="text"
                                   name="nik"
                                   id="nikInput"
                                   value="{{ old('nik') }}"
                                   required
                                   maxlength="16"
                                   placeholder="Masukkan NIK Anggota (16 digit)..."
                                   class="w-full h-full pl-10 pr-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/40">
                        </div>
                        <button type="button" id="btnCariNik" class="btn-secondary flex-shrink-0" style="padding: 0 16px;">
                            <span class="material-symbols-outlined" style="font-size:17px; display:block;">search</span>
                            <span>Verifikasi</span>
                        </button>
                    </div>

                    {{-- Footnote / Helper text --}}
                    <p class="text-[12px] text-on-surface-variant leading-relaxed" style="margin-top: 5px;">
                        Masukkan 16 digit NIK KTP anggota perpustakaan yang terdaftar, lalu klik
                        <span class="font-semibold text-on-surface">Verifikasi</span> untuk memastikan identitas anggota.
                    </p>

                    {{-- AJAX Member Preview --}}
                    <div id="memberPreview" class="hidden mt-1 p-3 bg-surface-container-low rounded-lg border border-outline-variant text-[12px] space-y-1">
                        {{-- Filled by JS --}}
                    </div>
                </div>

                {{-- ── Field ISBN Buku ───────────────────────────────────────── --}}
                <div class="space-y-1.5">
                    <label for="isbnInput" class="form-label font-semibold">ISBN Buku <span style="color:#ba1a1a;">*</span></label>

                    <div class="flex gap-2 items-stretch">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px; display:block;">barcode</span>
                            </span>
                            <input type="text"
                                   name="isbn"
                                   id="isbnInput"
                                   value="{{ old('isbn') }}"
                                   required
                                   placeholder="Masukkan ISBN Buku..."
                                   class="w-full h-full pl-10 pr-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/40">
                        </div>
                        <button type="button" id="btnCariIsbn" class="btn-secondary flex-shrink-0" style="padding: 0 16px;">
                            <span class="material-symbols-outlined" style="font-size:17px; display:block;">search</span>
                            <span>Verifikasi</span>
                        </button>
                    </div>

                    {{-- Footnote / Helper text --}}
                    <p class="text-[12px] text-on-surface-variant leading-relaxed" style="margin-top: 5px;">
                        Masukkan nomor ISBN yang tertera di sampul atau barcode buku
                        (cth: <code class="text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-mono">978-979-756-001-1</code>),
                        lalu klik <span class="font-semibold text-on-surface">Verifikasi</span> untuk cek ketersediaan.
                    </p>

                    {{-- AJAX Book Preview --}}
                    <div id="bookPreview" class="hidden mt-1 p-3 bg-surface-container-low rounded-lg border border-outline-variant text-[12px] space-y-1">
                        {{-- Filled by JS --}}
                    </div>
                </div>

                {{-- Submit Peminjaman --}}
                <div class="pt-3 border-t border-outline-variant">
                    <button type="submit" class="btn-primary w-full justify-center" style="padding: 11px 18px;">
                        <span class="material-symbols-outlined" style="font-size:18px; display:block;">library_add_check</span>
                        <span>Proses Peminjaman</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ═══ Panel 2: Pengembalian Buku ═════════════════════════════════════ --}}
        <div class="card space-y-5">
            <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined icon-filled" style="font-size:22px; display:block; color:#15803d;">keyboard_return</span>
                <span>Pengembalian Buku</span>
            </h3>

            <form method="POST" action="{{ route('transaksi.pengembalian') }}" class="space-y-5">
                @csrf

                {{-- ── Field Kode Transaksi / ISBN ──────────────────────────── --}}
                <div class="space-y-1.5">
                    <label for="kodeTransaksiInput" class="form-label font-semibold">Kode Transaksi atau ISBN Buku <span style="color:#ba1a1a;">*</span></label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px; display:block;">qr_code_scanner</span>
                        </span>
                        <input type="text"
                               name="kode_transaksi"
                               id="kodeTransaksiInput"
                               required
                               placeholder="Masukkan Kode Transaksi atau ISBN Buku..."
                               class="w-full pl-10 pr-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline/40">
                    </div>

                    {{-- Footnote --}}
                    <p class="text-[12px] text-on-surface-variant leading-relaxed" style="margin-top: 5px;">
                        Ketik Kode Transaksi aktif (cth: <code class="text-[11px] bg-surface-container px-1.5 py-0.5 rounded font-mono">TRX20240601001</code>)
                        atau nomor ISBN Buku yang ingin dikembalikan.
                    </p>
                </div>

                {{-- Submit Pengembalian --}}
                <div class="pt-3 border-t border-outline-variant">
                    <button type="submit" class="btn-success w-full justify-center" style="padding: 11px 18px;">
                        <span class="material-symbols-outlined" style="font-size:18px; display:block;">check_circle</span>
                        <span>Proses Pengembalian</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Active Loans Table Section --}}
    <div class="card space-y-4">
        <h3 class="text-title-lg font-bold text-on-surface">Daftar Peminjaman Aktif (Sedang Berjalan)</h3>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-low">
                        <th class="table-header">Kode</th>
                        <th class="table-header">Anggota</th>
                        <th class="table-header">Buku</th>
                        <th class="table-header">Tanggal Pinjam</th>
                        <th class="table-header">Jatuh Tempo</th>
                        <th class="table-header text-center">Status</th>
                        <th class="table-header text-right">Denda Terkumpul</th>
                        <th class="table-header text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($transaksiAktif as $trx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="table-cell font-bold text-primary">
                            <a href="{{ route('riwayat.show', $trx->id) }}" class="hover:underline">
                                {{ $trx->kode_transaksi }}
                            </a>
                        </td>
                        <td class="table-cell">
                            <div class="font-semibold text-[14px] text-on-surface">{{ $trx->anggota->nama }}</div>
                            <div class="text-[11px] text-on-surface-variant">NIK: {{ $trx->anggota->nik }}</div>
                        </td>
                        <td class="table-cell">
                            <div class="font-medium text-[14px] truncate max-w-[200px]" title="{{ $trx->buku->judul }}">{{ $trx->buku->judul }}</div>
                            <div class="text-[11px] text-on-surface-variant">ISBN: {{ $trx->buku->isbn }}</div>
                        </td>
                        <td class="table-cell text-[13px]">{{ $trx->tanggal_pinjam->isoFormat('D MMM Y') }}</td>
                        <td class="table-cell text-[13px]">{{ $trx->tanggal_jatuh_tempo->isoFormat('D MMM Y') }}</td>
                        <td class="table-cell text-center">
                            @if($trx->status === 'dipinjam')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">Dipinjam</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800 animate-pulse">Terlambat</span>
                            @endif
                        </td>
                        <td class="table-cell text-right">
                            @if($trx->denda_perkiraan > 0)
                            <div class="text-[13px] font-bold text-red-700">Rp {{ number_format($trx->denda_perkiraan, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-slate-500">Terlambat {{ $trx->hari_terlambat }} hari</div>
                            @else
                            <span class="text-slate-400 text-[13px]">-</span>
                            @endif
                        </td>
                        <td class="table-cell text-right">
                            <form method="POST" action="{{ route('transaksi.pengembalian') }}" class="inline">
                                @csrf
                                <input type="hidden" name="kode_transaksi" value="{{ $trx->kode_transaksi }}">
                                <button type="submit"
                                        title="Kembalikan Buku"
                                        class="btn-success"
                                        style="padding: 6px 12px; font-size: 12px;">
                                    <span class="material-symbols-outlined" style="font-size:15px; display:block;">assignment_return</span>
                                    <span>Kembalikan</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="table-cell text-center py-12 text-on-surface-variant">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-slate-300" style="font-size:40px; display:block;">inventory_2</span>
                                <p class="text-[14px] font-semibold">Belum ada peminjaman aktif saat ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCariNik   = document.getElementById('btnCariNik');
    const nikInput     = document.getElementById('nikInput');
    const memberPreview = document.getElementById('memberPreview');

    const btnCariIsbn  = document.getElementById('btnCariIsbn');
    const isbnInput    = document.getElementById('isbnInput');
    const bookPreview  = document.getElementById('bookPreview');

    // AJAX Cari NIK Anggota
    btnCariNik.addEventListener('click', function() {
        const nik = nikInput.value.trim();
        if (!nik) { alert('Silakan masukkan NIK terlebih dahulu.'); return; }

        memberPreview.classList.remove('hidden');
        memberPreview.innerHTML = '<span style="color:#757682;">Mencari data anggota...</span>';

        fetch(`{{ route('transaksi.cariNik') }}?nik=${encodeURIComponent(nik)}`)
            .then(res => res.json())
            .then(data => {
                if (data.found) {
                    const isAktif    = data.anggota.status === 'aktif';
                    const statusBg   = isAktif ? '#dcfce7' : '#fee2e2';
                    const statusColor= isAktif ? '#15803d'  : '#b91c1c';
                    memberPreview.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:700; font-size:14px; color:#171c1f;">${data.anggota.nama}</span>
                            <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:999px; background:${statusBg}; color:${statusColor}; text-transform:uppercase;">${data.anggota.status}</span>
                        </div>
                        <div style="color:#757682; margin-top:3px; font-size:12px;">NIK: ${data.anggota.nik}</div>
                        <div style="color:#444651; margin-top:3px; font-size:12px;">Peminjaman aktif: <strong style="color:#00236f;">${data.anggota.pinjaman_aktif} / 3 buku</strong></div>
                    `;
                } else {
                    memberPreview.innerHTML = `<span style="color:#ba1a1a; font-weight:600;">${data.message}</span>`;
                }
            })
            .catch(() => {
                memberPreview.innerHTML = '<span style="color:#ba1a1a; font-weight:600;">Terjadi kesalahan sistem.</span>';
            });
    });

    // AJAX Cari ISBN Buku
    btnCariIsbn.addEventListener('click', function() {
        const isbn = isbnInput.value.trim();
        if (!isbn) { alert('Silakan masukkan ISBN terlebih dahulu.'); return; }

        bookPreview.classList.remove('hidden');
        bookPreview.innerHTML = '<span style="color:#757682;">Mencari data buku...</span>';

        fetch(`{{ route('buku.cariIsbn') }}?isbn=${encodeURIComponent(isbn)}`)
            .then(res => res.json())
            .then(data => {
                if (data.found) {
                    const isTersedia  = data.buku.status === 'tersedia';
                    const statusBg    = isTersedia ? '#dcfce7' : '#fee2e2';
                    const statusColor = isTersedia ? '#15803d'  : '#b91c1c';
                    bookPreview.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:700; font-size:14px; color:#171c1f;">${data.buku.judul}</span>
                            <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:999px; background:${statusBg}; color:${statusColor}; text-transform:uppercase;">${data.buku.status}</span>
                        </div>
                        <div style="color:#757682; margin-top:3px; font-size:12px;">Pengarang: ${data.buku.pengarang} &nbsp;|&nbsp; Rak: ${data.buku.lokasi_rak || '-'}</div>
                        <div style="color:#444651; margin-top:3px; font-size:12px;">Stok tersedia: <strong style="color:#00236f;">${data.buku.stok_tersedia} eksemplar</strong></div>
                    `;
                } else {
                    bookPreview.innerHTML = `<span style="color:#ba1a1a; font-weight:600;">${data.message}</span>`;
                }
            })
            .catch(() => {
                bookPreview.innerHTML = '<span style="color:#ba1a1a; font-weight:600;">Terjadi kesalahan sistem.</span>';
            });
    });

    // Enter key di NIK field → trigger Verifikasi
    nikInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); btnCariNik.click(); } });
    // Enter key di ISBN field → trigger Verifikasi
    isbnInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); btnCariIsbn.click(); } });
});
</script>
@endpush
