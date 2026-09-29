<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArsiparisController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\UserController;

// 1. RUTE HALAMAN PUBLIK (Bebas diakses tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/baca-berita/{id}', [HomeController::class, 'show'])->name('berita.baca');

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
});

// 4. Memanggil rute Autentikasi Breeze (Login, Register, dll)
require __DIR__.'/auth.php';
