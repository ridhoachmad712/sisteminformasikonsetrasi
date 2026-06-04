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
            $table->timestamp('last_activity_at')->nullable()->after('session_token');
            // Tipe tes yang sedang dikerjakan (untuk monitor): 'minat' / 'bakat' / null
            $table->string('tes_aktif', 10)->nullable()->after('last_activity_at');
            $table->index('last_activity_at', 'mahasiswa_last_activity_index');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropIndex('mahasiswa_last_activity_index');
            $table->dropColumn(['last_activity_at', 'tes_aktif']);
        });
    }
};
