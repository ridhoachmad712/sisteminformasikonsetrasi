<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            // Snapshot jumlah soal saat submit — untuk konsistensi perhitungan skor
            $table->unsignedTinyInteger('jml_soal_minat_pemasaran')->default(0)->after('skor_bakat_sdm');
            $table->unsignedTinyInteger('jml_soal_minat_keuangan')->default(0)->after('jml_soal_minat_pemasaran');
            $table->unsignedTinyInteger('jml_soal_minat_sdm')->default(0)->after('jml_soal_minat_keuangan');
            $table->unsignedTinyInteger('jml_soal_bakat_pemasaran')->default(0)->after('jml_soal_minat_sdm');
            $table->unsignedTinyInteger('jml_soal_bakat_keuangan')->default(0)->after('jml_soal_bakat_pemasaran');
            $table->unsignedTinyInteger('jml_soal_bakat_sdm')->default(0)->after('jml_soal_bakat_keuangan');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->dropColumn([
                'jml_soal_minat_pemasaran', 'jml_soal_minat_keuangan', 'jml_soal_minat_sdm',
                'jml_soal_bakat_pemasaran', 'jml_soal_bakat_keuangan', 'jml_soal_bakat_sdm',
            ]);
        });
    }
};
