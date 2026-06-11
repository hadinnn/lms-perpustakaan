<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_buku', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 20)->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_buku')->nullOnDelete();
            $table->integer('stok_total')->default(1);
            $table->integer('stok_tersedia')->default(1);
            $table->string('lokasi_rak')->nullable();
            $table->string('cover')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
        Schema::dropIfExists('kategori_buku');
    }
};
