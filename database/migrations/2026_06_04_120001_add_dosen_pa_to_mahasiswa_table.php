<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreignId('dosen_pa_id')->nullable()->after('angkatan')
                  ->constrained('dosen_pa')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\DosenPa::class);
            $table->dropColumn('dosen_pa_id');
        });
    }
};
