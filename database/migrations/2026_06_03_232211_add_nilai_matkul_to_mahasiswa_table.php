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
            // {"manajemen_keuangan":"A", "matematika_ekonomi":"B+", ...}
            $table->json('nilai_matkul')->nullable()->after('urutan_bakat');
            $table->boolean('sudah_input_nilai')->default(false)->after('nilai_matkul');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['nilai_matkul', 'sudah_input_nilai']);
        });
    }
};
