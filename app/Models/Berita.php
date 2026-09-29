<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    // Sesuaikan dengan nama kolom di file migration Anda sebelumnya
    protected $fillable = [
        'Judul',
        'Gambar',
        'Konten'
    ];
}
