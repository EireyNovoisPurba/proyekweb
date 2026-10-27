<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Arsiparis;
use App\Models\Arsip;
use App\Models\Visitor;
use App\Models\FunFact; // 1. Tambahkan pemanggilan Model FunFact

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mengambil berita terbaru (Maksimal 3 saja agar rapi)
        $beritaTerbaru = Berita::orderBy('id', 'desc')->take(3)->get();

        // 2. Fitur Pencarian untuk Halaman Publik (Arsiparis)
        $search = $request->input('search');
        $dataArsiparis = Arsiparis::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%");
        })->paginate(10);

        $dataArsiparis->appends(['search' => $search]);

        // 3. Data Statistik Arsip
        $arsipVital = Arsip::where('kategori_arsip', 'Arsip Vital')
                           ->where('judul_isi', '!=', '-')
                           ->count();

        $arsipTerjaga = Arsip::where('kategori_arsip', 'Arsip Terjaga')
                             ->where('judul_isi', '!=', '-')
                             ->count();

        $arsipInaktif = Arsip::where('kategori_arsip', 'Arsip Inaktif')
                             ->where('judul_isi', '!=', '-')
                             ->count();

        // 4. Data Statistik Pengunjung
        $totalPengunjung = Visitor::count();
        $pengunjungHariIni = Visitor::whereDate('visit_date', now()->toDateString())->count();

        // 5. Data Pojok Edukasi Dinamis (Mengambil 1 data terbaru yang aktif)
        $funFact = FunFact::where('is_active', true)->latest()->first();

        // 6. Gabungkan semua variabel ke dalam compact()
        return view('welcome', compact(
            'beritaTerbaru',
            'dataArsiparis',
            'search',
            'arsipVital',
            'arsipTerjaga',
            'arsipInaktif',
            'totalPengunjung',
            'pengunjungHariIni',
            'funFact' // Tambahkan variabel funFact ke sini
        ));
    }

    public function show($id)
    {
        // 1. Ambil data berita dari database berdasarkan ID yang diklik
        $berita = \App\Models\Berita::findOrFail($id);

        // 2. Lempar data tersebut ke file berita-detail.blade.php menggunakan compact('berita')
        return view('berita-detail', compact('berita'));
    }
}