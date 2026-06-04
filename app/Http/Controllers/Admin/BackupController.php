<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    // ── Download (Export SQL) ─────────────────────────────────────
    public function download()
    {
        $dbName   = config('database.connections.mysql.database');
        $now      = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$now}.sql";
        $sql      = $this->generateSql();

        return response($sql, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Content-Length'      => strlen($sql),
        ]);
    }

    // ── Upload (Restore SQL) ──────────────────────────────────────
    public function upload(Request $request)
    {
        $request->validate([
            'sql_file' => ['required', 'file', 'max:51200'],
        ], [
            'sql_file.required' => 'File SQL wajib diunggah.',
            'sql_file.max'      => 'Ukuran file maksimal 50MB.',
        ]);

        $ext = strtolower($request->file('sql_file')->getClientOriginalExtension());
        if (!in_array($ext, ['sql', 'txt'])) {
            return back()->with('error', 'File harus berekstensi .sql atau .txt.');
        }

        $content = file_get_contents($request->file('sql_file')->getRealPath());

        if (empty(trim($content))) {
            return back()->with('error', 'File SQL kosong.');
        }

        try {
            // Simpan info admin yang sedang login sebelum restore
            $adminId    = Auth::id();
            $adminEmail = Auth::user()->email ?? null;

            DB::unprepared($content);

            // Setelah restore, session mungkin tidak valid — login ulang otomatis
            Auth::logout();
            session()->flush();

            return redirect()->route('login.admin')
                ->with('success', 'Database berhasil dipulihkan. Silakan login kembali.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }

    // ── Generate SQL ──────────────────────────────────────────────
    private function generateSql(): string
    {
        $dbName   = config('database.connections.mysql.database');
        $output   = [];

        $output[] = "-- SI-KONSEN Database Backup";
        $output[] = "-- Generated: " . now()->toDateTimeString();
        $output[] = "-- Database : {$dbName}";
        $output[] = "";
        $output[] = "SET FOREIGN_KEY_CHECKS=0;";
        $output[] = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
        $output[] = "SET NAMES utf8mb4;";
        $output[] = "";

        $tables   = DB::select('SHOW TABLES');
        $tableKey = "Tables_in_{$dbName}";

        foreach ($tables as $tableRow) {
            $table = $tableRow->$tableKey;

            // CREATE TABLE
            $createRow = DB::select("SHOW CREATE TABLE `{$table}`");
            $createSql = $createRow[0]->{'Create Table'};

            $output[] = "-- --------------------------------------------------------";
            $output[] = "-- Table: `{$table}`";
            $output[] = "-- --------------------------------------------------------";
            $output[] = "DROP TABLE IF EXISTS `{$table}`;";
            $output[] = $createSql . ";";
            $output[] = "";

            // INSERT rows (batch 500)
            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) continue;

            $columns = array_map(fn($c) => "`{$c}`", array_keys((array) $rows->first()));
            $colList = implode(', ', $columns);
            $inserts = [];

            foreach ($rows as $row) {
                $values = array_map(function ($v) {
                    if ($v === null) return 'NULL';
                    return "'" . addslashes((string) $v) . "'";
                }, (array) $row);

                $inserts[] = '(' . implode(', ', $values) . ')';

                if (count($inserts) >= 500) {
                    $output[] = "INSERT INTO `{$table}` ({$colList}) VALUES";
                    $output[] = implode(",\n", $inserts) . ";";
                    $output[] = "";
                    $inserts  = [];
                }
            }

            if (!empty($inserts)) {
                $output[] = "INSERT INTO `{$table}` ({$colList}) VALUES";
                $output[] = implode(",\n", $inserts) . ";";
                $output[] = "";
            }
        }

        $output[] = "SET FOREIGN_KEY_CHECKS=1;";
        $output[] = "";

        return implode("\n", $output);
    }
}
