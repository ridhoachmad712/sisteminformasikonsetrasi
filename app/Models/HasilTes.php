<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    protected $table = 'hasil_tes';

    protected $fillable = [
        'mahasiswa_id', 'nilai_pemasaran', 'nilai_keuangan', 'nilai_sdm', 'rekomendasi',
        'sudah_minat', 'sudah_bakat', 'lengkap',
        'skor_minat_pemasaran', 'skor_minat_keuangan', 'skor_minat_sdm',
        'skor_bakat_pemasaran', 'skor_bakat_keuangan', 'skor_bakat_sdm',
        'jml_soal_minat_pemasaran', 'jml_soal_minat_keuangan', 'jml_soal_minat_sdm',
        'jml_soal_bakat_pemasaran', 'jml_soal_bakat_keuangan', 'jml_soal_bakat_sdm',
    ];

    protected $casts = [
        'sudah_minat' => 'boolean',
        'sudah_bakat' => 'boolean',
        'lengkap'     => 'boolean',
    ];

    /**
     * Hitung ulang nilai akhir & rekomendasi setelah kedua skor lengkap.
     * Formula: (skorMinat/sMaxMinat×100×60%) + (skorBakat/sMaxBakat×100×40%)
     */
    public function hitungNilaiAkhir(): void
    {
        $hasil = [];
        foreach (['pemasaran', 'keuangan', 'sdm'] as $k) {
            $minatRaw = $this->{"skor_minat_{$k}"};
            $bakatRaw = $this->{"skor_bakat_{$k}"};

            // Gunakan snapshot jumlah soal saat submit — bukan jumlah soal aktif saat ini
            $jmlMinat = $this->{"jml_soal_minat_{$k}"} ?: 1;
            $jmlBakat = $this->{"jml_soal_bakat_{$k}"} ?: 1;

            $hasil[$k] = round(
                ($minatRaw / ($jmlMinat * 5) * 100 * 0.6) +
                ($bakatRaw / ($jmlBakat * 5) * 100 * 0.4),
                2
            );
        }

        arsort($hasil);
        $this->update([
            'nilai_pemasaran' => $hasil['pemasaran'],
            'nilai_keuangan'  => $hasil['keuangan'],
            'nilai_sdm'       => $hasil['sdm'],
            'rekomendasi'     => array_key_first($hasil),
            'lengkap'         => true,
        ]);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawaban::class);
    }

    public function getLabelRekomendasiAttribute(): string
    {
        return match ($this->rekomendasi) {
            'pemasaran' => 'Manajemen Pemasaran',
            'keuangan'  => 'Manajemen Keuangan',
            'sdm'       => 'Manajemen Sumber Daya Manusia',
            default     => '-',
        };
    }
}
