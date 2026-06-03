<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konversi Nilai Huruf → Bobot (skala 4.0)
    |--------------------------------------------------------------------------
    */
    'bobot' => [
        'A'  => 4.0,
        'A-' => 3.7,
        'B+' => 3.3,
        'B'  => 3.0,
        'B-' => 2.7,
        'C+' => 2.3,
        'C'  => 2.0,
        'C-' => 1.7,
        'D'  => 1.0,
        'E'  => 0.0,
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
