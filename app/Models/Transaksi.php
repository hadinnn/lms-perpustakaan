<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'anggota_id',
        'buku_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'denda',
        'denda_dibayar',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
        'denda' => 'integer',
        'denda_dibayar' => 'boolean',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public static function generateKode(): string
    {
        $prefix = 'TRX';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function hitungDenda(): int
    {
        $tanggalKembali = $this->tanggal_kembali ?? Carbon::today();
        if ($tanggalKembali->gt($this->tanggal_jatuh_tempo)) {
            $selisihHari = $this->tanggal_jatuh_tempo->diffInDays($tanggalKembali);
            $dendaPerHari = (int) env('FINE_PER_DAY', 1000);
            return $selisihHari * $dendaPerHari;
        }
        return 0;
    }

    public function getTerlambatAttribute(): bool
    {
        if ($this->status === 'dikembalikan') return false;
        return Carbon::today()->gt($this->tanggal_jatuh_tempo);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->terlambat) return 0;
        return $this->tanggal_jatuh_tempo->diffInDays(Carbon::today());
    }

    public function getDendaPerkiraanAttribute(): int
    {
        return $this->hariTerlambat * (int) env('FINE_PER_DAY', 1000);
    }
}
