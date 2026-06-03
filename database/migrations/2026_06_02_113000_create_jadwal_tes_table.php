<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_tes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                          // Nama/label jadwal, contoh: "Tes Konsentrasi 2023"
            $table->string('angkatan', 4)->nullable();       // Null = berlaku untuk semua angkatan
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->boolean('aktif')->default(true);
            $table->text('keterangan')->nullable();          // Pesan yang ditampilkan ke mahasiswa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tes');
    }
};
