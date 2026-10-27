<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arsip_terjagas', function (Blueprint $table) {
            $table->id();

            // Data Induk (Uraian Informasi Berkas)
            $table->string('klasifikasi_arsip')->nullable();
            $table->string('no_berkas')->nullable();
            $table->text('uraian_informasi_berkas')->nullable();
            $table->string('pencipta')->nullable();
            $table->string('asal_berkas')->nullable();
            $table->string('kurun_waktu')->nullable();
            $table->string('jumlah_isi_berkas')->nullable();
            $table->string('validator')->nullable();

            // Data Detail (Uraian Informasi Arsip / Isi Berkas)
            $table->string('no_item_arsip')->nullable();
            $table->text('uraian_informasi_arsip')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('jenis_fisik')->nullable();
            $table->string('tingkat_perkembangan')->nullable();
            $table->string('kondisi')->nullable();

            // Lokasi Simpan
            $table->string('lokasi_ruangan')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->string('lokasi_boks')->nullable();

            // Jadwal Retensi Arsip
            $table->string('retensi_aktif')->nullable();
            $table->string('retensi_inaktif')->nullable();
            $table->text('uraian_retensi_aktif')->nullable();
            $table->text('uraian_retensi_inaktif')->nullable();

            // Keamanan & Hak Akses
            $table->string('klasifikasi_keamanan')->nullable();
            $table->string('kategori_arsip')->nullable();
            $table->string('tingkat_akses_publik')->nullable();
            $table->string('hak_akses')->nullable();
            $table->text('dasar_pertimbangan')->nullable();

            // Keterangan Lain
            $table->string('metode_penyimpanan')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_terjagas');
    }
};
