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
        'prestasi_relevan', 'catatan_prestasi',
        'hasil_final', 'skor_final',
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
        'prestasi_relevan'         => 'array',
    ];

    /**
     * Hitung skor final penentuan konsentrasi.
     * Formula bobot:
     *   - MINAT (Pilihan)         : 40% — Pilihan 1=100, 2=75, 3=50
     *   - Nilai MK Pendukung      : 25% — rata² nilai MK × 25%
     *   - Tes Minat & Bakat       : 15% — hasil gabungan tes × 15%
     *   - Prestasi Relevan        : 15% — skor 0-15 langsung (sudah pada skala bobot)
     *   - IPK                     :  5% — IPK × 25 × 5%
     *
     * Return array dengan breakdown lengkap atau null jika data belum lengkap.
     */
    public function hitungSkorFinal(): ?array
    {
        $konsentrasi = ['pemasaran', 'keuangan', 'sdm'];
        $hasil       = ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];
        $breakdown   = [];

        // ── 1. MINAT (Pilihan Konsentrasi) — 40% ────────────────
        $skorPilihan = ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];
        if ($this->sudah_pilih_konsentrasi && $this->pilihan_konsentrasi) {
            $konversi = [0 => 100, 1 => 75, 2 => 50]; // index → skor
            foreach ($this->pilihan_konsentrasi as $i => $k) {
                if (isset($konversi[$i]) && in_array($k, $konsentrasi)) {
                    $skorPilihan[$k] = $konversi[$i];
                }
            }
        }
        foreach ($konsentrasi as $k) {
            $kontrib = round($skorPilihan[$k] * 0.40, 2);
            $hasil[$k] += $kontrib;
            $breakdown['minat'][$k] = ['mentah' => $skorPilihan[$k], 'kontribusi' => $kontrib];
        }

        // ── 2. Nilai MK Pendukung — 25% ─────────────────────────
        $mkData = $this->nilaiMkPerKonsentrasi();
        foreach ($konsentrasi as $k) {
            $avg     = $mkData[$k]['avg'] ?? 0;
            $kontrib = round($avg * 0.25, 2);
            $hasil[$k] += $kontrib;
            $breakdown['matkul'][$k] = ['mentah' => $avg, 'kontribusi' => $kontrib];
        }

        // ── 3. Tes Minat & Bakat — 15% ──────────────────────────
        $hasilTes = $this->hasilTesTerakhir;
        $skorTes  = ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];
        if ($hasilTes && $hasilTes->lengkap) {
            $skorTes['pemasaran'] = (float) $hasilTes->nilai_pemasaran;
            $skorTes['keuangan']  = (float) $hasilTes->nilai_keuangan;
            $skorTes['sdm']       = (float) $hasilTes->nilai_sdm;
        }
        foreach ($konsentrasi as $k) {
            $kontrib = round($skorTes[$k] * 0.15, 2);
            $hasil[$k] += $kontrib;
            $breakdown['tes'][$k] = ['mentah' => round($skorTes[$k], 2), 'kontribusi' => $kontrib];
        }

        // ── 4. Prestasi Relevan — 15% (langsung skor 0-15) ──────
        $prestasi = $this->prestasi_relevan ?? [];
        foreach ($konsentrasi as $k) {
            $skor    = (int) ($prestasi[$k] ?? 0);
            $kontrib = $skor; // sudah dalam skala bobot
            $hasil[$k] += $kontrib;
            $breakdown['prestasi'][$k] = ['mentah' => $skor, 'kontribusi' => $kontrib];
        }

        // ── 5. IPK — 5% ─────────────────────────────────────────
        $ipk        = (float) ($this->ipk ?? 0);
        $ipkSetara  = $ipk * 25;             // skala 0-100
        $ipkKontrib = round($ipkSetara * 0.05, 2);
        foreach ($konsentrasi as $k) {
            $hasil[$k] += $ipkKontrib;
            $breakdown['ipk'][$k] = ['mentah' => $ipk, 'kontribusi' => $ipkKontrib];
        }

        // ── Total + Rekomendasi ─────────────────────────────────
        foreach ($konsentrasi as $k) $hasil[$k] = round($hasil[$k], 2);
        arsort($hasil);
        $rekomendasi = array_key_first($hasil);

        return [
            'breakdown'   => $breakdown,
            'total'       => $hasil,
            'rekomendasi' => $rekomendasi,
            'lengkap'     => $this->cekKelengkapanData(),
        ];
    }

    /** Cek kelengkapan data untuk perhitungan skor final */
    public function cekKelengkapanData(): array
    {
        return [
            'pilihan'  => $this->sudah_pilih_konsentrasi,
            'matkul'   => $this->sudah_input_nilai,
            'ipk'      => $this->ipk !== null,
            'tes'      => $this->sudah_tes_minat && $this->sudah_tes_bakat,
            'prestasi' => !empty($this->prestasi_relevan),
        ];
    }

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
