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
    Schema::create('fun_facts', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('gambar'); // Untuk menyimpan path gambar vertikal 9:16
        $table->string('tag_badge'); // Contoh: "Tahukah Kamu?" atau "SobatArsip"
        $table->text('deskripsi_singkat');
        $table->text('poin_1_judul')->nullable();
        $table->text('poin_1_isi')->nullable();
        $table->text('poin_2_judul')->nullable();
        $table->text('poin_2_isi')->nullable();
        $table->text('poin_3_judul')->nullable();
        $table->text('poin_3_isi')->nullable();
        $table->text('kesimpulan')->nullable();
        $table->boolean('is_active')->default(true); // Untuk mengatur konten mana yang tampil
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fun_facts');
    }
};