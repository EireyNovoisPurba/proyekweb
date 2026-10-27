<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FunFact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FunFactController extends Controller
{
    public function index()
    {
        $funFacts = FunFact::latest()->get();
        return view('admin.fun_fact.index', compact('funFacts'));
    }

    public function create()
    {
        return view('admin.fun_fact.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'tag_badge' => 'required|string|max:50',
            'deskripsi_singkat' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Proses upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('fun-facts', 'public');
        }

        // Jika konten baru diset aktif, nonaktifkan konten yang lama agar di halaman depan hanya muncul 1
        if (isset($data['is_active']) && $data['is_active']) {
            FunFact::where('is_active', true)->update(['is_active' => false]);
        } else {
            $data['is_active'] = false;
        }

        FunFact::create($data);

        return redirect()->route('fun-fact.index')->with('success', 'Konten Fun Fact berhasil ditambahkan!');
    }
public function edit($id)
    {
        // Mengambil data berdasarkan ID untuk ditampilkan di form
        $funFact = FunFact::findOrFail($id);
        return view('admin.fun_fact.edit', compact('funFact'));
    }

    public function update(Request $request, $id)
    {
        $funFact = FunFact::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable karena gambar tidak wajib diganti
            'tag_badge' => 'required|string|max:50',
            'deskripsi_singkat' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->except(['_token', '_method']);

        // Jika user mengunggah gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari storage
            if ($funFact->gambar && Storage::disk('public')->exists($funFact->gambar)) {
                Storage::disk('public')->delete($funFact->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('fun-facts', 'public');
        }

        // Atur logika is_active (hanya 1 konten yang boleh aktif)
        if (isset($data['is_active']) && $data['is_active']) {
            FunFact::where('id', '!=', $id)->where('is_active', true)->update(['is_active' => false]);
        } else {
            $data['is_active'] = false;
        }

        $funFact->update($data);

        return redirect()->route('fun-fact.index')->with('success', 'Konten Fun Fact berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $funFact = FunFact::findOrFail($id);
        if ($funFact->gambar) {
            Storage::disk('public')->delete($funFact->gambar);
        }
        $funFact->delete();

        return redirect()->route('fun-fact.index')->with('success', 'Konten berhasil dihapus!');
    }
}
