<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;

class PengumumanController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::find(session('mahasiswa_id'));
        return view('pengumuman.index', compact('mahasiswa'));
    }

    public function hasil()
    {
        $mahasiswa = Mahasiswa::find(session('mahasiswa_id'));

        if (!$mahasiswa || !$mahasiswa->hasil_final) {
            return redirect()->route('pengumuman')->with('info', 'Hasil konsentrasi Anda belum tersedia.');
        }

        $info = $this->infoKonsentrasi($mahasiswa->hasil_final, $mahasiswa->skor_final);

        return view('pengumuman.hasil', compact('mahasiswa', 'info'));
    }

    private function infoKonsentrasi(string $hasil, ?float $skor): array
    {
        $map = [
            'pemasaran' => [
                'label'        => 'Manajemen Pemasaran',
                'label_singkat'=> 'Pemasaran',
                'color'        => '#465fff',
                'emoji'        => '🎯',
                'judul'        => 'SELAMAT! ANDA DINYATAKAN LOLOS KE KONSENTRASI MANAJEMEN PEMASARAN',
                'pesan'        => 'Pencapaian ini merupakan hasil dari usaha, dedikasi, dan kompetensi yang telah Anda tunjukkan selama masa studi. Semoga konsentrasi yang telah dipilih menjadi wadah untuk mengembangkan potensi, memperdalam keahlian, serta mempersiapkan diri menjadi lulusan yang unggul dan profesional di bidang Manajemen Pemasaran.',
                'penutup'      => 'Teruslah belajar, berprestasi, dan memberikan kontribusi terbaik bagi organisasi, masyarakat, dan dunia kerja di masa depan.',
                'ajakan'       => 'Selamat bergabung di Konsentrasi Manajemen Pemasaran! 🚀',
            ],
            'keuangan' => [
                'label'        => 'Manajemen Keuangan',
                'label_singkat'=> 'Keuangan',
                'color'        => '#12b76a',
                'emoji'        => '💹',
                'judul'        => 'SELAMAT! ANDA DINYATAKAN LOLOS KE KONSENTRASI MANAJEMEN KEUANGAN',
                'pesan'        => 'Pencapaian ini merupakan hasil dari usaha, dedikasi, dan kompetensi yang telah Anda tunjukkan selama masa studi. Semoga konsentrasi yang telah dipilih menjadi wadah untuk mengembangkan potensi, memperdalam keahlian, serta mempersiapkan diri menjadi lulusan yang unggul dan profesional di bidang Manajemen Keuangan.',
                'penutup'      => 'Teruslah belajar, berprestasi, dan memberikan kontribusi terbaik bagi organisasi, masyarakat, dan dunia kerja di masa depan.',
                'ajakan'       => 'Selamat bergabung di Konsentrasi Manajemen Keuangan! 🚀',
            ],
            'sdm' => [
                'label'        => 'Manajemen Sumber Daya Manusia (SDM)',
                'label_singkat'=> 'SDM',
                'color'        => '#f79009',
                'emoji'        => '🤝',
                'judul'        => 'SELAMAT! ANDA DINYATAKAN LOLOS KE KONSENTRASI MANAJEMEN SUMBER DAYA MANUSIA (SDM)',
                'pesan'        => 'Pencapaian ini merupakan hasil dari usaha, dedikasi, dan kompetensi yang telah Anda tunjukkan selama masa studi. Semoga konsentrasi yang telah dipilih menjadi wadah untuk mengembangkan potensi, memperdalam keahlian, serta mempersiapkan diri menjadi lulusan yang unggul dan profesional di bidang Manajemen Sumber Daya Manusia.',
                'penutup'      => 'Teruslah belajar, berprestasi, dan memberikan kontribusi terbaik bagi organisasi, masyarakat, dan dunia kerja di masa depan.',
                'ajakan'       => 'Selamat bergabung di Konsentrasi Manajemen Sumber Daya Manusia! 🚀',
            ],
        ];

        $info = $map[$hasil] ?? $map['pemasaran'];
        $info['skor_label'] = $this->labelSkor($skor);

        return $info;
    }

    private function labelSkor(?float $skor): string
    {
        if (!$skor || $skor <= 0) return 'Data tidak tersedia';
        if ($skor >= 90) return 'Sangat Tinggi';
        if ($skor >= 80) return 'Tinggi';
        if ($skor >= 70) return 'Cukup';
        if ($skor >= 60) return 'Rendah';
        return 'Sangat Rendah';
    }
}
