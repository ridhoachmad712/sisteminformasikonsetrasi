<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konversi Nilai Huruf → Bobot (skala 4.0)
    |--------------------------------------------------------------------------
    */
    'bobot' => [
        'A'  => 96,
        'A-' => 88,
        'B+' => 83,
        'B'  => 78,
        'B-' => 73,
        'C+' => 68,
        'C'  => 63,
        'C-' => 58,
        'D'  => 48,
        'E'  => 21,
        'K'  => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Daftar Nilai Huruf yang Valid (urutan tampil di dropdown)
    |--------------------------------------------------------------------------
    */
    'pilihan' => ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'E'],

    /*
    |--------------------------------------------------------------------------
    | Mata Kuliah per Konsentrasi
    | key dipakai sebagai penyimpanan; label untuk tampilan.
    |--------------------------------------------------------------------------
    */
    'mata_kuliah' => [
        'keuangan' => [
            'label' => 'Manajemen Keuangan',
            'warna' => '#12b76a',
            'items' => [
                'manajemen_keuangan' => 'Manajemen Keuangan',
                'matematika_ekonomi' => 'Matematika Ekonomi',
                'manajemen_perbankan' => 'Manajemen Perbankan',
            ],
        ],
        'sdm' => [
            'label' => 'Manajemen SDM',
            'warna' => '#f79009',
            'items' => [
                'manajemen_sdm' => 'Manajemen SDM',
                'perilaku_keorganisasian' => 'Perilaku Keorganisasian',
                'teori_pengambilan_keputusan' => 'Teori Pengambilan Keputusan',
            ],
        ],
        'pemasaran' => [
            'label' => 'Manajemen Pemasaran',
            'warna' => '#465fff',
            'items' => [
                'manajemen_pemasaran' => 'Manajemen Pemasaran',
                'etika_komunikasi_bisnis' => 'Etika dan Komunikasi Bisnis',
                'manajemen_strategik' => 'Manajemen Strategik',
            ],
        ],
    ],
];
