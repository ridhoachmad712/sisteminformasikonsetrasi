<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function edit(Mahasiswa $mahasiswum)
    {
        $prestasi = $mahasiswum->prestasi_relevan ?? ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];
        return view('admin.prestasi.edit', [
            'mahasiswa' => $mahasiswum,
            'prestasi'  => $prestasi,
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswum)
    {
        $request->validate([
            'prestasi.pemasaran' => 'required|integer|min:0|max:15',
            'prestasi.keuangan'  => 'required|integer|min:0|max:15',
            'prestasi.sdm'       => 'required|integer|min:0|max:15',
            'catatan'            => 'nullable|string|max:500',
        ], [
            'prestasi.*.max' => 'Skor prestasi maksimal 15.',
            'prestasi.*.min' => 'Skor prestasi minimal 0.',
        ]);

        $mahasiswum->update([
            'prestasi_relevan' => [
                'pemasaran' => (int) $request->input('prestasi.pemasaran'),
                'keuangan'  => (int) $request->input('prestasi.keuangan'),
                'sdm'       => (int) $request->input('prestasi.sdm'),
            ],
            'catatan_prestasi' => $request->catatan,
        ]);

        return redirect()->route('admin.rekap.show', $mahasiswum)
            ->with('success', 'Skor prestasi relevan berhasil disimpan.');
    }

    // ─────────────────────────────────────────────
    //  Import Excel/CSV Prestasi Massal
    // ─────────────────────────────────────────────

    public function importForm()
    {
        return view('admin.prestasi.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ], [
            'file.mimes' => 'File harus CSV atau Excel (.csv, .xlsx, .xls).',
            'file.max'   => 'Maksimal 5MB.',
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        // Parse file → array of rows
        $rows = $ext === 'csv' || $ext === 'txt'
            ? $this->parseCsv($file->getRealPath())
            : $this->parseXlsx($file->getRealPath());

        if (!$rows) {
            return back()->with('error', 'File kosong atau format tidak valid.');
        }

        $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);
        $dataRows = array_slice($rows, 1);

        // Cari index kolom
        $col = [
            'nim'       => $this->findCol($header, ['nim']),
            'pemasaran' => $this->findCol($header, ['pemasaran', 'mp', 'manajemen pemasaran']),
            'keuangan'  => $this->findCol($header, ['keuangan', 'mk', 'manajemen keuangan']),
            'sdm'       => $this->findCol($header, ['sdm', 'manajemen sdm', 'manajemen sumber daya manusia']),
            'catatan'   => $this->findCol($header, ['catatan', 'keterangan', 'note', 'notes']),
        ];

        if ($col['nim'] === null) {
            return back()->with('error', 'Kolom NIM tidak ditemukan di file.');
        }
        if ($col['pemasaran'] === null && $col['keuangan'] === null && $col['sdm'] === null) {
            return back()->with('error', 'Minimal salah satu kolom Pemasaran / Keuangan / SDM harus ada.');
        }

        $berhasil = 0;
        $gagal    = [];
        $baris    = 1;

        foreach ($dataRows as $row) {
            $baris++;
            $nim = trim($row[$col['nim']] ?? '');
            if (!$nim) continue;

            $mhs = Mahasiswa::where('nim', $nim)->first();
            if (!$mhs) {
                $gagal[] = "Baris {$baris}: NIM {$nim} tidak terdaftar";
                continue;
            }

            // Ambil skor (clamp 0-15)
            $skor = [];
            foreach (['pemasaran', 'keuangan', 'sdm'] as $k) {
                $idx = $col[$k];
                $val = $idx !== null ? (int) trim($row[$idx] ?? 0) : 0;
                $skor[$k] = max(0, min(15, $val));
            }

            $update = ['prestasi_relevan' => $skor];
            if ($col['catatan'] !== null) {
                $catatan = trim($row[$col['catatan']] ?? '');
                if ($catatan) $update['catatan_prestasi'] = $catatan;
            }

            $mhs->update($update);
            $berhasil++;
        }

        $msg = "{$berhasil} mahasiswa berhasil diupdate prestasinya.";
        if (count($gagal)) {
            $msg .= ' ' . count($gagal) . ' baris gagal: ' . implode('; ', array_slice($gagal, 0, 5));
            if (count($gagal) > 5) $msg .= '...';
        }

        return redirect()->route('admin.rekap.index')
            ->with($berhasil > 0 ? 'success' : 'error', $msg);
    }

    public function template()
    {
        $csv = "nim,pemasaran,keuangan,sdm,catatan\n";
        $csv .= "240903500001,10,0,5,Juara 2 Marketing Competition Nasional\n";
        $csv .= "240903500002,0,12,0,Sertifikat Pelatihan Keuangan Lanjutan\n";
        $csv .= "240903500003,5,5,5,Anggota HMJ Manajemen\n";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-prestasi.csv"',
        ]);
    }

    private function findCol(array $header, array $aliases): ?int
    {
        foreach ($aliases as $alias) {
            $idx = array_search($alias, $header);
            if ($idx !== false) return $idx;
        }
        return null;
    }

    private function parseCsv(string $path): array
    {
        $rows = [];
        $h = fopen($path, 'r');
        while (($r = fgetcsv($h, 5000, ',')) !== false) {
            $rows[] = $r;
        }
        fclose($h);
        return $rows;
    }

    private function parseXlsx(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) return [];

        // Baca shared strings
        $sharedStrings = [];
        if ($ss = $zip->getFromName('xl/sharedStrings.xml')) {
            $xml = simplexml_load_string($ss);
            foreach ($xml->si as $si) {
                $text = '';
                if (isset($si->t)) $text = (string) $si->t;
                else foreach ($si->r as $r) $text .= (string) $r->t;
                $sharedStrings[] = $text;
            }
        }

        // Baca sheet1
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        if (!$sheetXml) return [];

        $xml  = simplexml_load_string($sheetXml);
        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $r = [];
            foreach ($row->c as $c) {
                $ref  = (string) $c['r'];
                $col  = $this->colLetterToIndex(preg_replace('/\d/', '', $ref));
                $type = (string) $c['t'];
                $val  = (string) $c->v;
                if ($type === 's') {
                    $val = $sharedStrings[(int) $val] ?? '';
                } elseif ($type === 'inlineStr') {
                    $val = (string) $c->is->t;
                }
                $r[$col] = $val;
            }
            // Normalize keys (fill gaps)
            if ($r) {
                $max = max(array_keys($r));
                $norm = [];
                for ($i = 0; $i <= $max; $i++) $norm[$i] = $r[$i] ?? '';
                $rows[] = $norm;
            }
        }
        return $rows;
    }

    private function colLetterToIndex(string $letters): int
    {
        $i = 0;
        for ($j = 0, $len = strlen($letters); $j < $len; $j++) {
            $i = $i * 26 + (ord(strtoupper($letters[$j])) - 64);
        }
        return $i - 1;
    }
}
