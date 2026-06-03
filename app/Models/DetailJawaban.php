<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJawaban extends Model
{
    protected $table = 'detail_jawaban';

    protected $fillable = ['hasil_tes_id', 'soal_id', 'nilai'];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function hasilTes()
    {
        return $this->belongsTo(HasilTes::class);
    }
}
