<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ArsipInaktif extends Model
{
    protected $table = 'arsip_inaktifs';

    protected $fillable = [
        // Kolom Berkas (Induk)
        'asal_unit_pengolah',
        'no_berkas',
        'kode_klasifikasi',
        'uraian_informasi_berkas',
        'kurun_waktu',
        'jumlah_fisik_berkas',

        // Kolom Item Arsip (Detail)
        'no_item_arsip',
        'uraian_informasi_arsip',
        'tanggal',
        'jumlah_fisik_item',

        // Keterangan Fisik
        'jenis_fisik',
        'tingkat_perkembangan',
        'kondisi',

        // Lokasi Simpan
        'lokasi_boks',
        'lokasi_rak',
        'lokasi_ruangan',

        // Retensi
        'retensi_aktif',
        'retensi_inaktif',

        // Klasifikasi Keamanan & Akses
        'klasifikasi_keamanan',
        'kategori_arsip',
        'tingkat_akses_publik',
        'hak_akses',
        'dasar_pertimbangan',

        // Lain-lain
        'keterangan',
        'tanggal_pemindahan',
    ];
}