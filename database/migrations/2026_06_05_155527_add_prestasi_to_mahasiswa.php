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
            // {"pemasaran":15,"keuangan":0,"sdm":10} — skor 0-15 per konsentrasi
            $table->json('prestasi_relevan')->nullable()->after('ipk');
            $table->text('catatan_prestasi')->nullable()->after('prestasi_relevan');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['prestasi_relevan', 'catatan_prestasi']);
        });
    }
};
