<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Arsiparis;

class HomeController extends Controller
{

    public function index(Request $request) // Tambahkan Request di sini
    {
        // 1. Mengambil berita terbaru (Maksimal 3 saja agar rapi)
        $beritaTerbaru = Berita::orderBy('id', 'desc')->take(3)->get();

        // 2. Fitur Pencarian untuk Halaman Publik
        $search = $request->input('search');
        $dataArsiparis = Arsiparis::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%");
        })->paginate(10); // Batasi 10 data

        $dataArsiparis->appends(['search' => $search]);

        return view('welcome', compact('beritaTerbaru', 'dataArsiparis', 'search'));
    }
    public function show($id)
    {
        // 1. Ambil data berita dari database berdasarkan ID yang diklik
        $berita = \App\Models\Berita::findOrFail($id);

        // 2. Lempar data tersebut ke file berita-detail.blade.php menggunakan compact('berita')
        return view('berita-detail', compact('berita'));
    }
}