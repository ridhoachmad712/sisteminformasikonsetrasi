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
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->decimal('nilai_pemasaran', 5, 2)->default(0);
            $table->decimal('nilai_keuangan', 5, 2)->default(0);
            $table->decimal('nilai_sdm', 5, 2)->default(0);
            $table->enum('rekomendasi', ['pemasaran', 'keuangan', 'sdm']);
            $table->decimal('skor_minat_pemasaran', 5, 2)->default(0);
            $table->decimal('skor_minat_keuangan', 5, 2)->default(0);
            $table->decimal('skor_minat_sdm', 5, 2)->default(0);
            $table->decimal('skor_bakat_pemasaran', 5, 2)->default(0);
            $table->decimal('skor_bakat_keuangan', 5, 2)->default(0);
            $table->decimal('skor_bakat_sdm', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_tes');
    }
};
