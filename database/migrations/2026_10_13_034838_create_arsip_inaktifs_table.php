<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('arsip_inaktifs', function (Blueprint $table) {
        $table->id();
        // Kolom Induk (Uraian Informasi Berkas)
        $table->string('asal_unit_pengolah')->nullable();
        $table->string('no_berkas')->nullable();
        $table->string('kode_klasifikasi')->nullable();
        $table->text('uraian_informasi_berkas')->nullable();
        $table->string('kurun_waktu')->nullable();
        $table->string('jumlah_fisik_berkas')->nullable();

        // Kolom Anak (Uraian Informasi Arsip)
        $table->string('no_item_arsip')->nullable();
        $table->text('uraian_informasi_arsip')->nullable();
        $table->string('tanggal')->nullable();
        $table->string('jumlah_fisik_item')->nullable();

        // Keterangan Detail
        $table->string('jenis_fisik')->nullable();
        $table->string('tingkat_perkembangan')->nullable();
        $table->string('kondisi')->nullable();

        // Lokasi & Retensi
        $table->string('lokasi_boks')->nullable();
        $table->string('lokasi_rak')->nullable();
        $table->string('lokasi_ruangan')->nullable();
        $table->string('retensi_aktif')->nullable();
        $table->string('retensi_inaktif')->nullable();

        // Klasifikasi & Akses
        $table->string('klasifikasi_keamanan')->nullable();
        $table->string('kategori_arsip')->nullable();
        $table->string('tingkat_akses_publik')->nullable();
        $table->string('hak_akses')->nullable();
        $table->text('dasar_pertimbangan')->nullable();
        $table->text('keterangan')->nullable();
        $table->string('tanggal_pemindahan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_inaktifs');
    }
};
