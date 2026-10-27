<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipInaktif;
use App\Exports\ArsipInaktifExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipInaktifImport;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipInaktifController extends Controller
{
    /**
     * Export semua data ke Excel
     */
    public function export()
    {
        return Excel::download(new ArsipInaktifExport, 'Daftar_Arsip_Inaktif.xlsx');
    }

    /**
     * Cetak label boks PDF
     */
    public function cetakLabel($uraian_informasi_berkas)
    {
        $arsip = ArsipInaktif::where('uraian_informasi_berkas', $uraian_informasi_berkas)->firstOrFail();

        $pdf = Pdf::loadView('admin.arsipinaktif.label', compact('arsip'))
                  ->setPaper([0, 0, 283.465, 425.197], 'landscape');

        return $pdf->stream('Label_Boks_' . ($arsip->lokasi_boks ?? 'TanpaBoks') . '.pdf');
    }

    /**
     * Halaman URAIAN INFORMASI BERKAS — menampilkan 1 baris per berkas (dikelompokkan)
     */
    public function index(Request $request)
    {
        $query = ArsipInaktif::whereIn('id', function($q) {
            $q->selectRaw('MIN(id)')
              ->from('arsip_inaktifs')
              ->groupBy('uraian_informasi_berkas');
        });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('kode_klasifikasi', 'like', '%' . $search . '%')
                  ->orWhere('asal_unit_pengolah', 'like', '%' . $search . '%');
            });
        }

        $arsip = $query->latest()->paginate(25);
        $arsip->appends(['search' => $request->search]);

        return view('admin.arsipinaktif.index', compact('arsip'));
    }

    /**
     * Halaman URAIAN INFORMASI ARSIP — menampilkan SEMUA item dari SEMUA berkas
     */
    public function daftarArsip(Request $request)
    {
        $query = ArsipInaktif::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('uraian_informasi_arsip', 'like', '%' . $search . '%')
                  ->orWhere('uraian_informasi_berkas', 'like', '%' . $search . '%')
                  ->orWhere('kode_klasifikasi', 'like', '%' . $search . '%')
                  ->orWhere('asal_unit_pengolah', 'like', '%' . $search . '%');
            });
        }

        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->klasifikasi_keamanan) {
            $query->where('klasifikasi_keamanan', $request->klasifikasi_keamanan);
        }

        $arsipItems = $query->orderBy('uraian_informasi_berkas')->orderBy('no_item_arsip')->paginate(30);
        $arsipItems->appends($request->query());

        return view('admin.arsipinaktif.daftar-arsip', compact('arsipItems'));
    }

    /**
     * Form Tambah Manual Arsip Inaktif (Berkas + Item sekaligus)
     */
    public function create(Request $request)
    {
        $berkasDefault = $request->query('berkas', '');
        return view('admin.arsipinaktif.create', compact('berkasDefault'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        ArsipInaktif::create($request->all());
        return redirect()->route('admin.arsip_inaktif.index')->with('success', 'Data Arsip Inaktif berhasil ditambahkan.');
    }

    /**
     * Form Edit satu item arsip
     */
    public function edit($id)
    {
        $arsip = ArsipInaktif::findOrFail($id);
        return view('admin.arsipinaktif.edit', compact('arsip'));
    }

    /**
     * Update satu item arsip
     */
    public function update(Request $request, $id)
    {
        $arsip = ArsipInaktif::findOrFail($id);
        $arsip->update($request->all());

        return redirect()->back()->with('success', 'Data Arsip Inaktif berhasil diperbarui.');
    }

    /**
     * Preview detail satu berkas beserta semua item arsip di dalamnya
     */
    public function show($id)
    {
        $induk = ArsipInaktif::findOrFail($id);

        $itemArsip = ArsipInaktif::where('uraian_informasi_berkas', $induk->uraian_informasi_berkas)
                                 ->orderBy('no_item_arsip', 'asc')
                                 ->orderBy('id', 'asc')
                                 ->get();

        return view('admin.arsipinaktif.show', compact('induk', 'itemArsip'));
    }

    /**
     * Form Edit Uraian Informasi Berkas (Induk) - Khusus 6 field berkas
     */
    public function editBerkas($id)
    {
        $berkas = ArsipInaktif::findOrFail($id);
        return view('admin.arsipinaktif.edit-berkas', compact('berkas'));
    }

    /**
     * Update Uraian Informasi Berkas (Induk) ke seluruh item arsip di dalamnya
     */
    public function updateBerkas(Request $request, $id)
    {
        $berkas = ArsipInaktif::findOrFail($id);
        $oldUraian = $berkas->uraian_informasi_berkas;

        $request->validate([
            'uraian_informasi_berkas' => 'required',
        ]);

        ArsipInaktif::where('uraian_informasi_berkas', $oldUraian)->update([
            'uraian_informasi_berkas' => $request->uraian_informasi_berkas,
            'asal_unit_pengolah'      => $request->asal_unit_pengolah,
            'no_berkas'               => $request->no_berkas,
            'kode_klasifikasi'        => $request->kode_klasifikasi,
            'kurun_waktu'             => $request->kurun_waktu,
            'jumlah_fisik_berkas'     => $request->jumlah_fisik_berkas,
        ]);

        return redirect()->route('admin.arsip_inaktif.show', $berkas->id)
            ->with('success', 'Uraian Informasi Berkas (Induk) berhasil diperbarui untuk seluruh item arsip.');
    }

    /**
     * Import file Excel / CSV (Streaming cepat tanpa timeout & atasi formula)
     */
    public function import(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $request->validate([
            'file_excel' => 'required|file',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return redirect()->back()->with('error', 'Format file tidak didukung. Harap upload file .xlsx, .xls, atau .csv.');
        }

        try {
            $resetBefore = $request->boolean('reset_before_import');
            if ($resetBefore) {
                ArsipInaktif::truncate();
            }

            $count = ArsipInaktifImport::importFile($file->getRealPath(), $ext);
            
            $msg = "Daftar Arsip Inaktif berhasil diimpor! Total {$count} data item arsip berhasil disimpan ke sistem.";
            if ($resetBefore) {
                $msg = "Data lama berhasil dikosongkan dan digantikan dengan {$count} data item arsip baru dari file.";
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Hapus satu item arsip
     */
    public function destroy($id)
    {
        $item = ArsipInaktif::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Satu item arsip berhasil dihapus.');
    }

    /**
     * Hapus seluruh berkas beserta semua item arsip di dalamnya
     */
    public function destroyBerkas($uraian_informasi_berkas)
    {
        $decoded = urldecode($uraian_informasi_berkas);
        ArsipInaktif::where('uraian_informasi_berkas', $decoded)->delete();
        return redirect()->route('admin.arsip_inaktif.index')->with('success', 'Seluruh berkas beserta isinya berhasil dihapus.');
    }

    /**
     * Hapus SELURUH data Arsip Inaktif (Semua berkas dan seluruh isi arsip)
     */
    public function destroyAll()
    {
        ArsipInaktif::truncate();
        return redirect()->route('admin.arsip_inaktif.index')
            ->with('success', 'Seluruh data Arsip Inaktif (semua berkas dan seluruh uraian informasi arsip) berhasil dikosongkan!');
    }
}