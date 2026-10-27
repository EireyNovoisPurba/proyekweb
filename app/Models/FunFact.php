<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunFact extends Model
{
    protected $fillable = [
        'judul', 'gambar', 'tag_badge', 'deskripsi_singkat',
        'poin_1_judul', 'poin_1_isi', 'poin_2_judul', 'poin_2_isi',
        'poin_3_judul', 'poin_3_isi', 'kesimpulan', 'is_active'
    ];
}