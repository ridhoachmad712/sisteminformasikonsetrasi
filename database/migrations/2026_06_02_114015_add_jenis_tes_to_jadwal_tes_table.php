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
        Schema::table('jadwal_tes', function (Blueprint $table) {
            // null = berlaku untuk kedua jenis tes (minat & bakat)
            $table->enum('jenis_tes', ['minat', 'bakat'])->nullable()->after('angkatan');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_tes', function (Blueprint $table) {
            $table->dropColumn('jenis_tes');
        });
    }
};
