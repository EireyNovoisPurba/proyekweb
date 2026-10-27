<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipVital;
use App\Imports\ArsipVitalImport;

class ArsipVitalController extends Controller
{
    /**
     * Halaman URAIAN INFORMASI BERKAS (Induk) Arsip Vital
     */
    public function index(Request $request)
    {
        $query = ArsipVital::whereIn('id', function($q) {
            $q->selectRaw('MIN(id)')
              ->from('arsip_vitals')
              ->groupBy('uraian_informasi_berkas');
        });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('jenis_arsip', 'like', '%' . $search . '%')
                  ->orWhere('asal_berkas', 'like', '%' . $search . '%')
                  ->orWhere('kode_klasifikasi', 'like', '%' . $search . '%');
            });
        }

        $arsip = $query->latest()->paginate(25);
        $arsip->appends(['search' => $request->search]);

        return view('admin.arsipvital.index', compact('arsip'));
    }

    /**
     * Halaman URAIAN INFORMASI ARSIP (Semua Item Arsip Vital)
     */
    public function daftarArsip(Request $request)
    {
        $query = ArsipVital::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_arsip', 'like', '%' . $search . '%')
                  ->orWhere('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('jenis_arsip', 'like', '%' . $search . '%')
                  ->orWhere('kode_klasifikasi', 'like', '%' . $search . '%');
            });
        }

        if ($request->jenis_arsip) {
            $query->where('jenis_arsip', $request->jenis_arsip);
        }

        $arsipItems = $query->orderBy('uraian_informasi_berkas')->orderBy('no_item_arsip')->paginate(30);
        $arsipItems->appends($request->query());

        return view('admin.arsipvital.daftar-arsip', compact('arsipItems'));
    }

    /**
     * Detail satu Berkas Induk beserta seluruh Item Dokumen
     */
    public function show($id)
    {
        $induk = ArsipVital::findOrFail($id);

        $itemArsip = ArsipVital::where('uraian_informasi_berkas', $induk->uraian_informasi_berkas)
                               ->orderBy('id', 'asc')
                               ->get();

        return view('admin.arsipvital.show', compact('induk', 'itemArsip'));
    }

    /**
     * Form Tambah Arsip Vital Manual
     */
    public function create(Request $request)
    {
        $berkasDefault = $request->query('berkas', '');
        return view('admin.arsipvital.create', compact('berkasDefault'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        ArsipVital::create($request->all());
        return redirect()->route('admin.arsip_vital.index')->with('success', 'Data Arsip Vital berhasil ditambahkan.');
    }

    /**
     * Form Edit Berkas (Induk) - Khusus Field Berkas
     */
    public function editBerkas($id)
    {
        $berkas = ArsipVital::findOrFail($id);
        return view('admin.arsipvital.edit-berkas', compact('berkas'));
    }

    /**
     * Update Data Berkas (Induk) ke seluruh item arsip di dalamnya
     */
    public function updateBerkas(Request $request, $id)
    {
        $berkas = ArsipVital::findOrFail($id);
        $oldUraian = $berkas->uraian_informasi_berkas;

        $request->validate([
            'uraian_informasi_berkas' => 'required',
        ]);

        ArsipVital::where('uraian_informasi_berkas', $oldUraian)->update([
            'uraian_informasi_berkas' => $request->uraian_informasi_berkas,
            'jenis_arsip'             => $request->jenis_arsip,
            'no_berkas'               => $request->no_berkas,
            'pencipta'                => $request->pencipta,
            'asal_berkas'             => $request->asal_berkas,
            'kurun_waktu'             => $request->kurun_waktu,
            'validator'               => $request->validator,
        ]);

        return redirect()->route('admin.arsip_vital.show', $berkas->id)
            ->with('success', 'Data Berkas Induk Arsip Vital berhasil diperbarui.');
    }

    /**
     * Form Edit Detail Satu Item Arsip
     */
    public function edit($id)
    {
        $arsip = ArsipVital::findOrFail($id);
        return view('admin.arsipvital.edit', compact('arsip'));
    }

    /**
     * Update Detail Satu Item Arsip
     */
    public function update(Request $request, $id)
    {
        $arsip = ArsipVital::findOrFail($id);
        $arsip->update($request->all());

        return redirect()->route('admin.arsip_vital.show', $arsip->id)
            ->with('success', 'Data Item Arsip Vital berhasil diperbarui.');
    }

    /**
     * Hapus Satu Item Arsip
     */
    public function destroy($id)
    {
        $item = ArsipVital::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Satu item Arsip Vital berhasil dihapus.');
    }

    /**
     * Hapus Seluruh Berkas Induk beserta seluruh isinya
     */
    public function destroyBerkas($uraian)
    {
        $decoded = urldecode($uraian);
        ArsipVital::where('uraian_informasi_berkas', $decoded)->delete();

        return redirect()->route('admin.arsip_vital.index')
            ->with('success', 'Seluruh berkas beserta isinya berhasil dihapus.');
    }

    /**
     * Import File Excel / CSV Arsip Vital
     */
    public function import(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $request->validate(['file_excel' => 'required|file']);
        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());

        try {
            $resetBefore = $request->boolean('reset_before_import');
            if ($resetBefore) {
                ArsipVital::truncate();
            }

            $count = ArsipVitalImport::importFile($file->getRealPath(), $ext);

            $msg = "Daftar Arsip Vital berhasil diimpor! Total {$count} data item berhasil disimpan.";
            if ($resetBefore) {
                $msg = "Data lama berhasil dikosongkan dan digantikan dengan {$count} data item arsip vital baru dari file.";
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Hapus SELURUH data Arsip Vital (Semua berkas dan seluruh isi dokumen)
     */
    public function destroyAll()
    {
        ArsipVital::truncate();
        return redirect()->route('admin.arsip_vital.index')
            ->with('success', 'Seluruh data Arsip Vital (semua berkas dan seluruh uraian informasi arsip) berhasil dikosongkan!');
    }
}
