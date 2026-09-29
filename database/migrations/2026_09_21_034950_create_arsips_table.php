<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_arsip'); // Jenis Arsip (Vital, Terjaga, Inaktif)
            $table->string('judul_berkas');   // Parent Folder

            // Kolom Anak (Child) - Boleh Nullable karena Parent tidak punya ini awalnya
            $table->string('judul_isi')->nullable();
            $table->string('validator')->nullable();
            $table->string('pencipta')->nullable();
            $table->string('asal_berkas')->nullable();
            $table->string('tahun')->nullable();
            $table->string('retensi_aktif')->nullable();
            $table->string('retensi_inaktif')->nullable();
            $table->string('lokasi_ruangan')->nullable();
            $table->string('tempat')->nullable();
            $table->string('folder_boks')->nullable();
            $table->string('tingkat_klasifikasi')->nullable();
            $table->string('jenis_klasifikasi')->nullable();
            $table->string('klasifikasi_big')->nullable();
            $table->string('klasifikasi_bakosurtanal')->nullable();
            $table->string('jenis_fisik')->nullable();
            $table->string('sifat')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('file_arsip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
