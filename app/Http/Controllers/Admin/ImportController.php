<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('admin.mahasiswa.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.mimes' => 'File harus berformat CSV (.csv).',
            'file.max'   => 'Ukuran file maksimal 2MB.',
        ]);

        $file     = $request->file('file');
        $handle   = fopen($file->getRealPath(), 'r');
        $header   = null;
        $berhasil = 0;
        $gagal    = [];
        $baris    = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $baris++;

            // Skip header baris pertama
            if ($baris === 1) {
                $header = array_map('strtolower', array_map('trim', $row));
                continue;
            }

            if (count($row) < 3) {
                $gagal[] = "Baris {$baris}: kolom tidak cukup";
                continue;
            }

            // Mapping fleksibel: nim, nama, angkatan, email (opsional), password (opsional)
            $nim      = trim($row[0] ?? '');
            $nama     = trim($row[1] ?? '');
            $angkatan = trim($row[2] ?? '');
            $email    = trim($row[3] ?? '') ?: null;
            $password = trim($row[4] ?? '') ?: $nim; // default password = NIM

            if (!$nim || !$nama || !$angkatan) {
                $gagal[] = "Baris {$baris}: NIM, Nama, atau Angkatan kosong";
                continue;
            }

            if (\App\Models\Mahasiswa::where('nim', $nim)->exists()) {
                $gagal[] = "Baris {$baris}: NIM {$nim} sudah terdaftar";
                continue;
            }

            try {
                \App\Models\Mahasiswa::create([
                    'nim'      => $nim,
                    'nama'     => $nama,
                    'angkatan' => $angkatan,
                    'email'    => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                ]);
                $berhasil++;
            } catch (\Exception $e) {
                $gagal[] = "Baris {$baris}: {$e->getMessage()}";
            }
        }

        fclose($handle);

        $msg = "{$berhasil} mahasiswa berhasil diimpor.";
        if (count($gagal)) {
            $msg .= ' ' . count($gagal) . ' baris gagal: ' . implode('; ', array_slice($gagal, 0, 5));
            if (count($gagal) > 5) $msg .= '...';
        }

        return redirect()->route('admin.mahasiswa.index')
            ->with($berhasil > 0 ? 'success' : 'error', $msg);
    }

    public function downloadTemplate()
    {
        $csv = "nim,nama,angkatan,email,password\n";
        $csv .= "2023001,Budi Santoso,2023,budi@email.com,budi2023\n";
        $csv .= "2023002,Siti Rahayu,2023,siti@email.com,\n";
        $csv .= "2023003,Ahmad Fauzi,2023,,\n";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-mahasiswa.csv"',
        ]);
    }
}
