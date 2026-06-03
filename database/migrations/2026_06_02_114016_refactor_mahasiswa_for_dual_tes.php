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
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Status per-jenis (menggantikan sudah_tes)
            $table->boolean('sudah_tes_minat')->default(false)->after('sudah_tes');
            $table->boolean('sudah_tes_bakat')->default(false)->after('sudah_tes_minat');

            // Draft & urutan per-jenis (menggantikan jawaban_draft & urutan_soal)
            $table->json('draft_minat')->nullable()->after('jawaban_draft');
            $table->json('draft_bakat')->nullable()->after('draft_minat');
            $table->json('urutan_minat')->nullable()->after('urutan_soal');
            $table->json('urutan_bakat')->nullable()->after('urutan_minat');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['sudah_tes_minat','sudah_tes_bakat','draft_minat','draft_bakat','urutan_minat','urutan_bakat']);
        });
    }
};
