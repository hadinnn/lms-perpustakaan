@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('buku.index') }}"
           class="btn-ghost"
           style="padding: 8px 10px; border-color: transparent;"
           title="Kembali ke Katalog Buku">
            <span class="material-symbols-outlined" style="font-size:20px; display:block;">arrow_back</span>
        </a>
        <div>
            <h2 class="font-bold text-on-surface" style="font-size:22px; line-height:1.3;">Tambah Buku Baru</h2>
            <p class="text-on-surface-variant" style="font-size:13px; margin-top:2px;">Masukkan buku baru ke dalam katalog perpustakaan.</p>
        </div>
    </div>

    {{-- ── Form Card ────────────────────────────────────────────────────── --}}
    <div class="card" style="max-width: 860px; margin: 0 auto; padding: 32px;">
        <form method="POST" action="{{ route('buku.store') }}">
            @csrf

            {{-- ═══ SEKSI 1: Identitas Buku ══════════════════════════════════ --}}
            <div style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #eaeef2;">
                    <span class="material-symbols-outlined text-primary icon-filled" style="font-size:18px; display:block;">menu_book</span>
                    <h3 style="font-size:13px; font-weight:700; color:#444651; text-transform:uppercase; letter-spacing:0.05em;">Identitas Buku</h3>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    {{-- ISBN --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="isbn" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Nomor ISBN
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="text"
                               name="isbn"
                               id="isbn"
                               value="{{ old('isbn') }}"
                               required
                               placeholder="Contoh: 978-602-8512-00-0"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('isbn') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('isbn') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        <p style="font-size:11px; color:#757682;">Format: 978-xxx-xxx-xxx-x (13 digit).</p>
                        @error('isbn')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Judul Buku --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="judul" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Judul Buku
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="text"
                               name="judul"
                               id="judul"
                               value="{{ old('judul') }}"
                               required
                               placeholder="Masukkan judul buku lengkap..."
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('judul') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('judul') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('judul')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Pengarang --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="pengarang" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Pengarang / Penulis
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="text"
                               name="pengarang"
                               id="pengarang"
                               value="{{ old('pengarang') }}"
                               required
                               placeholder="Nama pengarang buku..."
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('pengarang') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('pengarang') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('pengarang')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Penerbit --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="penerbit" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Penerbit
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="text"
                               name="penerbit"
                               id="penerbit"
                               value="{{ old('penerbit') }}"
                               placeholder="Nama penerbit buku..."
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('penerbit') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('penerbit') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('penerbit')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Terbit --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="tahun_terbit" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Tahun Terbit
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="number"
                               name="tahun_terbit"
                               id="tahun_terbit"
                               min="1900"
                               max="{{ date('Y') }}"
                               value="{{ old('tahun_terbit', date('Y')) }}"
                               placeholder="Contoh: {{ date('Y') }}"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('tahun_terbit') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('tahun_terbit') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('tahun_terbit')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="kategori_id" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Kategori Buku
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <select name="kategori_id"
                                id="kategori_id"
                                required
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('kategori_id') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; appearance:auto; transition:border-color 150ms;">
                            <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>-- Pilih kategori --</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deskripsi — full width --}}
                    <div style="grid-column: 1 / -1; display:flex; flex-direction:column; gap:5px;">
                        <label for="deskripsi" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Deskripsi / Sinopsis
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="4"
                                  placeholder="Tulis ringkasan isi buku atau sinopsis singkat..."
                                  style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('deskripsi') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; resize:vertical; line-height:1.5; transition:border-color 150ms, box-shadow 150ms; font-family:inherit;"
                                  onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                                  onblur="this.style.borderColor='{{ $errors->has('deskripsi') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ═══ SEKSI 2: Inventori & Lokasi ══════════════════════════════ --}}
            <div style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #eaeef2;">
                    <span class="material-symbols-outlined text-primary icon-filled" style="font-size:18px; display:block;">inventory_2</span>
                    <h3 style="font-size:13px; font-weight:700; color:#444651; text-transform:uppercase; letter-spacing:0.05em;">Inventori & Lokasi</h3>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    {{-- Stok Total --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="stok_total" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Jumlah Stok (Eksemplar)
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="number"
                               name="stok_total"
                               id="stok_total"
                               min="1"
                               value="{{ old('stok_total', 1) }}"
                               required
                               placeholder="Jumlah total eksemplar buku..."
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('stok_total') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('stok_total') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        <p style="font-size:11px; color:#757682;">Stok awal tersedia akan sama dengan jumlah ini.</p>
                        @error('stok_total')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Lokasi Rak --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="lokasi_rak" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Lokasi Rak / Kode Penyimpanan
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="text"
                               name="lokasi_rak"
                               id="lokasi_rak"
                               value="{{ old('lokasi_rak') }}"
                               placeholder="Contoh: A-03, B-12, REF-01..."
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('lokasi_rak') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('lokasi_rak') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        <p style="font-size:11px; color:#757682;">Kode rak tempat buku ini disimpan di perpustakaan.</p>
                        @error('lokasi_rak')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Action Buttons ──────────────────────────────────────────── --}}
            <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:20px; border-top:1.5px solid #eaeef2;">
                <a href="{{ route('buku.index') }}" class="btn-ghost">
                    <span class="material-symbols-outlined" style="font-size:17px; display:block;">close</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size:17px; display:block;">library_add</span>
                    <span>Simpan Buku</span>
                </button>
            </div>

        </form>
    </div>

</div>
</div>
@endsection
