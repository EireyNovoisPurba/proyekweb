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
        Schema::create('arsip_vitals', function (Blueprint $table) {
            $table->id();

            // Data Induk (Uraian Informasi Berkas)
            $table->string('jenis_arsip')->nullable();
            $table->string('no_berkas')->nullable();
            $table->text('uraian_informasi_berkas')->nullable();
            $table->string('validator')->nullable();
            $table->string('pencipta')->nullable();
            $table->string('asal_berkas')->nullable();
            $table->string('kurun_waktu')->nullable();

            // Data Detail (Uraian Informasi Arsip / Isi Berkas)
            $table->string('no_item_arsip')->nullable();
            $table->text('uraian_informasi_arsip')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('jumlah_lembar')->nullable();

            // Retensi
            $table->string('retensi_aktif')->nullable();
            $table->string('retensi_inaktif')->nullable();

            // Lokasi Simpan
            $table->string('lokasi_ruangan')->nullable();
            $table->string('lokasi_lemari')->nullable();
            $table->string('lokasi_boks')->nullable();

            // Klasifikasi & Keamanan
            $table->string('tingkat_klasifikasi')->nullable();
            $table->string('jenis_klasifikasi')->nullable();
            $table->string('kode_klasifikasi')->nullable();

            // Fisik & Sifat
            $table->string('jenis_fisik')->nullable();
            $table->string('sifat')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_vitals');
    }
};
