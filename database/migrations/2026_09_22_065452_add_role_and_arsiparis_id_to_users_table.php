<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom role (default ke UP jika tidak diisi)
            $table->string('role')->default('arsiparis_up')->after('email');

            // Menambahkan kolom arsiparis_id sebagai Foreign Key (bisa kosong/null untuk Admin)
            $table->unsignedBigInteger('arsiparis_id')->nullable()->after('role');

            // Membuat relasi yang menyambungkan ke tabel arsiparis
            $table->foreign('arsiparis_id')->references('id')->on('arsiparis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['arsiparis_id']);
            $table->dropColumn(['role', 'arsiparis_id']);
        });
    }
};
