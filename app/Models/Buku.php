<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'isbn',
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'kategori_id',
        'stok_total',
        'stok_tersedia',
        'lokasi_rak',
        'cover',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'buku_id');
    }

    public function updateStatusStok(): void
    {
        $this->status = $this->stok_tersedia > 0 ? 'tersedia' : 'habis';
        $this->save();
    }
}
