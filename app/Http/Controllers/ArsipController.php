<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipImport;
use Illuminate\Support\Facades\Gate;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $penciptaList = Arsip::whereNotNull('pencipta')
                             ->where('pencipta', '!=', '')
                             ->where('pencipta', '!=', '-')
                             ->distinct()
                             ->pluck('pencipta');

        $query = Arsip::whereIn('id', function($q) {
            $q->selectRaw('MIN(id)')->from('arsips')->groupBy('judul_berkas');
        });

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_berkas', 'like', '%' . $request->search . '%')
                  ->orWhere('tahun', 'like', '%' . $request->search . '%');
            });
        }

        $arsip = $query->latest()->paginate(10);
        $arsip->appends(['search' => $request->search]);

        return view('admin.arsip.index', compact('arsip', 'penciptaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_berkas'   => 'required',
            'tahun'          => 'nullable',
            'pencipta'       => 'nullable|string',
            'kategori_arsip' => 'nullable|string'
        ]);

        Arsip::create([
            'kategori_arsip'      => $request->kategori_arsip ?? 'Belum Dikategorikan',
            'judul_berkas'        => $request->judul_berkas,
            'no_berkas'           => $request->no_berkas ?? '-',
            'tahun'               => $request->tahun ?? '-',
            'pencipta'            => $request->pencipta ?? '-',
            'judul_isi'           => '-',
            'tingkat_klasifikasi' => '-',
            'klasifikasi_big'     => '-',
            'jenis_fisik'         => '-',
            'sifat'               => '-',
            'kondisi'             => '-',
            'keterangan'          => '-',
            'tanggal_dokumen'       => '-',
            'kategori_berkas'       => '-',
            'tingkat_akses_publik'  => '-',
            'hak_akses'             => '-',
            'dasar_pertimbangan'    => '-',
            'tanggal_pemindahan'    => '-',
        ]);

        return redirect()->back()->with('success', 'Uraian Informasi Berkas (Folder) berhasil dibuat! Silakan klik Detail Arsip untuk mengisi dokumen.');
    }

    public function show($id)
    {
        $arsip = Arsip::findOrFail($id);

        $isiBerkasTerkait = Arsip::where('judul_berkas', $arsip->judul_berkas)
                                 ->orderBy('id', 'asc')
                                 ->get();

        return view('admin.arsip.show', compact('arsip', 'isiBerkasTerkait'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu!',
            'file_excel.mimes'    => 'Format harus .xlsx atau .xls'
        ]);

        Excel::import(new ArsipImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data arsip massal berhasil diimport!');
    }

   public function edit($id)
    {
        $arsip = \App\Models\Arsip::findOrFail($id);

        if (!Gate::allows('is-admin')) {
            if ($arsip->kategori_arsip == 'Arsip Inaktif' && !Gate::allows('input-inaktif')) {
                abort(403);
            }
            if (in_array($arsip->kategori_arsip, ['Arsip Vital', 'Arsip Terjaga']) && !Gate::allows('input-vital')) {
                abort(403);
            }
        }

        $penciptaList = \App\Models\Arsip::whereNotNull('pencipta')
                             ->where('pencipta', '!=', '')
                             ->where('pencipta', '!=', '-')
                             ->distinct()
                             ->pluck('pencipta');

        return view('admin.arsip.edit', compact('arsip', 'penciptaList'));
    }

    public function update(Request $request, $id)
    {
        $arsip = \App\Models\Arsip::findOrFail($id);
        $judul_berkas_lama = $arsip->judul_berkas;

        $request->validate([
            'judul_berkas'   => 'required|string',
            'tahun'          => 'nullable',
            'pencipta'       => 'nullable|string'
        ]);

        \App\Models\Arsip::where('judul_berkas', $judul_berkas_lama)->update([
            'judul_berkas'   => $request->judul_berkas,
            'tahun'          => $request->tahun ?? '-',
            'pencipta'       => $request->pencipta ?? '-',
        ]);

        return redirect()->route('arsip.index')->with('success', 'Folder Berkas dan seluruh isinya berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);
        $kategori = $arsip->kategori_arsip;
        $isFolder = ($arsip->judul_isi == '-');

        if ($isFolder) {
            $dokumenTerkait = Arsip::where('judul_berkas', $arsip->judul_berkas)->get();

            foreach($dokumenTerkait as $dokumen) {
                if ($dokumen->file_arsip && File::exists(public_path('file_arsip_pdf/' . $dokumen->file_arsip))) {
                    File::delete(public_path('file_arsip_pdf/' . $dokumen->file_arsip));
                }
                $dokumen->delete();
            }

            return redirect()->route('arsip.index')->with('success', 'Folder Induk beserta seluruh dokumen di dalamnya berhasil dihapus bersih!');
        } else {
            if ($arsip->file_arsip && File::exists(public_path('file_arsip_pdf/' . $arsip->file_arsip))) {
                File::delete(public_path('file_arsip_pdf/' . $arsip->file_arsip));
            }
            $arsip->delete();

            return redirect()->route('arsip.kategori', $kategori)->with('success', 'Satu dokumen arsip berhasil dihapus!');
        }
    }

    public function storeDokumen(Request $request)
    {
        $request->validate([
            'judul_berkas' => 'required',
            'judul_isi'    => 'required|string',
            'file_arsip'   => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $namaFile = null;
        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('file_arsip_pdf'), $namaFile);
        }

        $folder = \App\Models\Arsip::where('judul_berkas', $request->judul_berkas)->first();

        \App\Models\Arsip::create([
            'kategori_arsip'      => $folder->kategori_arsip,
            'judul_berkas'        => $folder->judul_berkas,
            'tahun'               => ($request->tahun && $request->tahun != '-') ? $request->tahun : $folder->tahun,
            'judul_isi'           => $request->judul_isi,
            'validator'           => $request->validator ?? '-',
            'asal_berkas'         => $request->asal_berkas ?? '-',
            'retensi_aktif'       => $request->retensi_aktif ?? '-',
            'retensi_inaktif'     => $request->retensi_inaktif ?? '-',
            'lokasi_ruangan'      => $request->lokasi_ruangan ?? '-',
            'tempat'              => $request->tempat ?? '-',
            'folder_boks'         => $request->folder_boks ?? '-',
            'klasifikasi_big'     => $request->klasifikasi_big ?? '-',
            'klasifikasi_bakosurtanal' => $request->klasifikasi_bakosurtanal ?? '-',
            'jumlah'              => $request->jumlah ?? '1',
            'pencipta'            => \App\Models\Arsip::terjemahkan('pencipta', $request->pencipta),
            'tingkat_klasifikasi' => \App\Models\Arsip::terjemahkan('tingkat_klasifikasi', $request->tingkat_klasifikasi),
            'jenis_klasifikasi'   => \App\Models\Arsip::terjemahkan('jenis_klasifikasi', $request->jenis_klasifikasi),
            'jenis_fisik'         => \App\Models\Arsip::terjemahkan('jenis_fisik', $request->jenis_fisik),
            'sifat'               => \App\Models\Arsip::terjemahkan('sifat', $request->sifat),
            'kondisi'             => \App\Models\Arsip::terjemahkan('kondisi', $request->kondisi),
            'keterangan'          => \App\Models\Arsip::terjemahkan('keterangan', $request->keterangan),
            'file_arsip'          => $namaFile,

            'tanggal_dokumen'       => $request->tanggal_dokumen ?? '-',
            'kategori_berkas'       => $request->kategori_berkas ?? '-',
            'tingkat_akses_publik'  => $request->tingkat_akses_publik ?? '-',
            'hak_akses'             => $request->hak_akses ?? '-',
            'dasar_pertimbangan'    => $request->dasar_pertimbangan ?? '-',
            'tanggal_pemindahan'    => $request->tanggal_pemindahan ?? '-',
        ]);

        return redirect()->back()->with('success', 'Dokumen baru berhasil ditambahkan ke dalam Berkas!');
    }

    public function updateDokumen(Request $request, $id)
    {
        $arsip = \App\Models\Arsip::findOrFail($id);

        $request->validate([
            'judul_isi'  => 'required|string',
            'file_arsip' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->except(['_token', '_method', 'file_arsip']);

        // KOREKSI: Pastikan nilai null dari input text diubah menjadi "-"
        $fieldsToSanitize = [
            'validator', 'asal_berkas', 'tanggal_dokumen', 'lokasi_ruangan',
            'tempat', 'folder_boks', 'hak_akses', 'klasifikasi_big',
            'klasifikasi_bakosurtanal', 'tanggal_pemindahan', 'dasar_pertimbangan'
        ];

        foreach ($fieldsToSanitize as $field) {
            if (empty($data[$field])) {
                $data[$field] = '-';
            }
        }

        if ($request->hasFile('file_arsip')) {
            if ($arsip->file_arsip && file_exists(public_path('file_arsip_pdf/' . $arsip->file_arsip))) {
                unlink(public_path('file_arsip_pdf/' . $arsip->file_arsip));
            }
            $file = $request->file('file_arsip');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('file_arsip_pdf'), $nama_file);
            $data['file_arsip'] = $nama_file;
        }

        $arsip->update($data);

        return redirect()->back()->with('success', 'Detail dokumen berhasil diperbarui!');
    }

    public function hapusFile($id)
    {
        $arsip = \App\Models\Arsip::findOrFail($id);

        if ($arsip->file_arsip && \Illuminate\Support\Facades\File::exists(public_path('file_arsip_pdf/' . $arsip->file_arsip))) {
            \Illuminate\Support\Facades\File::delete(public_path('file_arsip_pdf/' . $arsip->file_arsip));
        }

        $arsip->update(['file_arsip' => null]);

        return redirect()->back()->with('success', 'File lampiran berhasil dihapus! Anda dapat mengunggah ulang file baru.');
    }

    public function kategori($kategori, Request $request)
    {
        if (!Gate::allows('is-admin')) {
            if ($kategori == 'Arsip Inaktif' && !Gate::allows('input-inaktif')) abort(403);
            if (in_array($kategori, ['Arsip Vital', 'Arsip Terjaga']) && !Gate::allows('input-vital')) abort(403);
        }

        $query = \App\Models\Arsip::where('kategori_arsip', $kategori)
                                  ->whereIn('id', function($q) {
                                      $q->selectRaw('MIN(id)')->from('arsips')->groupBy('judul_berkas');
                                  });

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul_berkas', 'like', '%' . $request->search . '%')
                  ->orWhere('tahun', 'like', '%' . $request->search . '%');
            });
        }

        $arsip = $query->latest()->paginate(10);
        $arsip->appends(['search' => $request->search]);

        $penciptaList = \App\Models\Arsip::whereNotNull('pencipta')
                             ->where('pencipta', '!=', '')
                             ->where('pencipta', '!=', '-')
                             ->distinct()
                             ->pluck('pencipta');

        return view('admin.arsip.kategori', compact('arsip', 'kategori', 'penciptaList'));
    }
}
