@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">

    {{-- ── Page Header ────────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('anggota.index') }}"
           class="btn-ghost"
           style="padding: 8px 10px; border-color: transparent;"
           title="Kembali">
            <span class="material-symbols-outlined" style="font-size:20px; display:block;">arrow_back</span>
        </a>
        <div>
            <h2 class="font-bold text-on-surface" style="font-size:22px; line-height:1.3;">Tambah Anggota Baru</h2>
            <p class="text-on-surface-variant" style="font-size:13px; margin-top:2px;">Daftarkan anggota baru ke dalam sistem perpustakaan.</p>
        </div>
    </div>

    {{-- ── Form Card ───────────────────────────────────────────────────────── --}}
    <div class="card" style="max-width: 860px; margin: 0 auto; padding: 32px;">
        <form method="POST" action="{{ route('anggota.store') }}">
            @csrf

            {{-- ═══ SEKSI 1: Identitas Diri ═══════════════════════════════════ --}}
            <div style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #eaeef2;">
                    <span class="material-symbols-outlined text-primary icon-filled" style="font-size:18px; display:block;">person</span>
                    <h3 style="font-size:13px; font-weight:700; color:#444651; text-transform:uppercase; letter-spacing:0.05em;">Identitas Diri</h3>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    {{-- NIK --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="nik" style="font-size:13px; font-weight:600; color:#171c1f;">
                            NIK (Nomor Induk Kependudukan)
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="text"
                               name="nik"
                               id="nik"
                               value="{{ old('nik') }}"
                               maxlength="16"
                               required
                               placeholder="16 digit NIK sesuai KTP"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('nik') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('nik') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        <p style="font-size:11px; color:#757682;">Tepat 16 digit, sesuai e-KTP anggota.</p>
                        @error('nik')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="nama" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Nama Lengkap
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="nama"
                               value="{{ old('nama') }}"
                               required
                               placeholder="Nama lengkap sesuai KTP"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('nama') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('nama') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('nama')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="jenis_kelamin" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Jenis Kelamin
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <select name="jenis_kelamin"
                                id="jenis_kelamin"
                                required
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('jenis_kelamin') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; appearance:auto; transition:border-color 150ms;">
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih jenis kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px; display:block;">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="tanggal_lahir" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Tanggal Lahir
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="date"
                               name="tanggal_lahir"
                               id="tanggal_lahir"
                               value="{{ old('tanggal_lahir') }}"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('tanggal_lahir') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('tanggal_lahir') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('tanggal_lahir')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ═══ SEKSI 2: Informasi Kontak ══════════════════════════════════ --}}
            <div style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #eaeef2;">
                    <span class="material-symbols-outlined text-primary icon-filled" style="font-size:18px; display:block;">contacts</span>
                    <h3 style="font-size:13px; font-weight:700; color:#444651; text-transform:uppercase; letter-spacing:0.05em;">Informasi Kontak</h3>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    {{-- Telepon --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="telepon" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Nomor Telepon / HP
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="text"
                               name="telepon"
                               id="telepon"
                               value="{{ old('telepon') }}"
                               placeholder="Contoh: 081234567890"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('telepon') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('telepon') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('telepon')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="email" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Alamat E-mail
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               placeholder="Contoh: nama@email.com"
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('email') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('email') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        @error('email')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat — full width --}}
                    <div style="grid-column: 1 / -1; display:flex; flex-direction:column; gap:5px;">
                        <label for="alamat" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Alamat Tempat Tinggal
                            <span style="font-size:11px; color:#757682; font-weight:400; margin-left:4px;">(opsional)</span>
                        </label>
                        <textarea name="alamat"
                                  id="alamat"
                                  rows="3"
                                  placeholder="Tulis alamat lengkap: Jl. Nama Jalan No. XX, Kelurahan, Kecamatan, Kota..."
                                  style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('alamat') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; resize:vertical; line-height:1.5; transition:border-color 150ms, box-shadow 150ms; font-family:inherit;"
                                  onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                                  onblur="this.style.borderColor='{{ $errors->has('alamat') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ═══ SEKSI 3: Data Keanggotaan ══════════════════════════════════ --}}
            <div style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #eaeef2;">
                    <span class="material-symbols-outlined text-primary icon-filled" style="font-size:18px; display:block;">card_membership</span>
                    <h3 style="font-size:13px; font-weight:700; color:#444651; text-transform:uppercase; letter-spacing:0.05em;">Data Keanggotaan</h3>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                    {{-- Tanggal Bergabung --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="tanggal_bergabung" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Tanggal Bergabung
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <input type="date"
                               name="tanggal_bergabung"
                               id="tanggal_bergabung"
                               value="{{ old('tanggal_bergabung', now()->format('Y-m-d')) }}"
                               required
                               style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('tanggal_bergabung') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; transition:border-color 150ms, box-shadow 150ms;"
                               onfocus="this.style.borderColor='#00236f'; this.style.boxShadow='0 0 0 3px rgba(0,35,111,0.1)';"
                               onblur="this.style.borderColor='{{ $errors->has('tanggal_bergabung') ? '#ba1a1a' : '#c5c5d3' }}'; this.style.boxShadow='none';">
                        <p style="font-size:11px; color:#757682;">Otomatis diisi hari ini, ubah jika diperlukan.</p>
                        @error('tanggal_bergabung')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Keanggotaan --}}
                    <div style="display:flex; flex-direction:column; gap:5px;">
                        <label for="status" style="font-size:13px; font-weight:600; color:#171c1f;">
                            Status Keanggotaan
                            <span style="color:#ba1a1a; margin-left:2px;">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                required
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $errors->has('status') ? '#ba1a1a' : '#c5c5d3' }}; border-radius:8px; font-size:14px; color:#171c1f; background:#fff; outline:none; appearance:auto; transition:border-color 150ms;">
                            <option value="aktif"    {{ old('status', 'aktif') === 'aktif'    ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>❌ Non-Aktif</option>
                        </select>
                        @error('status')
                            <p style="font-size:12px; color:#ba1a1a; font-weight:600;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Action Buttons ───────────────────────────────────────────── --}}
            <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:20px; border-top:1.5px solid #eaeef2;">
                <a href="{{ route('anggota.index') }}" class="btn-ghost">
                    <span class="material-symbols-outlined" style="font-size:17px; display:block;">close</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size:17px; display:block;">person_add</span>
                    <span>Simpan Anggota</span>
                </button>
            </div>

        </form>
    </div>

</div>
</div>
@endsection
