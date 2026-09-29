<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Mengubah nama kolom lama menjadi 'pangkat'
        Schema::table('arsiparis', function (Blueprint $table) {
            $table->renameColumn('pangkat_golongan', 'pangkat');
        });
        // Menambahkan kolom 'golongan' tepat di sebelahnya
        Schema::table('arsiparis', function (Blueprint $table) {
            $table->string('golongan')->nullable()->after('pangkat');
        });
    }

    public function down(): void {
        Schema::table('arsiparis', function (Blueprint $table) {
            $table->dropColumn('golongan');
            $table->renameColumn('pangkat', 'pangkat_golongan');
        });
    }
};
