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
            // ["pemasaran","keuangan","sdm"] urutan = peringkat preferensi (index 0 = pilihan 1)
            $table->json('pilihan_konsentrasi')->nullable()->after('ipk');
            $table->boolean('sudah_pilih_konsentrasi')->default(false)->after('pilihan_konsentrasi');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['pilihan_konsentrasi', 'sudah_pilih_konsentrasi']);
        });
    }
};
