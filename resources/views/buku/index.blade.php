@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="p-6 max-w-[1280px] mx-auto fade-in">
<div class="space-y-6">
    {{-- Header Page --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-headline-md font-bold text-on-surface">Katalog Buku</h2>
            <p class="text-body-md text-on-surface-variant mt-0.5">Kelola koleksi buku dan ketersediaan stok perpustakaan.</p>
        </div>
        <a href="{{ route('buku.create') }}" class="btn-primary">
            <span class="material-symbols-outlined text-lg">library_add</span>
            <span>Tambah Buku</span>
        </a>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('buku.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            {{-- Search Input --}}
            <div class="flex-1 w-full">
                <label for="search" class="form-label font-semibold">Cari Buku</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ $search }}"
                           placeholder="Masukkan Judul, ISBN, atau pengarang..."
                           class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                </div>
            </div>

            {{-- Kategori Dropdown --}}
            <div class="w-full lg:w-48">
                <label for="kategori" class="form-label font-semibold">Kategori</label>
                <select name="kategori" 
                        id="kategori" 
                        class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ $kategori == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Dropdown --}}
            <div class="w-full lg:w-44">
                <label for="status" class="form-label font-semibold">Status</label>
                <select name="status" 
                        id="status" 
                        class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-lg text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ $status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ $status === 'habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="btn-secondary w-full lg:w-auto justify-center">
                    <span class="material-symbols-outlined text-lg">filter_list</span>
                    <span>Filter</span>
                </button>
                @if($search || $kategori || $status)
                <a href="{{ route('buku.index') }}" class="btn-ghost border border-outline-variant w-full lg:w-auto justify-center">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Books Table Card --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px]">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-low">
                        <th class="table-header w-12">No</th>
                        <th class="table-header">Buku</th>
                        <th class="table-header">Pengarang & Penerbit</th>
                        <th class="table-header">Kategori</th>
                        <th class="table-header text-center">Rak</th>
                        <th class="table-header text-center">Stok</th>
                        <th class="table-header text-center">Status</th>
                        <th class="table-header text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($buku as $index => $bk)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="table-cell text-center text-sm font-semibold text-on-surface-variant">
                            {{ $buku->firstItem() + $index }}
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center gap-3">
                                {{-- Book Cover Placeholder --}}
                                <div class="w-10 h-14 bg-gradient-to-br from-primary to-indigo-950 text-white flex flex-col justify-between p-1 rounded shadow text-[8px] leading-tight flex-shrink-0 text-center font-bold">
                                    <span class="truncate block text-slate-300">LMS</span>
                                    <span class="line-clamp-2 block">{{ substr($bk->judul, 0, 15) }}</span>
                                    <span class="text-[6px] text-slate-300">ISBN</span>
                                </div>
                                <div>
                                    <div class="font-bold text-on-surface text-base hover:text-primary">
                                        <a href="{{ route('buku.show', $bk->id) }}">{{ $bk->judul }}</a>
                                    </div>
                                    <div class="text-caption text-on-surface-variant">ISBN: {{ $bk->isbn }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-cell">
                            <div class="text-sm font-medium">{{ $bk->pengarang }}</div>
                            <div class="text-caption text-on-surface-variant">
                                {{ $bk->penerbit ?? '-' }} ({{ $bk->tahun_terbit ?? '-' }})
                            </div>
                        </td>
                        <td class="table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-container text-on-surface-variant">
                                {{ $bk->kategori ? $bk->kategori->nama : 'Umum' }}
                            </span>
                        </td>
                        <td class="table-cell text-center font-medium text-slate-600">
                            {{ $bk->lokasi_rak ?? '-' }}
                        </td>
                        <td class="table-cell text-center">
                            <span class="font-bold text-primary">{{ $bk->stok_tersedia }}</span>
                            <span class="text-slate-400">/</span>
                            <span class="text-slate-500">{{ $bk->stok_total }}</span>
                        </td>
                        <td class="table-cell text-center">
                            @if($bk->status === 'tersedia')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                Tersedia
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                                Habis
                            </span>
                            @endif
                        </td>
                        <td class="table-cell text-right">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Lihat --}}
                                <a href="{{ route('buku.show', $bk->id) }}" 
                                   title="Lihat Detail" 
                                   class="p-2 text-primary hover:bg-surface-container rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('buku.edit', $bk->id) }}" 
                                   title="Ubah Buku" 
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </a>
                                {{-- Hapus — hanya Admin --}}
                                @can('hapus-buku')
                                <form method="POST" action="{{ route('buku.destroy', $bk->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku {{ $bk->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Hapus Buku" 
                                            class="p-2 text-error hover:bg-error-container rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="table-cell text-center py-12 text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-4xl text-slate-300">library_books</span>
                                <p class="text-body-md font-semibold">Data buku tidak ditemukan</p>
                                <p class="text-caption">Coba ubah kata kunci pencarian Anda atau hapus filter.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($buku->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest">
            {{ $buku->links() }}
        </div>
        @endif
    </div>
</div>
</div>
@endsection
