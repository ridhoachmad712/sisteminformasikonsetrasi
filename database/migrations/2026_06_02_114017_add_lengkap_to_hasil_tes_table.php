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
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->boolean('sudah_minat')->default(false)->after('rekomendasi');
            $table->boolean('sudah_bakat')->default(false)->after('sudah_minat');
            // lengkap = true saat kedua tes sudah disubmit & nilai_akhir sudah dihitung
            $table->boolean('lengkap')->default(false)->after('sudah_bakat');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->dropColumn(['sudah_minat', 'sudah_bakat', 'lengkap']);
        });
    }
};
