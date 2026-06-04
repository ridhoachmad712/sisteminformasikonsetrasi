<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DosenPa;

class DosenPaSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = [
            'Prof. Dr. Chalid Imran Musa, M. Si.',
            'Prof. Romansyah Sahabuddin, S.E., M. Si.',
            'Prof. Dr. Anwar Ramli, S.E., M. Si.',
            'Prof. Dr. Hj. Sitti Hasbiah, M. Si.',
            'Prof. M. Ikhwan Maulana Haeruddin, S.E., MHRMgt., Ph. D.',
            'Dr. Agung Widhi Kurniawan, S.T., M.M',
            'Dr. Anwar, S.E.,M.Si',
            'Dr. Burhanuddin, S. Sos., S.E., M.M.',
            'Dr. Muh. Ichwan Musa, S.E., M. Si.',
            'Dr. Uhud Darmawan Natsir, S.E., M.M.',
            'Nurman, S.E., M. Si.',
            'Dr. Hety Budiyanti, S.E., M. Ak.',
            'Dr. Zainal Ruma, S. Pd., M.M.',
            'Dr. Muhammad Ilham Wardhana Haeruddin, S.E., MMktMgt.',
            'Tenri Sayu Puspitaningsih Dipoatmodjo, S.E., M.M',
            'Andi Mustika Amin, S.E., M.Si',
            'Dr. Isma Azis Riu, S.P., MM.',
            'Annisa Paramaswary Aslam., SE., MSM',
            'Rezky Amalia Hamka, S.E., M.M.',
            'Dr.Hj. Nurul Fadilah Aswar, S.E., M.M.',
            'Ilma Wulansari Hasdiansa, M.M.',
            'Rahmat Riwayat Abadi, S.E., M.M.',
            'Khaidir Syahrul, S.E., M.B.A.',
            'Dr. Abdul Rahman, S.Pd., M.Si',
            'Dr. Azlan Azhari, S.E.,M.M',
            'Muhammad Rijal Alim Rahmat SE., MM',
            'A. Reski Almaida Dg Macenning, S. Pd., M. M.',
            'M. Yunasri Ridhoh, S.Pd., M.Pd.',
            'Achmad Ridha, S.M., M.M.',
            'Nulthazam Sarah, S.E., M.M.',
            'Sri Astuti Nasir, S.Pd., M.M',
            'Ridwan Andi Mattoliang, S.Pd., MPd.',
            'Rostina, S.Pd., M.Pd.',
            'Indri Iswardhani, S.E., M.M.',
            'Anang Setiawan, S.M.,M.M.',
            'Nur Fadilah Ayu andira, S.Pd., M.M',
            'Rahmat Burhamzah, S.Pd., M.M., Ak.',
            'Dr. Hasnidar, S.E., M.M',
            'Wiwin Riski Windarsari, S.E., M.M',
            'Ushwa Dwi Masrurah Arifin Bando, S. Pd. I., M. Pd',
            'Indah Lestari Anwar, S.E., M.SM.',
            'Fitriani Rahim, S.E.,M.M.',
            'Muhaidir Ikram, S.M., M.M',
            'Mufidatul Azmi, S.M., M.M',
            'Chairunnisa Miftahurrahmah Zenida Huzaen, S.E., M.SM',
            'Ahmad Rais Habib, S, S.E., M.M',
            'Nidrah, S.E., M.M',
            'Josafat Gracia Ginting, S.M., M.M',
            'Muh. Nurfadel Hamzah S.Tr.Pel., M.M',
            'Ade Vidya Eryanti, K, S.M., M.M',
            'A. Nurfiana Haz, S.E., M.M',
            'Rika Kurniawati, S.E., M.M',
            'Rifdan Rifaldy Abadi, S.E., M.M',
            'Stefani',
            'Nurhaedah, S.E., M. Si.',
            'Asniwati, S.E., M.Si.',
            'Muh. Yushar Mustafa, S.E., M.Sc.',
            'Widhi Nugraha Sumiharja Darmawinata, S.E., M.M',
            'Andi Dewi Angreyani, S.E., M.M',
            'Deddy Ibrahim Rauf, SE., MM',
            'Andi Aryani Hardiyanti, S.Si., M.M',
            'Fakhirah Husain, SE., MM',
            'Muh Al Fatah Arief Putra, S.E., M.M',
            'Dr. Kristina Parinsi, S.E., M.Pd',
            'Dr. Dwi Anugrah Lestari Musa, S.E., M.M',
            'Abd. Muis., SE. M.SM',
        ];

        foreach ($dosen as $nama) {
            DosenPa::firstOrCreate(['nama' => $nama], ['aktif' => true]);
        }
    }
}
