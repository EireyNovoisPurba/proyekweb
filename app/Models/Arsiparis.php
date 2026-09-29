<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsiparis extends Model
{
    // Mengizinkan kolom-kolom ini diisi melalui form
    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'unit_kerja',
        'pangkat',
        'golongan',
        'pendidikan',
        'tempat_tanggal_lahir'
    ];
}