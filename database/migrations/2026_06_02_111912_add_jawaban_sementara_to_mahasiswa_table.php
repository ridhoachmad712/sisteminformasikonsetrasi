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
            // JSON draft: {"soal_id": nilai, ...} + urutan soal
            $table->json('jawaban_draft')->nullable()->after('session_token');
            $table->json('urutan_soal')->nullable()->after('jawaban_draft');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['jawaban_draft', 'urutan_soal']);
        });
    }
};
