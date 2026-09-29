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
    Schema::create('arsiparis', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('nip')->unique()->nullable();
        $table->string('tempat_tanggal_lahir')->nullable();
        $table->string('pangkat_golongan')->nullable();
        $table->string('pendidikan')->nullable();
        $table->string('jabatan')->nullable();
        $table->string('unit_kerja')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsiparis');
    }
};
