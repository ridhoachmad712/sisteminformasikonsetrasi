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
        // mahasiswa — query paling sering: login (nim), middleware (id+session_token), filter angkatan
        Schema::table('mahasiswa', function (Blueprint $table) {
            // nim sudah unique (otomatis ada index), tapi pastikan ada
            if (!$this->indexExists('mahasiswa', 'mahasiswa_session_token_index')) {
                $table->index('session_token', 'mahasiswa_session_token_index');
            }
            if (!$this->indexExists('mahasiswa', 'mahasiswa_angkatan_index')) {
                $table->index('angkatan', 'mahasiswa_angkatan_index');
            }
            if (!$this->indexExists('mahasiswa', 'mahasiswa_sudah_tes_minat_index')) {
                $table->index(['sudah_tes_minat', 'sudah_tes_bakat'], 'mahasiswa_tes_status_index');
            }
        });

        // soal — query sering: WHERE jenis='minat' AND aktif=1
        Schema::table('soal', function (Blueprint $table) {
            if (!$this->indexExists('soal', 'soal_jenis_aktif_index')) {
                $table->index(['jenis', 'aktif'], 'soal_jenis_aktif_index');
            }
            if (!$this->indexExists('soal', 'soal_konsentrasi_index')) {
                $table->index('konsentrasi', 'soal_konsentrasi_index');
            }
        });

        // hasil_tes — query admin: filter rekomendasi, join mahasiswa
        Schema::table('hasil_tes', function (Blueprint $table) {
            if (!$this->indexExists('hasil_tes', 'hasil_tes_mahasiswa_id_index')) {
                $table->index('mahasiswa_id', 'hasil_tes_mahasiswa_id_index');
            }
            if (!$this->indexExists('hasil_tes', 'hasil_tes_rekomendasi_index')) {
                $table->index('rekomendasi', 'hasil_tes_rekomendasi_index');
            }
            if (!$this->indexExists('hasil_tes', 'hasil_tes_lengkap_index')) {
                $table->index('lengkap', 'hasil_tes_lengkap_index');
            }
        });

        // detail_jawaban — query admin show: WHERE hasil_tes_id=?
        Schema::table('detail_jawaban', function (Blueprint $table) {
            if (!$this->indexExists('detail_jawaban', 'detail_jawaban_hasil_id_index')) {
                $table->index('hasil_tes_id', 'detail_jawaban_hasil_id_index');
            }
        });

        // jadwal_tes — query sering: WHERE aktif=1 AND tanggal_selesai >= now()
        Schema::table('jadwal_tes', function (Blueprint $table) {
            if (!$this->indexExists('jadwal_tes', 'jadwal_tes_aktif_index')) {
                $table->index(['aktif', 'tanggal_mulai', 'tanggal_selesai'], 'jadwal_tes_aktif_dates_index');
            }
            if (!$this->indexExists('jadwal_tes', 'jadwal_tes_angkatan_index')) {
                $table->index(['angkatan', 'jenis_tes'], 'jadwal_tes_angkatan_jenis_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropIndex('mahasiswa_session_token_index');
            $table->dropIndex('mahasiswa_angkatan_index');
            $table->dropIndex('mahasiswa_tes_status_index');
        });
        Schema::table('soal', function (Blueprint $table) {
            $table->dropIndex('soal_jenis_aktif_index');
            $table->dropIndex('soal_konsentrasi_index');
        });
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->dropIndex('hasil_tes_mahasiswa_id_index');
            $table->dropIndex('hasil_tes_rekomendasi_index');
            $table->dropIndex('hasil_tes_lengkap_index');
        });
        Schema::table('detail_jawaban', function (Blueprint $table) {
            $table->dropIndex('detail_jawaban_hasil_id_index');
        });
        Schema::table('jadwal_tes', function (Blueprint $table) {
            $table->dropIndex('jadwal_tes_aktif_dates_index');
            $table->dropIndex('jadwal_tes_angkatan_jenis_index');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return collect(\DB::select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')
            ->contains($indexName);
    }
};
