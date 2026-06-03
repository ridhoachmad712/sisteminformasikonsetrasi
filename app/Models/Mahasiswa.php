<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim', 'nama', 'angkatan', 'email', 'password',
        'sudah_tes', 'sudah_tes_minat', 'sudah_tes_bakat',
        'session_token',
        'jawaban_draft', 'urutan_soal',   // legacy, tetap untuk kompatibilitas
        'draft_minat', 'draft_bakat', 'urutan_minat', 'urutan_bakat',
        'nilai_matkul', 'sudah_input_nilai',
    ];

    protected $casts = [
        'sudah_tes'         => 'boolean',
        'sudah_tes_minat'   => 'boolean',
        'sudah_tes_bakat'   => 'boolean',
        'jawaban_draft'     => 'array',
        'urutan_soal'       => 'array',
        'draft_minat'       => 'array',
        'draft_bakat'       => 'array',
        'urutan_minat'      => 'array',
        'urutan_bakat'      => 'array',
        'nilai_matkul'      => 'array',
        'sudah_input_nilai' => 'boolean',
    ];

    /** Kedua tes sudah selesai */
    public function getBothTesCompleteAttribute(): bool
    {
        return $this->sudah_tes_minat && $this->sudah_tes_bakat;
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
}
