<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = ['teks', 'jenis', 'konsentrasi', 'urutan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawaban::class);
    }
}
