<?php

namespace App\Imports;

use App\Models\Arsip;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ArsipImport implements ToCollection, WithStartRow
{
    /**
     * Mulai baca dari baris ke-2 (melewati baris judul/header Excel)
     */
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        // Variabel untuk "mengingat" Induk Berkas dari baris sebelumnya
        $lastJudulBerkas = '-';
        $lastTahun = '-';
        $lastKategoriArsip = '-';
        $lastPencipta = '-';

        foreach ($rows as $row) {
            // Abaikan baris jika benar-benar kosong (Kolom Judul Berkas & Judul Isi kosong)
            if (empty($row[1]) && empty($row[4])) continue;

            // =========================================================
            // 1. JIKA BARIS INI MEMILIKI JUDUL BERKAS (PEMBUATAN FOLDER)
            // =========================================================
            if (!empty($row[1])) {
                $lastJudulBerkas   = $row[1];
                $lastTahun         = $row[7] ?? '-';
                $lastKategoriArsip = Arsip::terjemahkan('kategori_arsip', $row[3]);
                $lastPencipta      = Arsip::terjemahkan('pencipta', $row[5]);

                // Buat Folder Induk jika belum pernah ada di database
                $cekFolder = Arsip::where('judul_berkas', $lastJudulBerkas)->where('judul_isi', '-')->first();
                if (!$cekFolder) {
                    Arsip::create([
                        'kategori_arsip' => $lastKategoriArsip,
                        'judul_berkas'   => $lastJudulBerkas,
                        'tahun'          => $lastTahun,
                        'pencipta'       => $lastPencipta,
                        'judul_isi'      => '-', // Penanda bahwa ini adalah Folder Induk
                    ]);
                }
            }

            // =========================================================
            // 2. JIKA BARIS INI MEMILIKI JUDUL ISI (PENYIMPANAN DOKUMEN)
            // =========================================================
            if (!empty($row[4])) {
                Arsip::create([
                    // Mengikuti parent (Folder)
                    'judul_berkas'   => $lastJudulBerkas,

                    // Terjemahan Kamus Data (Angka -> Teks)
                    'kategori_arsip'      => Arsip::terjemahkan('kategori_arsip', $row[3] ?? '-'),
                    'pencipta'            => Arsip::terjemahkan('pencipta', $row[5] ?? '-'),
                    'tingkat_klasifikasi' => Arsip::terjemahkan('tingkat_klasifikasi', $row[13] ?? '-'),
                    'jenis_klasifikasi'   => Arsip::terjemahkan('jenis_klasifikasi', $row[14] ?? '-'),
                    'jenis_fisik'         => Arsip::terjemahkan('jenis_fisik', $row[17] ?? '-'),
                    'sifat'               => Arsip::terjemahkan('sifat', $row[18] ?? '-'),
                    'keterangan'          => Arsip::terjemahkan('keterangan', $row[19] ?? '-'),
                    'kondisi'             => Arsip::terjemahkan('kondisi', $row[20] ?? '-'),

                    // Data Teks / Angka Biasa
                    'judul_isi'           => $row[4],
                    'validator'           => $row[2] ?? '-',
                    'asal_berkas'         => $row[6] ?? '-',
                    'tahun'               => $row[7] ?? $lastTahun,
                    'retensi_aktif'       => $row[8] ?? '-',
                    'retensi_inaktif'     => $row[9] ?? '-',
                    'lokasi_ruangan'      => $row[10] ?? '-',
                    'tempat'              => $row[11] ?? '-',
                    'folder_boks'         => $row[12] ?? '-',

                    // ==========================================
                    // PERUBAHAN DI SINI SESUAI INSTRUKSI AI
                    // ==========================================
                    'klasifikasi_big'          => Arsip::terjemahkan('klasifikasi_big', $row[15] ?? '-'),
                    'klasifikasi_bakosurtanal' => Arsip::terjemahkan('klasifikasi_bakosurtanal', $row[16] ?? '-'),

                    'jumlah'              => $row[21] ?? '1',
                ]);
            }
        }
    }
}