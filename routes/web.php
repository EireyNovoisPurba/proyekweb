<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArsiparisController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\FunFactController;
use App\Http\Controllers\ArsipInaktifController;
use App\Http\Controllers\ArsipTerjagaController;
use App\Http\Controllers\ArsipVitalController;
use App\Http\Middleware\TrackVisitor;

// 1. RUTE HALAMAN PUBLIK (Bebas diakses tanpa login)
Route::middleware([TrackVisitor::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/baca-berita/{id}', [HomeController::class, 'show'])->name('berita.baca');
});

// 2. Rute Profile (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. KELOMPOK ROUTE ADMIN (Terkunci dengan Middleware Auth)
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute Kelola Pengguna (Hanya bisa diakses oleh Admin)
    Route::resource('users', UserController::class)->middleware('can:is-admin');

    // --- RUTE KELOLA ARSIP ---
    // Rute Dinamis untuk Menu Sidebar Kategori Arsip
    Route::get('arsip/kategori/{kategori}', [ArsipController::class, 'kategori'])->name('arsip.kategori');

    Route::resource('arsip', ArsipController::class);
    Route::post('arsip/import', [ArsipController::class, 'import'])->name('arsip.import');

    // ====================================================================
    // RUTE BARU: ARSIP INAKTIF (Master-Detail)
    // ====================================================================
    // Route khusus untuk menangkap aksi Import Excel
    Route::post('/admin/arsip_inaktif/import', [ArsipInaktifController::class, 'import'])->name('admin.arsip_inaktif.import');

    // Route Export Excel & Cetak Label
    Route::get('/admin/arsip_inaktif/export', [ArsipInaktifController::class, 'export'])->name('admin.arsip_inaktif.export');
    Route::get('/admin/arsip_inaktif/cetak-label/{uraian}', [ArsipInaktifController::class, 'cetakLabel'])->name('admin.arsip_inaktif.cetakLabel');

    // Route Uraian Informasi Arsip (Daftar semua item dari semua berkas)
    Route::get('/admin/arsip_inaktif/daftar-arsip', [ArsipInaktifController::class, 'daftarArsip'])->name('admin.arsip_inaktif.daftar-arsip');

    // Route khusus Edit & Update Berkas (Induk)
    Route::get('/admin/arsip_inaktif/berkas/{id}/edit', [ArsipInaktifController::class, 'editBerkas'])->name('admin.arsip_inaktif.editBerkas');
    Route::put('/admin/arsip_inaktif/berkas/{id}', [ArsipInaktifController::class, 'updateBerkas'])->name('admin.arsip_inaktif.updateBerkas');

    // Route khusus untuk Hapus Induk (Seluruh Berkas)
    Route::delete('/admin/arsip_inaktif/destroy-berkas/{uraian}', [ArsipInaktifController::class, 'destroyBerkas'])->name('admin.arsip_inaktif.destroyBerkas');

    // Route khusus Hapus Semua Data Arsip Inaktif
    Route::delete('/admin/arsip_inaktif/destroy-all', [ArsipInaktifController::class, 'destroyAll'])->name('admin.arsip_inaktif.destroyAll');

    // Resource Route (Otomatis membuatkan route index, create, store, show, edit, update, destroy item)
    Route::resource('/admin/arsip_inaktif', ArsipInaktifController::class, [
        'as' => 'admin'
    ]);
    // ====================================================================

    // ====================================================================
    // RUTE BARU: ARSIP TERJAGA (Master-Detail)
    // ====================================================================
    Route::post('/admin/arsip_terjaga/import', [ArsipTerjagaController::class, 'import'])->name('admin.arsip_terjaga.import');
    Route::get('/admin/arsip_terjaga/daftar-arsip', [ArsipTerjagaController::class, 'daftarArsip'])->name('admin.arsip_terjaga.daftar-arsip');
    Route::get('/admin/arsip_terjaga/berkas/{id}/edit', [ArsipTerjagaController::class, 'editBerkas'])->name('admin.arsip_terjaga.editBerkas');
    Route::put('/admin/arsip_terjaga/berkas/{id}', [ArsipTerjagaController::class, 'updateBerkas'])->name('admin.arsip_terjaga.updateBerkas');
    Route::delete('/admin/arsip_terjaga/destroy-berkas/{uraian}', [ArsipTerjagaController::class, 'destroyBerkas'])->name('admin.arsip_terjaga.destroyBerkas');
    Route::delete('/admin/arsip_terjaga/destroy-all', [ArsipTerjagaController::class, 'destroyAll'])->name('admin.arsip_terjaga.destroyAll');
    Route::resource('/admin/arsip_terjaga', ArsipTerjagaController::class, [
        'as' => 'admin'
    ]);

    // ====================================================================
    // RUTE BARU: ARSIP VITAL (Master-Detail)
    // ====================================================================
    Route::post('/admin/arsip_vital/import', [ArsipVitalController::class, 'import'])->name('admin.arsip_vital.import');
    Route::get('/admin/arsip_vital/daftar-arsip', [ArsipVitalController::class, 'daftarArsip'])->name('admin.arsip_vital.daftar-arsip');
    Route::get('/admin/arsip_vital/berkas/{id}/edit', [ArsipVitalController::class, 'editBerkas'])->name('admin.arsip_vital.editBerkas');
    Route::put('/admin/arsip_vital/berkas/{id}', [ArsipVitalController::class, 'updateBerkas'])->name('admin.arsip_vital.updateBerkas');
    Route::delete('/admin/arsip_vital/destroy-berkas/{uraian}', [ArsipVitalController::class, 'destroyBerkas'])->name('admin.arsip_vital.destroyBerkas');
    Route::delete('/admin/arsip_vital/destroy-all', [ArsipVitalController::class, 'destroyAll'])->name('admin.arsip_vital.destroyAll');
    Route::resource('/admin/arsip_vital', ArsipVitalController::class, [
        'as' => 'admin'
    ]);
    // ====================================================================

    // ====================================================================
    // KODE BARU DARI AI: Rute khusus mengelola Judul Isi (Dokumen)
    // ====================================================================
    Route::post('arsip/dokumen/store', [ArsipController::class, 'storeDokumen'])->name('arsip.storeDokumen');
    Route::put('arsip/dokumen/{id}/update', [ArsipController::class, 'updateDokumen'])->name('arsip.updateDokumen');

    // Rute khusus untuk Hapus File Lampiran saja (tanpa menghapus data teksnya)
    Route::delete('arsip/dokumen/{id}/hapus-file', [App\Http\Controllers\ArsipController::class, 'hapusFile'])->name('arsip.hapusFile');

    // A. Menangani redirect bawaan setelah sukses Login
    Route::get('/home', function () {
        return redirect('/admin/dashboard');
    });

    Route::get('/dashboard', function () {
        return redirect('/admin/dashboard');
    })->name('dashboard');

    // ====================================================================
    // B. ROUTE DASHBOARD ADMIN CERDAS (YANG BARU DIPERBARUI)
    // ====================================================================
    Route::get('/admin/dashboard', function () {
        // Menghitung jumlah dokumen (bukan folder) per kategori
        $vital = \App\Models\Arsip::where('kategori_arsip', 'Arsip Vital')->where('judul_isi', '!=', '-')->count();
        $terjaga = \App\Models\Arsip::where('kategori_arsip', 'Arsip Terjaga')->where('judul_isi', '!=', '-')->count();
        $inaktif = \App\Models\Arsip::where('kategori_arsip', 'Arsip Inaktif')->where('judul_isi', '!=', '-')->count();

        // Menghitung total arsiparis/user yang terdaftar
        $users = \App\Models\User::count();

        return view('admin.dashboard', compact('vital', 'terjaga', 'inaktif', 'users'));
    });

    // C. Fitur Import Excel (Arsiparis)
    Route::post('arsiparis/import', [ArsiparisController::class, 'import'])->name('arsiparis.import');

    // D. Rute Kelola Data (CRUD Arsiparis & Berita)
    Route::resource('arsiparis', ArsiparisController::class);
    Route::resource('berita', BeritaController::class);

    // E. Rute Kelola Fun Fact (Pojok Edukasi)
    Route::resource('admin/fun-fact', FunFactController::class);
});

// 4. Memanggil rute Autentikasi Breeze (Login, Register, dll)
require __DIR__.'/auth.php';