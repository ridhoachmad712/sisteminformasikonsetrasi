<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soal = [
            // === TES MINAT - PEMASARAN (15 soal) ===
            ['teks' => 'Saya merasa nyaman ketika harus berbicara dengan orang yang belum saya kenal sebelumnya.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 1],
            ['teks' => 'Saya tertarik memahami mengapa seseorang memilih satu alternatif dibandingkan alternatif lainnya.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 2],
            ['teks' => 'Saya sering memiliki ide yang berbeda dari kebanyakan orang.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 3],
            ['teks' => 'Saya tidak kesulitan menyampaikan pendapat di hadapan banyak orang.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 4],
            ['teks' => 'Saya menikmati situasi yang memungkinkan saya bertemu banyak orang.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 5],
            ['teks' => 'Saya menikmati kegiatan yang membutuhkan kreativitas dalam menemukan solusi.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 6],
            ['teks' => 'Saya merasa bersemangat ketika terlibat dalam kegiatan yang melibatkan banyak interaksi.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 7],
            ['teks' => 'Saya sering menjadi orang yang menjelaskan sesuatu kepada teman atau kelompok.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 8],
            ['teks' => 'Saya suka menyampaikan gagasan yang dapat meningkatkan kualitas suatu kegiatan.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 9],
            ['teks' => 'Saya merasa nyaman ketika menjadi penghubung antara berbagai pihak.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 10],
            ['teks' => 'Saya tertarik memahami apa yang memengaruhi pilihan seseorang.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 11],
            ['teks' => 'Saya mudah membangun percakapan dengan orang baru.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 12],
            ['teks' => 'Saya senang mengembangkan ide-ide yang dapat menarik perhatian orang lain.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 13],
            ['teks' => 'Saya tidak keberatan berbicara di depan kelompok atau forum.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 14],
            ['teks' => 'Saya tertarik mempelajari faktor-faktor yang memengaruhi perilaku seseorang.', 'jenis' => 'minat', 'konsentrasi' => 'pemasaran', 'urutan' => 15],

            // === TES MINAT - KEUANGAN (15 soal) ===
            ['teks' => 'Saya sering memeriksa kembali pekerjaan sebelum menyerahkannya.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 1],
            ['teks' => 'Saya lebih menyukai pekerjaan yang memiliki tahapan yang jelas.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 2],
            ['teks' => 'Saya senang mencari hubungan antara berbagai informasi yang saya peroleh.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 3],
            ['teks' => 'Saya terbiasa memperhatikan hal-hal kecil yang sering diabaikan orang lain.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 4],
            ['teks' => 'Saya lebih percaya pada keputusan yang didasarkan pada pertimbangan yang matang.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 5],
            ['teks' => 'Saya senang mengorganisasi langkah-langkah sebelum memulai suatu pekerjaan.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 6],
            ['teks' => 'Saya senang menganalisis berbagai kemungkinan sebelum mengambil keputusan.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 7],
            ['teks' => 'Saya menikmati pekerjaan yang membutuhkan konsentrasi tinggi.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 8],
            ['teks' => 'Saya tertarik mencari penyebab di balik suatu peristiwa atau kejadian.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 9],
            ['teks' => 'Saya lebih suka bekerja berdasarkan data atau fakta yang tersedia.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 10],
            ['teks' => 'Saya sering membuat daftar atau rencana sebelum melaksanakan suatu kegiatan.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 11],
            ['teks' => 'Saya menikmati tugas yang membutuhkan ketelitian dalam pengerjaan.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 12],
            ['teks' => 'Saya cenderung mempertimbangkan risiko sebelum bertindak.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 13],
            ['teks' => 'Saya senang mengolah informasi untuk menemukan pola tertentu.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 14],
            ['teks' => 'Saya sering mempertimbangkan berbagai alternatif sebelum menentukan pilihan.', 'jenis' => 'minat', 'konsentrasi' => 'keuangan', 'urutan' => 15],

            // === TES MINAT - SDM (15 soal) ===
            ['teks' => 'Saya senang mempelajari hal-hal baru dari pengalaman orang lain.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 1],
            ['teks' => 'Saya menikmati bekerja dalam kelompok untuk menyelesaikan suatu tugas.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 2],
            ['teks' => 'Saya merasa puas ketika dapat membantu orang lain mengatasi kesulitannya.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 3],
            ['teks' => 'Saya tertarik memahami bagaimana suatu kelompok dapat bekerja lebih efektif.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 4],
            ['teks' => 'Saya berusaha memahami sudut pandang orang lain sebelum memberikan penilaian.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 5],
            ['teks' => 'Saya mudah menyesuaikan diri dengan berbagai karakter orang.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 6],
            ['teks' => 'Saya merasa penting untuk menjaga hubungan baik dalam lingkungan kerja atau organisasi.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 7],
            ['teks' => 'Saya menikmati membantu anggota kelompok agar dapat berkontribusi secara optimal.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 8],
            ['teks' => 'Saya berusaha mencari jalan tengah ketika terjadi perbedaan pendapat.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 9],
            ['teks' => 'Saya senang mencari cara yang lebih efektif untuk mencapai tujuan.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 10],
            ['teks' => 'Saya menikmati kegiatan yang melibatkan kerja sama dan koordinasi.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 11],
            ['teks' => 'Saya merasa penting untuk memahami kebutuhan dan harapan orang lain.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 12],
            ['teks' => 'Saya lebih nyaman jika pekerjaan dilakukan secara teratur dan sistematis.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 13],
            ['teks' => 'Saya senang melihat orang lain berkembang setelah mendapatkan arahan atau dukungan.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 14],
            ['teks' => 'Saya menikmati kegiatan yang memungkinkan saya berbagi gagasan kepada banyak orang.', 'jenis' => 'minat', 'konsentrasi' => 'sdm', 'urutan' => 15],

            // === TES BAKAT - PEMASARAN (10 soal) ===
            ['teks' => 'Saya mampu menjelaskan suatu ide dengan bahasa yang mudah dipahami orang lain.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 1],
            ['teks' => 'Saya dapat memulai percakapan dengan orang yang baru dikenal tanpa merasa canggung.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 2],
            ['teks' => 'Saya sering menemukan cara yang berbeda untuk menyelesaikan suatu masalah.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 3],
            ['teks' => 'Saya mampu meyakinkan orang lain terhadap suatu gagasan yang saya sampaikan.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 4],
            ['teks' => 'Saya percaya diri berbicara di depan kelompok.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 5],
            ['teks' => 'Saya mampu menyesuaikan cara berkomunikasi dengan lawan bicara yang berbeda.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 6],
            ['teks' => 'Saya sering menghasilkan ide-ide yang kreatif dalam berbagai situasi.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 7],
            ['teks' => 'Saya dapat membangun hubungan baik dengan banyak orang.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 8],
            ['teks' => 'Saya mampu menarik perhatian orang lain saat menyampaikan informasi.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 9],
            ['teks' => 'Saya mudah memahami apa yang menarik perhatian seseorang terhadap suatu hal.', 'jenis' => 'bakat', 'konsentrasi' => 'pemasaran', 'urutan' => 10],

            // === TES BAKAT - KEUANGAN (10 soal) ===
            ['teks' => 'Saya mampu memahami informasi yang disajikan dalam bentuk angka atau tabel.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 1],
            ['teks' => 'Saya dapat menemukan kesalahan kecil dalam suatu pekerjaan.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 2],
            ['teks' => 'Saya senang menganalisis suatu masalah sebelum mengambil keputusan.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 3],
            ['teks' => 'Saya mampu menghubungkan berbagai informasi untuk memperoleh kesimpulan.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 4],
            ['teks' => 'Saya terbiasa bekerja secara sistematis dan teratur.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 5],
            ['teks' => 'Saya mampu mempertimbangkan risiko sebelum menentukan pilihan.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 6],
            ['teks' => 'Saya mudah memahami hubungan sebab-akibat dari suatu kejadian.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 7],
            ['teks' => 'Saya dapat berkonsentrasi dalam waktu yang cukup lama pada suatu pekerjaan.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 8],
            ['teks' => 'Saya terbiasa memeriksa kembali pekerjaan yang telah selesai.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 9],
            ['teks' => 'Saya mampu membuat keputusan berdasarkan fakta yang tersedia.', 'jenis' => 'bakat', 'konsentrasi' => 'keuangan', 'urutan' => 10],

            // === TES BAKAT - SDM (10 soal) ===
            ['teks' => 'Saya mampu memahami perasaan orang lain meskipun tidak diungkapkan secara langsung.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 1],
            ['teks' => 'Saya mudah bekerja sama dengan berbagai tipe orang.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 2],
            ['teks' => 'Saya dapat membantu orang lain menemukan solusi atas masalah yang dihadapinya.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 3],
            ['teks' => 'Saya mampu menjaga hubungan baik meskipun terdapat perbedaan pendapat.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 4],
            ['teks' => 'Saya dapat menjadi pendengar yang baik ketika orang lain berbicara.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 5],
            ['teks' => 'Saya mampu memahami kebutuhan dan harapan orang lain.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 6],
            ['teks' => 'Saya sering membantu menciptakan suasana kerja sama yang baik dalam kelompok.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 7],
            ['teks' => 'Saya mampu menenangkan situasi ketika terjadi ketegangan dalam kelompok.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 8],
            ['teks' => 'Saya senang membantu orang lain mengembangkan kemampuannya.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 9],
            ['teks' => 'Saya dapat menjadi penengah ketika terjadi konflik antarindividu.', 'jenis' => 'bakat', 'konsentrasi' => 'sdm', 'urutan' => 10],
        ];

        foreach ($soal as $s) {
            \App\Models\Soal::create(array_merge($s, ['aktif' => true]));
        }
    }
}
