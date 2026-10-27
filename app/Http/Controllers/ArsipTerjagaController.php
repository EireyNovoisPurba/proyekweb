<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipTerjaga;
use App\Imports\ArsipTerjagaImport;

class ArsipTerjagaController extends Controller
{
    /**
     * Halaman URAIAN INFORMASI BERKAS (Induk) Arsip Terjaga
     */
    public function index(Request $request)
    {
        $query = ArsipTerjaga::whereIn('id', function($q) {
            $q->selectRaw('MIN(id)')
              ->from('arsip_terjagas')
              ->groupBy('uraian_informasi_berkas');
        });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('klasifikasi_arsip', 'like', '%' . $search . '%')
                  ->orWhere('asal_berkas', 'like', '%' . $search . '%')
                  ->orWhere('pencipta', 'like', '%' . $search . '%');
            });
        }

        $arsip = $query->latest()->paginate(25);
        $arsip->appends(['search' => $request->search]);

        return view('admin.arsipterjaga.index', compact('arsip'));
    }

    /**
     * Halaman URAIAN INFORMASI ARSIP (Semua Item Arsip Terjaga)
     */
    public function daftarArsip(Request $request)
    {
        $query = ArsipTerjaga::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_arsip', 'like', '%' . $search . '%')
                  ->orWhere('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('klasifikasi_arsip', 'like', '%' . $search . '%')
                  ->orWhere('pencipta', 'like', '%' . $search . '%');
            });
        }

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->tingkat_perkembangan) {
            $query->where('tingkat_perkembangan', $request->tingkat_perkembangan);
        }

        $arsipItems = $query->orderBy('uraian_informasi_berkas')->orderBy('no_item_arsip')->paginate(30);
        $arsipItems->appends($request->query());

        return view('admin.arsipterjaga.daftar-arsip', compact('arsipItems'));
    }

    /**
     * Detail satu Berkas Induk beserta seluruh Item Dokumen
     */
    public function show($id)
    {
        $induk = ArsipTerjaga::findOrFail($id);

        $itemArsip = ArsipTerjaga::where('uraian_informasi_berkas', $induk->uraian_informasi_berkas)
                                 ->orderBy('id', 'asc')
                                 ->get();

        return view('admin.arsipterjaga.show', compact('induk', 'itemArsip'));
    }

    /**
     * Form Tambah Arsip Terjaga Manual
     */
    public function create(Request $request)
    {
        $berkasDefault = $request->query('berkas', '');
        return view('admin.arsipterjaga.create', compact('berkasDefault'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        ArsipTerjaga::create($request->all());
        return redirect()->route('admin.arsip_terjaga.index')->with('success', 'Data Arsip Terjaga berhasil ditambahkan.');
    }

    /**
     * Form Edit Berkas (Induk) - Khusus Field Berkas
     */
    public function editBerkas($id)
    {
        $berkas = ArsipTerjaga::findOrFail($id);
        return view('admin.arsipterjaga.edit-berkas', compact('berkas'));
    }

    /**
     * Update Data Berkas (Induk) ke seluruh item arsip di dalamnya
     */
    public function updateBerkas(Request $request, $id)
    {
        $berkas = ArsipTerjaga::findOrFail($id);
        $oldUraian = $berkas->uraian_informasi_berkas;

        $request->validate([
            'uraian_informasi_berkas' => 'required',
        ]);

        ArsipTerjaga::where('uraian_informasi_berkas', $oldUraian)->update([
            'uraian_informasi_berkas' => $request->uraian_informasi_berkas,
            'klasifikasi_arsip'       => $request->klasifikasi_arsip,
            'no_berkas'               => $request->no_berkas,
            'pencipta'                => $request->pencipta,
            'asal_berkas'             => $request->asal_berkas,
            'kurun_waktu'             => $request->kurun_waktu,
            'jumlah_isi_berkas'       => $request->jumlah_isi_berkas,
        ]);

        return redirect()->route('admin.arsip_terjaga.show', $berkas->id)
            ->with('success', 'Data Berkas Induk Arsip Terjaga berhasil diperbarui.');
    }

    /**
     * Form Edit Detail Satu Item Arsip
     */
    public function edit($id)
    {
        $arsip = ArsipTerjaga::findOrFail($id);
        return view('admin.arsipterjaga.edit', compact('arsip'));
    }

    /**
     * Update Detail Satu Item Arsip
     */
    public function update(Request $request, $id)
    {
        $arsip = ArsipTerjaga::findOrFail($id);
        $arsip->update($request->all());

        return redirect()->route('admin.arsip_terjaga.show', $arsip->id)
            ->with('success', 'Data Item Arsip Terjaga berhasil diperbarui.');
    }

    /**
     * Hapus Satu Item Arsip
     */
    public function destroy($id)
    {
        $item = ArsipTerjaga::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Satu item Arsip Terjaga berhasil dihapus.');
    }

    /**
     * Hapus Seluruh Berkas Induk beserta seluruh isinya
     */
    public function destroyBerkas($uraian)
    {
        $decoded = urldecode($uraian);
        ArsipTerjaga::where('uraian_informasi_berkas', $decoded)->delete();

        return redirect()->route('admin.arsip_terjaga.index')
            ->with('success', 'Seluruh berkas beserta isinya berhasil dihapus.');
    }

    /**
     * Import File Excel / CSV Arsip Terjaga
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
                ArsipTerjaga::truncate();
            }

            $count = ArsipTerjagaImport::importFile($file->getRealPath(), $ext);

            $msg = "Daftar Arsip Terjaga berhasil diimpor! Total {$count} data item berhasil disimpan.";
            if ($resetBefore) {
                $msg = "Data lama berhasil dikosongkan dan digantikan dengan {$count} data item arsip terjaga baru dari file.";
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Hapus SELURUH data Arsip Terjaga (Semua berkas dan seluruh isi dokumen)
     */
    public function destroyAll()
    {
        ArsipTerjaga::truncate();
        return redirect()->route('admin.arsip_terjaga.index')
            ->with('success', 'Seluruh data Arsip Terjaga (semua berkas dan seluruh uraian informasi arsip) berhasil dikosongkan!');
    }
}
