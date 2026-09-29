<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\File;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::all();
        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Judul'  => 'required',
            'Gambar' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'Konten' => 'required'
        ], [
            'Gambar.max' => 'Ukuran gambar maksimal adalah 10MB agar website tetap ringan!'
        ]);

        $image = $request->file('Gambar');
        $namaGambar = $image->hashName();

        // PERBAIKAN: Ubah nama folder menjadi 'gambar_berita' agar tidak bentrok dengan rute '/berita'
        $image->move(public_path('gambar_berita'), $namaGambar);

        Berita::create([
            'Judul'  => $request->Judul,
            'Gambar' => $namaGambar,
            'Konten' => $request->Konten
        ]);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'Judul'  => 'required',
            'Konten' => 'required',
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ], [
            'Gambar.max' => 'Ukuran gambar maksimal adalah 10MB agar website tetap ringan!'
        ]);

        $berita = Berita::findOrFail($id);

        if ($request->hasFile('Gambar')) {
            $image = $request->file('Gambar');
            $namaGambar = $image->hashName();

            // PERBAIKAN: Gunakan folder 'gambar_berita'
            $image->move(public_path('gambar_berita'), $namaGambar);

            // PERBAIKAN: Hapus dari folder 'gambar_berita'
            if (File::exists(public_path('gambar_berita/' . $berita->Gambar))) {
                File::delete(public_path('gambar_berita/' . $berita->Gambar));
            }

            $berita->update([
                'Judul'  => $request->Judul,
                'Gambar' => $namaGambar,
                'Konten' => $request->Konten
            ]);
        } else {
            $berita->update([
                'Judul'  => $request->Judul,
                'Konten' => $request->Konten
            ]);
        }

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $berita = Berita::findOrFail($id);

        // PERBAIKAN: Hapus dari folder 'gambar_berita'
        if (File::exists(public_path('gambar_berita/' . $berita->Gambar))) {
            File::delete(public_path('gambar_berita/' . $berita->Gambar));
        }

        $berita->delete();

        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}
