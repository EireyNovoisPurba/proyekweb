<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipVital extends Model
{
    protected $table = 'arsip_vitals';

    protected $fillable = [
        // Induk Berkas
        'jenis_arsip',
        'no_berkas',
        'uraian_informasi_berkas',
        'validator',
        'pencipta',
        'asal_berkas',
        'kurun_waktu',

        // Detail Item
        'no_item_arsip',
        'uraian_informasi_arsip',
        'tanggal',
        'jumlah_lembar',

        // Retensi
        'retensi_aktif',
        'retensi_inaktif',

        // Lokasi Simpan
        'lokasi_ruangan',
        'lokasi_lemari',
        'lokasi_boks',

        // Klasifikasi & Keamanan
        'tingkat_klasifikasi',
        'jenis_klasifikasi',
        'kode_klasifikasi',

        // Fisik & Sifat
        'jenis_fisik',
        'sifat',
        'keterangan',
    ];
}
