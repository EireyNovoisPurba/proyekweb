<?php

namespace App\Imports;

use App\Models\Arsip;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ArsipImport implements ToCollection, WithStartRow
{
    /**
     * Berdasarkan file Excel terbaru, data baru dimulai pada baris ke-7
     */
    public function startRow(): int
    {
        return 7;
    }

    public function collection(Collection $rows)
    {
        $lastJudulBerkas = '-';
        $lastTahun = '-';
        $lastPencipta = '-';
        $lastNoBerkas = '-';
        $lastKodeKlas = '-';

        foreach ($rows as $row) {
            // Abaikan baris jika Uraian Informasi Arsip kosong
            if (empty($row[8])) continue;

            // 1. BUAT / INGAT FOLDER INDUK JIKA KOLOM "Uraian Informasi Berkas" TERISI
            if (!empty($row[4])) {
                $lastPencipta    = $row[1] ?? '-';
                $lastNoBerkas    = $row[2] ?? '-';
                $lastKodeKlas    = $row[3] ?? '-';
                $lastJudulBerkas = $row[4];
                $lastTahun       = $row[5] ?? '-';

                $cekFolder = Arsip::where('judul_berkas', $lastJudulBerkas)->where('judul_isi', '-')->first();
                if (!$cekFolder) {
                    Arsip::create([
                        'kategori_arsip' => 'Arsip Inaktif', // Mengunci ke Inaktif
                        'judul_berkas'   => $lastJudulBerkas,
                        'no_berkas'      => $lastNoBerkas,
                        'tahun'          => $lastTahun,
                        'pencipta'       => $lastPencipta,
                        'judul_isi'      => '-',
                    ]);
                }
            }

            // 2. SIMPAN DOKUMEN KE DALAM FOLDER
            Arsip::create([
                'kategori_arsip'        => 'Arsip Inaktif',
                'judul_berkas'          => $lastJudulBerkas,
                'no_berkas'             => $lastNoBerkas,
                'pencipta'              => $lastPencipta,
                'klasifikasi_big'       => $lastKodeKlas, // Disimpan dari kode klasifikasi

                // MAPPING 100% SESUAI KOLOM EXCEL BARU
                'judul_isi'             => $row[8] ?? '-',
                'tanggal_dokumen'       => $row[9] ?? '-',
                'jumlah'                => $row[10] ?? '1',
                'jenis_fisik'           => $row[11] ?? '-',
                'sifat'                 => $row[12] ?? '-', // Tingkat Perkembangan
                'kondisi'               => $row[13] ?? '-',
                'folder_boks'           => $row[14] ?? '-',
                'tempat'                => $row[15] ?? '-', // Rak
                'lokasi_ruangan'        => $row[16] ?? '-',
                'retensi_aktif'         => $row[17] ?? '-',
                'retensi_inaktif'       => $row[18] ?? '-',
                'tingkat_klasifikasi'   => $row[19] ?? '-', // Klasifikasi Keamanan
                'kategori_berkas'       => $row[20] ?? '-', // Substantif/Fasilitatif
                'tingkat_akses_publik'  => $row[21] ?? '-',
                'hak_akses'             => $row[22] ?? '-',
                'dasar_pertimbangan'    => $row[23] ?? '-',
                'keterangan'            => $row[24] ?? '-', // Nasib Akhir
                'tanggal_pemindahan'    => $row[25] ?? '-',
            ]);
        }
    }
}
