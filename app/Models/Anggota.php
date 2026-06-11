<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'email',
        'foto',
        'status',
        'tanggal_bergabung',
        'no_kartu',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'anggota_id');
    }

    public function transaksiAktif(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'anggota_id')->where('status', 'dipinjam');
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nama);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }
}
