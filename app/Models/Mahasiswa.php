<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim', 'nama', 'angkatan', 'aktif', 'dosen_pa_id', 'email', 'password',
        'sudah_tes', 'sudah_tes_minat', 'sudah_tes_bakat',
        'session_token',
        'jawaban_draft', 'urutan_soal',   // legacy, tetap untuk kompatibilitas
        'draft_minat', 'draft_bakat', 'urutan_minat', 'urutan_bakat',
        'nilai_matkul', 'sudah_input_nilai', 'ipk',
        'pilihan_konsentrasi', 'sudah_pilih_konsentrasi',
        'last_activity_at', 'tes_aktif',
    ];

    protected $casts = [
        'aktif'             => 'boolean',
        'sudah_tes'         => 'boolean',
        'sudah_tes_minat'   => 'boolean',
        'sudah_tes_bakat'   => 'boolean',
        'jawaban_draft'     => 'array',
        'urutan_soal'       => 'array',
        'draft_minat'       => 'array',
        'draft_bakat'       => 'array',
        'urutan_minat'      => 'array',
        'urutan_bakat'      => 'array',
        'nilai_matkul'             => 'array',
        'sudah_input_nilai'        => 'boolean',
        'pilihan_konsentrasi'      => 'array',
        'sudah_pilih_konsentrasi'  => 'boolean',
        'last_activity_at'         => 'datetime',
    ];

    /** Label konsentrasi */
    public static function labelKonsentrasi(string $key): string
    {
        return [
            'pemasaran' => 'Manajemen Pemasaran',
            'keuangan'  => 'Manajemen Keuangan',
            'sdm'       => 'Manajemen SDM',
        ][$key] ?? $key;
    }

    /** Kedua tes sudah selesai */
    public function getBothTesCompleteAttribute(): bool
    {
        return $this->sudah_tes_minat && $this->sudah_tes_bakat;
    }

    /**
     * Rata-rata nilai mata kuliah pendukung per konsentrasi (skala 0-100).
     * Return: ['keuangan'=>['avg'=>93,'kontribusi'=>23.25,'detail'=>[...]], ...]
     * Null jika mahasiswa belum input nilai.
     */
    public function nilaiMkPerKonsentrasi(): ?array
    {
        if (!$this->sudah_input_nilai || empty($this->nilai_matkul)) {
            return null;
        }

        $bobot = config('matakuliah.bobot');
        $grup  = config('matakuliah.mata_kuliah');
        $hasil = [];

        foreach ($grup as $konsentrasi => $data) {
            $nilaiList = [];
            $detail    = [];
            foreach ($data['items'] as $key => $namaMk) {
                $huruf = $this->nilai_matkul[$key] ?? null;
                $angka = $bobot[$huruf] ?? 0;
                $nilaiList[] = $angka;
                $detail[] = ['mk' => $namaMk, 'huruf' => $huruf, 'angka' => $angka];
            }
            $avg = count($nilaiList) ? round(array_sum($nilaiList) / count($nilaiList)) : 0;
            $hasil[$konsentrasi] = [
                'label'      => $data['label'],
                'warna'      => $data['warna'],
                'avg'        => $avg,
                'kontribusi' => round($avg * 0.25, 2), // bobot 25%
                'detail'     => $detail,
            ];
        }

        return $hasil;
    }

    protected $hidden = ['password'];

    public function hasilTes()
    {
        return $this->hasMany(HasilTes::class);
    }

    public function hasilTesTerakhir()
    {
        return $this->hasOne(HasilTes::class)->latestOfMany();
    }

    public function dosenPa()
    {
        return $this->belongsTo(DosenPa::class, 'dosen_pa_id');
    }
}
