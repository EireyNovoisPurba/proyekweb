<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipTerjaga extends Model
{
    protected $table = 'arsip_terjagas';

    protected $fillable = [
        // Induk Berkas
        'klasifikasi_arsip',
        'no_berkas',
        'uraian_informasi_berkas',
        'pencipta',
        'asal_berkas',
        'kurun_waktu',
        'jumlah_isi_berkas',
        'validator',

        // Detail Item
        'no_item_arsip',
        'uraian_informasi_arsip',
        'tanggal',
        'jenis_fisik',
        'tingkat_perkembangan',
        'kondisi',

        // Lokasi Simpan
        'lokasi_ruangan',
        'lokasi_rak',
        'lokasi_boks',

        // Retensi
        'retensi_aktif',
        'retensi_inaktif',
        'uraian_retensi_aktif',
        'uraian_retensi_inaktif',

        // Keamanan & Hak Akses
        'klasifikasi_keamanan',
        'kategori_arsip',
        'tingkat_akses_publik',
        'hak_akses',
        'dasar_pertimbangan',

        // Keterangan Lain
        'metode_penyimpanan',
        'keterangan',
    ];
}
