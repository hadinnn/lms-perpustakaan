@extends('layouts.app')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header Page --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-headline-md font-bold text-on-surface">Manajemen Anggota</h2>
            <p class="text-body-md text-on-surface-variant mt-0.5">Kelola data pendaftaran dan keanggotaan perpustakaan.</p>
        </div>
        <a href="{{ route('anggota.create') }}" class="btn-primary">
            <span class="material-symbols-outlined text-lg">person_add</span>
            <span>Tambah Anggota</span>
        </a>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('anggota.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            {{-- Search Input --}}
            <div class="flex-1 w-full">
                <label for="search" class="form-label font-semibold">Cari Anggota</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ $search }}"
                           placeholder="Masukkan Nama, NIK, atau nomor telepon..."
                           class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                </div>
            </div>

            {{-- Status Dropdown --}}
            <div class="w-full md:w-48">
                <label for="status" class="form-label font-semibold">Status</label>
                <select name="status" 
                        id="status" 
                        class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            {{-- Submit & Reset Buttons --}}
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="btn-secondary w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-lg">filter_list</span>
                    <span>Filter</span>
                </button>
                @if($search || $status)
                <a href="{{ route('anggota.index') }}" class="btn-ghost border border-outline-variant w-full md:w-auto justify-center">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Members List Table Card --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-low">
                        <th class="table-header w-12">No</th>
                        <th class="table-header">Anggota</th>
                        <th class="table-header">Kontak & Email</th>
                        <th class="table-header">Jenis Kelamin</th>
                        <th class="table-header text-center">Status</th>
                        <th class="table-header text-center">Pinjaman Aktif</th>
                        <th class="table-header">Bergabung Sejak</th>
                        <th class="table-header text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($anggota as $index => $agt)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="table-cell text-center text-sm font-semibold text-on-surface-variant">
                            {{ $anggota->firstItem() + $index }}
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-sm flex-shrink-0 uppercase">
                                    {{ $agt->initials }}
                                </div>
                                <div>
                                    <div class="font-bold text-on-surface text-base hover:text-primary">
                                        <a href="{{ route('anggota.show', $agt->id) }}">{{ $agt->nama }}</a>
                                    </div>
                                    <div class="text-caption text-on-surface-variant">NIK: {{ $agt->nik }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-cell">
                            <div class="text-sm font-medium">{{ $agt->telepon ?? '-' }}</div>
                            <div class="text-caption text-on-surface-variant">{{ $agt->email ?? '-' }}</div>
                        </td>
                        <td class="table-cell">
                            <span class="text-body-md">
                                {{ $agt->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="table-cell text-center">
                            @if($agt->status === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                Non-Aktif
                            </span>
                            @endif
                        </td>
                        <td class="table-cell text-center font-bold">
                            @if($agt->transaksi_aktif_count > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                {{ $agt->transaksi_aktif_count }} Buku
                            </span>
                            @else
                            <span class="text-slate-400 font-normal">-</span>
                            @endif
                        </td>
                        <td class="table-cell text-sm">
                            {{ $agt->tanggal_bergabung ? $agt->tanggal_bergabung->isoFormat('D MMMM Y') : '-' }}
                        </td>
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Lihat --}}
                                <a href="{{ route('anggota.show', $agt->id) }}" 
                                   title="Lihat Detail" 
                                   class="p-2 text-primary hover:bg-surface-container rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('anggota.edit', $agt->id) }}" 
                                   title="Ubah Anggota" 
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </a>
                                {{-- Hapus --}}
                                <form method="POST" action="{{ route('anggota.destroy', $agt->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota {{ $agt->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Hapus Anggota" 
                                            class="p-2 text-error hover:bg-error-container rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="table-cell text-center py-12 text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300">group_off</span>
                                <p class="text-body-md font-semibold">Data anggota tidak ditemukan</p>
                                <p class="text-caption">Coba masukkan kata kunci pencarian lain atau pilih filter yang berbeda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($anggota->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest">
            {{ $anggota->links() }}
        </div>
        @endif
    </div>
</div>
</div>
@endsection
