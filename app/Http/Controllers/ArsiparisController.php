<?php

namespace App\Http\Controllers;

use App\Models\Arsiparis;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsiparisImport;

class ArsiparisController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->input('search');

        $arsiparis = \App\Models\Arsiparis::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%");
        })->paginate(10);

        $arsiparis->appends(['search' => $search]);

        return view('admin.arsiparis.index', compact('arsiparis', 'search'));
    }

    public function create()
    {
        return view('admin.arsiparis.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi: NIP sekarang 'nullable' (Boleh Kosong)
        $request->validate([
            'nama'                 => 'required|string|max:255',
            'nip'                  => 'nullable|string|unique:arsiparis,nip',
            'jabatan'              => 'required|string',
            'unit_kerja'           => 'required|string',
            'pangkat'              => 'nullable|string',
            'golongan'             => 'nullable|string',
            'pendidikan'           => 'nullable|string',
            'tempat_tanggal_lahir' => 'nullable|string',
        ]);

        // 2. Buat NIP otomatis jika dikosongkan oleh user
        $nip_simpan = $request->nip ? $request->nip : 'NONASN-' . rand(1000, 9999);

        \App\Models\Arsiparis::create([
            'nama'                 => $request->nama,
            'nip'                  => $nip_simpan, // Gunakan NIP yang sudah difilter
            'jabatan'              => $request->jabatan,
            'unit_kerja'           => $request->unit_kerja,
            'pangkat'              => $request->pangkat,
            'golongan'             => $request->golongan,
            'pendidikan'           => $request->pendidikan,
            'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
        ]);

        return redirect()->route('arsiparis.index')->with('success', 'Data SDM berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $arsiparis = Arsiparis::findOrFail($id);
        return view('admin.arsiparis.edit', compact('arsiparis'));
    }

    public function update(Request $request, $id)
    {
        $arsiparis = \App\Models\Arsiparis::findOrFail($id);

        // 1. Validasi Update (NIP nullable)
        $request->validate([
            'nama'                 => 'required|string|max:255',
            'nip'                  => 'nullable|string|unique:arsiparis,nip,' . $arsiparis->id,
            'jabatan'              => 'required|string',
            'unit_kerja'           => 'required|string',
            'pangkat'              => 'nullable|string',
            'golongan'             => 'nullable|string',
            'pendidikan'           => 'nullable|string',
            'tempat_tanggal_lahir' => 'nullable|string',
        ], [
            'nip.unique' => 'Gagal memperbarui! NIP ini sudah dipakai oleh pegawai lain.'
        ]);

        // 2. Buat NIP otomatis jika dikosongkan oleh user saat edit
        $nip_simpan = $request->nip ? $request->nip : 'NONASN-' . rand(1000, 9999);

        // 3. Update ke Database
        $arsiparis->update([
            'nama'                 => $request->nama,
            'nip'                  => $nip_simpan,
            'jabatan'              => $request->jabatan,
            'unit_kerja'           => $request->unit_kerja,
            'pangkat'              => $request->pangkat,
            'golongan'             => $request->golongan,
            'pendidikan'           => $request->pendidikan,
            'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
        ]);

        return redirect()->route('arsiparis.index')->with('success', 'Data SDM Arsiparis berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $arsiparis = Arsiparis::findOrFail($id);
        $arsiparis->delete();

        return redirect()->route('arsiparis.index')->with('success', 'Data arsiparis berhasil dihapus!');
    }

    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new ArsiparisImport, $request->file('file_excel'));

        return redirect()->route('arsiparis.index')->with('success', 'Data SDM berhasil diimpor dari Excel!');
    }
}
