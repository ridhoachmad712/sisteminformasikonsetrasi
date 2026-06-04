<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Soal;
use App\Models\HasilTes;
use App\Models\DetailJawaban;
use App\Models\JadwalTes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TesController extends Controller
{
    const MIN_SOAL_MINAT = 5;
    const MIN_SOAL_BAKAT = 3;

    // ─────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────

    private function getMahasiswa(): Mahasiswa
    {
        return Mahasiswa::findOrFail(session('mahasiswa_id'));
    }

    private function validasiSoalCukup(string $jenis): bool
    {
        $min = $jenis === 'minat' ? self::MIN_SOAL_MINAT : self::MIN_SOAL_BAKAT;

        return Cache::remember("validasi_soal_{$jenis}", 600, function () use ($jenis, $min) {
            foreach (['pemasaran', 'keuangan', 'sdm'] as $k) {
                if (Soal::where('aktif', true)->where('jenis', $jenis)->where('konsentrasi', $k)->count() < $min) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Cek apakah jenis tes tertentu bisa diakses sekarang.
     * Return: null (boleh lanjut) | array ['status'=>..., 'jadwal'=>...]
     */
    private function cekJadwal(Mahasiswa $mahasiswa, string $jenis): ?array
    {
        $jadwal = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, $jenis);

        if (!$jadwal) {
            return ['status' => 'belum_dijadwalkan', 'jadwal' => null, 'jenis' => $jenis];
        }
        if ($jadwal->belum_mulai) {
            return ['status' => 'belum_mulai', 'jadwal' => $jadwal, 'jenis' => $jenis];
        }
        if ($jadwal->sudah_berakhir) {
            return ['status' => 'sudah_berakhir', 'jadwal' => $jadwal, 'jenis' => $jenis];
        }
        return null; // OK
    }

    /**
     * Validasi server-side saat submit: pastikan jadwal masih berlangsung.
     * Return null jika OK, atau string pesan error jika waktu tidak valid.
     */
    private function waktuHabis(Mahasiswa $mahasiswa, string $jenis): ?string
    {
        $jadwal = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, $jenis);
        $label  = $jenis === 'minat' ? 'Tes Minat' : 'Tes Bakat';

        if (!$jadwal || !$jadwal->aktif) {
            return "{$label} tidak tersedia. Jadwal tidak ditemukan.";
        }
        if ($jadwal->belum_mulai) {
            return "{$label} belum dimulai.";
        }
        // Beri grace period 5 menit untuk submit yang dipicu timer client-side
        if ($jadwal->tanggal_selesai->addMinutes(5)->isPast()) {
            return "Waktu {$label} telah berakhir. Jawaban tidak dapat dikirim.";
        }
        return null;
    }

    // ─────────────────────────────────────────────────────────────
    //  Landing — status kedua tes
    // ─────────────────────────────────────────────────────────────

    private function cekPrasyarat(Mahasiswa $mahasiswa): ?string
    {
        if (!$mahasiswa->sudah_input_nilai) {
            return 'Anda harus menyelesaikan input data Akademik sebelum mengikuti tes.';
        }
        if (!$mahasiswa->sudah_pilih_konsentrasi) {
            return 'Anda harus memilih Pilihan Konsentrasi sebelum mengikuti tes.';
        }
        return null;
    }

    public function index()
    {
        $mahasiswa = $this->getMahasiswa();

        // Jika kedua tes selesai, langsung ke hasil
        if ($mahasiswa->sudah_tes_minat && $mahasiswa->sudah_tes_bakat) {
            $hasil = $mahasiswa->hasilTesTerakhir;
            if ($hasil && $hasil->lengkap) {
                return view('tes.hasil', compact('mahasiswa', 'hasil'));
            }
        }

        // Cek prasyarat
        if ($pesan = $this->cekPrasyarat($mahasiswa)) {
            return redirect()->route('beranda')->with('error', $pesan);
        }

        // Cek jadwal untuk masing-masing jenis
        $jadwalMinat = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'minat');
        $jadwalBakat = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'bakat');

        return view('tes.landing', compact('mahasiswa', 'jadwalMinat', 'jadwalBakat'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Tes Minat
    // ─────────────────────────────────────────────────────────────

    public function minat()
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa->sudah_tes_minat) {
            return redirect()->route('tes.index')->with('info', 'Tes Minat sudah Anda selesaikan.');
        }

        if ($pesan = $this->cekPrasyarat($mahasiswa)) {
            return redirect()->route('beranda')->with('error', $pesan);
        }

        $blokir = $this->cekJadwal($mahasiswa, 'minat');
        if ($blokir) {
            return view('tes.terkunci', array_merge($blokir, ['mahasiswa' => $mahasiswa]));
        }

        if (!$this->validasiSoalCukup('minat')) {
            return view('tes.tidak-tersedia', ['mahasiswa' => $mahasiswa, 'jenis' => 'minat']);
        }

        $jadwal         = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'minat');
        [$soal, $draft] = $this->getSoalDanDraft($mahasiswa, 'minat');

        return view('tes.soal', compact('mahasiswa', 'soal', 'draft', 'jadwal') + ['jenis' => 'minat']);
    }

    public function submitMinat(Request $request)
    {
        $mahasiswa = $this->getMahasiswa();
        if ($mahasiswa->sudah_tes_minat) return redirect()->route('tes.index');

        // Validasi waktu: tolak jika jadwal sudah berakhir / belum mulai
        if ($pesan = $this->waktuHabis($mahasiswa, 'minat')) {
            return redirect()->route('tes.index')->with('error', $pesan);
        }

        $this->prosesSubmit($mahasiswa, $request, 'minat');

        return redirect()->route('tes.index')->with('success', 'Tes Minat berhasil diselesaikan!');
    }

    // ─────────────────────────────────────────────────────────────
    //  Tes Bakat
    // ─────────────────────────────────────────────────────────────

    public function bakat()
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa->sudah_tes_bakat) {
            return redirect()->route('tes.index')->with('info', 'Tes Bakat sudah Anda selesaikan.');
        }

        if ($pesan = $this->cekPrasyarat($mahasiswa)) {
            return redirect()->route('beranda')->with('error', $pesan);
        }

        $blokir = $this->cekJadwal($mahasiswa, 'bakat');
        if ($blokir) {
            return view('tes.terkunci', array_merge($blokir, ['mahasiswa' => $mahasiswa]));
        }

        if (!$this->validasiSoalCukup('bakat')) {
            return view('tes.tidak-tersedia', ['mahasiswa' => $mahasiswa, 'jenis' => 'bakat']);
        }

        $jadwal         = JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'bakat');
        [$soal, $draft] = $this->getSoalDanDraft($mahasiswa, 'bakat');

        return view('tes.soal', compact('mahasiswa', 'soal', 'draft', 'jadwal') + ['jenis' => 'bakat']);
    }

    public function submitBakat(Request $request)
    {
        $mahasiswa = $this->getMahasiswa();
        if ($mahasiswa->sudah_tes_bakat) return redirect()->route('tes.index');

        // Validasi waktu: tolak jika jadwal sudah berakhir / belum mulai
        if ($pesan = $this->waktuHabis($mahasiswa, 'bakat')) {
            return redirect()->route('tes.index')->with('error', $pesan);
        }

        $this->prosesSubmit($mahasiswa, $request, 'bakat');

        return redirect()->route('tes.index')->with('success', 'Tes Bakat berhasil diselesaikan!');
    }

    // ─────────────────────────────────────────────────────────────
    //  Auto-save Draft (AJAX)
    // ─────────────────────────────────────────────────────────────

    public function saveDraft(Request $request)
    {
        $mahasiswaId = session('mahasiswa_id');
        if (!$mahasiswaId) return response()->json(['ok' => false], 401);

        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $jenis     = $request->input('jenis');

        if (!in_array($jenis, ['minat', 'bakat'])) {
            return response()->json(['ok' => false, 'error' => 'jenis tidak valid']);
        }

        $sudahKey = "sudah_tes_{$jenis}";
        if ($mahasiswa->$sudahKey) return response()->json(['ok' => false, 'reason' => 'already_submitted']);

        $jawaban = $request->input('jawaban', []);
        $cleaned = array_filter(
            array_map(fn($v) => (int) $v, $jawaban),
            fn($v) => $v >= 1 && $v <= 5
        );

        $draftKey = "draft_{$jenis}";
        $mahasiswa->update([
            $draftKey          => $cleaned,
            'last_activity_at' => now(),
            'tes_aktif'        => $jenis,
        ]);

        return response()->json(['ok' => true, 'saved' => count($cleaned), 'jenis' => $jenis]);
    }

    // ─────────────────────────────────────────────────────────────
    //  Halaman Hasil
    // ─────────────────────────────────────────────────────────────

    public function hasil()
    {
        $mahasiswa = $this->getMahasiswa();
        $hasil     = $mahasiswa->hasilTesTerakhir;

        if (!$hasil || !$hasil->lengkap) {
            return redirect()->route('tes.index');
        }

        return view('tes.hasil', compact('mahasiswa', 'hasil'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * Ambil semua soal aktif per jenis dari cache (TTL 10 menit).
     * Disimpan sebagai array plain (bukan Eloquent object) agar aman di semua cache driver.
     */
    public static function getCachedSoal(string $jenis): \Illuminate\Support\Collection
    {
        $data = Cache::remember("soal_aktif_{$jenis}", 600, function () use ($jenis) {
            // Simpan sebagai array of attributes, bukan Eloquent model
            return Soal::where('aktif', true)->where('jenis', $jenis)->get()->toArray();
        });

        // Konversi array kembali ke Collection of Soal model objects
        return collect($data)->map(function ($attrs) {
            $soal = new Soal();
            $soal->forceFill($attrs);
            $soal->exists = true;
            return $soal;
        });
    }

    private function getSoalDanDraft(Mahasiswa $mahasiswa, string $jenis): array
    {
        $urutanKey = "urutan_{$jenis}";
        $draftKey  = "draft_{$jenis}";

        // Semua soal diambil dari cache — tidak query DB setiap request
        $semuaSoal = self::getCachedSoal($jenis);

        if ($mahasiswa->$urutanKey && count($mahasiswa->$urutanKey) > 0) {
            $ids   = $mahasiswa->$urutanKey;
            $map   = $semuaSoal->keyBy('id');
            $soal  = collect($ids)->map(fn($id) => $map->get($id))->filter()->values();
            $draft = $mahasiswa->$draftKey ?? [];
        } else {
            $soal  = $semuaSoal->shuffle();
            $mahasiswa->update([$urutanKey => $soal->pluck('id')->toArray(), $draftKey => []]);
            $draft = [];
        }

        // Track aktivitas: tandai mahasiswa sedang mengerjakan tes ini
        $mahasiswa->update([
            'last_activity_at' => now(),
            'tes_aktif'        => $jenis,
        ]);

        return [$soal, $draft];
    }

    /**
     * Auto-submit untuk mahasiswa yang waktunya habis tapi belum submit.
     * Dipanggil dari Artisan command AutoSubmitExpiredTests.
     */
    public static function autoSubmitMahasiswa(Mahasiswa $mahasiswa, string $jenis): void
    {
        $sudahKey = "sudah_tes_{$jenis}";
        if ($mahasiswa->$sudahKey) return;

        $draftKey = "draft_{$jenis}";
        $draft    = $mahasiswa->$draftKey ?? [];

        // Buat fake request dari draft yang tersimpan
        $fakeRequest = new \Illuminate\Http\Request();
        $fakeRequest->merge(['jawaban' => $draft]);

        (new self())->prosesSubmitDenganJawaban($mahasiswa, $draft, $jenis);
    }

    private function prosesSubmit(Mahasiswa $mahasiswa, Request $request, string $jenis): HasilTes
    {
        $jawaban  = $request->input('jawaban', []);
        return $this->prosesSubmitDenganJawaban($mahasiswa, $jawaban, $jenis);
    }

    private function prosesSubmitDenganJawaban(Mahasiswa $mahasiswa, array $jawaban, string $jenis): HasilTes
    {
        $soalList = self::getCachedSoal($jenis)->keyBy('id');

        // Akumulasi skor per konsentrasi
        $skor = ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];

        foreach ($jawaban as $soalId => $nilai) {
            $soal = $soalList->get($soalId);
            if (!$soal) continue;
            $nilai = (int) $nilai;
            if ($nilai < 1 || $nilai > 5) continue;
            $skor[$soal->konsentrasi] += $nilai;
        }

        return DB::transaction(function () use ($mahasiswa, $jawaban, $soalList, $skor, $jenis) {
            // Cari atau buat HasilTes untuk mahasiswa ini
            $hasil = HasilTes::firstOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'nilai_pemasaran' => 0, 'nilai_keuangan' => 0, 'nilai_sdm' => 0,
                    'rekomendasi' => 'pemasaran',
                    'sudah_minat' => false, 'sudah_bakat' => false, 'lengkap' => false,
                ]
            );

            // Update skor untuk jenis ini
            $prefix = "skor_{$jenis}_";
            $hasil->update([
                "{$prefix}pemasaran" => $skor['pemasaran'],
                "{$prefix}keuangan"  => $skor['keuangan'],
                "{$prefix}sdm"       => $skor['sdm'],
                "sudah_{$jenis}"     => true,
            ]);

            // Batch insert detail jawaban — 1 query, bukan N query
            $now  = now();
            $rows = [];
            foreach ($jawaban as $soalId => $nilai) {
                if (!$soalList->has($soalId)) continue;
                $rows[] = [
                    'hasil_tes_id' => $hasil->id,
                    'soal_id'      => $soalId,
                    'nilai'        => (int) $nilai,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
            if (!empty($rows)) {
                DetailJawaban::insert($rows);
            }

            // Update status mahasiswa
            $urutanKey = "urutan_{$jenis}";
            $draftKey  = "draft_{$jenis}";
            $sudahKey  = "sudah_tes_{$jenis}";
            $mahasiswa->update([
                $sudahKey          => true,
                $urutanKey         => null,
                $draftKey          => null,
                'tes_aktif'        => null,
                'last_activity_at' => $now,
            ]);

            // Jika kedua tes selesai: hitung nilai akhir
            $mahasiswa->refresh();
            if ($mahasiswa->sudah_tes_minat && $mahasiswa->sudah_tes_bakat) {
                $hasil->refresh();
                $hasil->hitungNilaiAkhir();
                $mahasiswa->update(['sudah_tes' => true]);
            }

            return $hasil;
        });
    }
}
