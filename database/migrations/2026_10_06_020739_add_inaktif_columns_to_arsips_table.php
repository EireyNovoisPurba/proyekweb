<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('arsips', function (Blueprint $table) {
        if (!Schema::hasColumn('arsips', 'no_berkas')) $table->string('no_berkas')->nullable()->after('judul_berkas');
        if (!Schema::hasColumn('arsips', 'tanggal_dokumen')) $table->string('tanggal_dokumen')->nullable()->after('tahun');
        if (!Schema::hasColumn('arsips', 'kategori_berkas')) $table->string('kategori_berkas')->nullable()->after('kategori_arsip');
        if (!Schema::hasColumn('arsips', 'tingkat_akses_publik')) $table->string('tingkat_akses_publik')->nullable()->after('tingkat_klasifikasi');
        if (!Schema::hasColumn('arsips', 'hak_akses')) $table->string('hak_akses')->nullable()->after('tingkat_akses_publik');
        if (!Schema::hasColumn('arsips', 'dasar_pertimbangan')) $table->text('dasar_pertimbangan')->nullable()->after('hak_akses');
        if (!Schema::hasColumn('arsips', 'tanggal_pemindahan')) $table->string('tanggal_pemindahan')->nullable()->after('retensi_inaktif');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            //
        });
    }
};
