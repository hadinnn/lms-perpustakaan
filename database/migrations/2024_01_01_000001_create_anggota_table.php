<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tanggal_bergabung')->default(now());
            $table->string('no_kartu')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
