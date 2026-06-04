<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenPa extends Model
{
    protected $table = 'dosen_pa';

    protected $fillable = ['nama', 'nip', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_pa_id');
    }
}
