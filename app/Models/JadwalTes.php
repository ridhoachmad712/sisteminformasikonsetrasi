<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTes extends Model
{
    protected $table = 'jadwal_tes';

    protected $fillable = ['nama', 'angkatan', 'jenis_tes', 'tanggal_mulai', 'tanggal_selesai', 'aktif', 'keterangan'];

    public function getLabelJenisAttribute(): string
    {
        return match ($this->jenis_tes) {
            'minat' => 'Tes Minat',
            'bakat' => 'Tes Bakat',
            default => 'Minat & Bakat',
        };
    }

    protected $casts = [
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
        'aktif'           => 'boolean',
    ];

    /** Cek apakah jadwal ini sedang berlangsung sekarang */
    public function getSedangBerlangsungAttribute(): bool
    {
        $now = now();
        return $this->aktif
            && $now->gte($this->tanggal_mulai)
            && $now->lte($this->tanggal_selesai);
    }

    /** Cek apakah jadwal belum dimulai */
    public function getBelumMulaiAttribute(): bool
    {
        return $this->aktif && now()->lt($this->tanggal_mulai);
    }

    /** Cek apakah jadwal sudah berakhir */
    public function getSudahBerakhirAttribute(): bool
    {
        return now()->gt($this->tanggal_selesai);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->aktif) return 'nonaktif';
        if ($this->belum_mulai) return 'belum_mulai';
        if ($this->sedang_berlangsung) return 'berlangsung';
        return 'selesai';
    }

    /**
     * Cari jadwal aktif untuk angkatan + jenis tes tertentu.
     * Prioritas: spesifik angkatan > global (null angkatan)
     * Jenis: spesifik jenis ('minat'/'bakat') > berlaku semua (null jenis_tes)
     */
    public static function getUntukAngkatanDanJenis(string $angkatan, string $jenis): ?self
    {
        $query = fn($q) => $q->where('aktif', true)
            ->where(fn($q2) => $q2->where('jenis_tes', $jenis)->orWhereNull('jenis_tes'))
            ->orderByRaw("CASE WHEN jenis_tes = ? THEN 0 ELSE 1 END", [$jenis])
            ->orderBy('tanggal_mulai', 'desc');

        // Spesifik angkatan dulu
        $jadwal = self::where('angkatan', $angkatan)
            ->tap($query)
            ->first();

        if ($jadwal) return $jadwal;

        // Fallback global
        return self::whereNull('angkatan')
            ->tap($query)
            ->first();
    }

    /** Helper: jadwal tanpa filter jenis (untuk halaman terkunci lama) */
    public static function getUntukAngkatan(string $angkatan): ?self
    {
        return self::getUntukAngkatanDanJenis($angkatan, 'minat') // default cek minat
            ?? self::getUntukAngkatanDanJenis($angkatan, 'bakat');
    }
}
