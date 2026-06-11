<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 20)->unique();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('buku_id')->constrained('buku')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->integer('denda')->default(0)->comment('Total denda dalam rupiah');
            $table->boolean('denda_dibayar')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
