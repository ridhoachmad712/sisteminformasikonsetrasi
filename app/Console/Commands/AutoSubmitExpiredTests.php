<?php

namespace App\Console\Commands;

use App\Http\Controllers\TesController;
use App\Models\JadwalTes;
use App\Models\Mahasiswa;
use Illuminate\Console\Command;

class AutoSubmitExpiredTests extends Command
{
    protected $signature   = 'tes:auto-submit-expired';
    protected $description = 'Auto-submit tes mahasiswa yang waktunya habis tapi belum submit';

    public function handle(): void
    {
        // Ambil semua jadwal yang sudah berakhir (lebih dari 5 menit lalu)
        $jadwalExpired = JadwalTes::where('aktif', true)
            ->where('tanggal_selesai', '<', now()->subMinutes(5))
            ->get();

        if ($jadwalExpired->isEmpty()) return;

        $total = 0;

        foreach ($jadwalExpired as $jadwal) {
            // Tentukan jenis tes yang perlu di-auto-submit
            $jenisList = match($jadwal->jenis_tes) {
                'minat'  => ['minat'],
                'bakat'  => ['bakat'],
                default  => ['minat', 'bakat'],
            };

            foreach ($jenisList as $jenis) {
                $sudahKey = "sudah_tes_{$jenis}";

                // Cari mahasiswa yang:
                // - angkatannya sesuai jadwal (atau jadwal untuk semua angkatan)
                // - belum selesai tes jenis ini
                // - sedang aktif tes (tes_aktif = jenis) atau punya draft
                $query = Mahasiswa::where($sudahKey, false)
                    ->where(function ($q) use ($jenis) {
                        $q->where('tes_aktif', $jenis)
                          ->orWhereNotNull("draft_{$jenis}");
                    });

                if ($jadwal->angkatan) {
                    $query->where('angkatan', $jadwal->angkatan);
                }

                $mahasiswaList = $query->get();

                foreach ($mahasiswaList as $mahasiswa) {
                    try {
                        TesController::autoSubmitMahasiswa($mahasiswa, $jenis);
                        $total++;
                        $this->line("  ✓ Auto-submit {$jenis}: {$mahasiswa->nama} ({$mahasiswa->nim})");
                    } catch (\Throwable $e) {
                        $this->error("  ✗ Gagal {$mahasiswa->nim}: {$e->getMessage()}");
                    }
                }
            }
        }

        if ($total > 0) {
            $this->info("Auto-submit selesai: {$total} tes diproses.");
        }
    }
}
