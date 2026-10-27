<?php

namespace App\Imports;

use App\Models\ArsipVital;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

class ArsipVitalImport
{
    /**
     * Import file XLSX atau CSV Arsip Vital
     */
    public static function importFile(string $filePath, string $extension): int
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $ext = strtolower($extension);
        $imported = 0;
        $batch = [];

        if ($ext === 'csv' || $ext === 'txt') {
            $f = fopen($filePath, 'r');
            if (!$f) {
                throw new \Exception("Gagal membuka file CSV.");
            }

            $rowNum = 0;
            $lastBerkas = '-';
            $lastJenis = '-';
            $lastNoBerkas = '-';
            $lastPencipta = '-';
            $lastAsal = '-';
            $itemCounter = 0;

            while (($row = fgetcsv($f)) !== false) {
                $rowNum++;
                if ($rowNum < 3) continue;

                $uraianBerkas = trim((string)($row[3] ?? ($row[1] ?? '')));
                $uraianArsip  = trim((string)($row[6] ?? ($row[5] ?? '')));
                $rawNoItem    = trim((string)($row[5] ?? ($row[4] ?? '')));

                if ($uraianArsip === '' && $uraianBerkas === '') continue;

                if ($uraianBerkas !== '') {
                    $lastBerkas = $uraianBerkas;
                    $lastJenis = trim((string)($row[1] ?? '-')) ?: '-';
                    $lastNoBerkas = trim((string)($row[2] ?? '-')) ?: '-';
                    $lastPencipta = trim((string)($row[7] ?? ($row[6] ?? '-'))) ?: '-';
                    $lastAsal = trim((string)($row[9] ?? ($row[7] ?? '-'))) ?: '-';
                    $itemCounter = 0;
                }

                if ($uraianArsip === '') continue;

                if (is_numeric($rawNoItem)) {
                    $itemCounter = (int)$rawNoItem;
                    $finalNoItem = (string)$itemCounter;
                } else {
                    $itemCounter++;
                    $finalNoItem = (string)$itemCounter;
                }

                $batch[] = [
                    'jenis_arsip'             => $lastJenis,
                    'no_berkas'               => $lastNoBerkas,
                    'uraian_informasi_berkas' => $lastBerkas,
                    'validator'               => trim((string)($row[4] ?? ($row[3] ?? '-'))) ?: '-',
                    'pencipta'                => $lastPencipta,
                    'asal_berkas'             => $lastAsal,
                    'kurun_waktu'             => trim((string)($row[12] ?? ($row[10] ?? '-'))) ?: '-',

                    'no_item_arsip'           => $finalNoItem,
                    'uraian_informasi_arsip'  => $uraianArsip,
                    'tanggal'                 => trim((string)($row[10] ?? ($row[8] ?? '-'))) ?: '-',
                    'jumlah_lembar'           => trim((string)($row[11] ?? ($row[9] ?? '-'))) ?: '-',

                    'retensi_aktif'           => trim((string)($row[13] ?? ($row[11] ?? '-'))) ?: '-',
                    'retensi_inaktif'         => trim((string)($row[14] ?? ($row[12] ?? '-'))) ?: '-',

                    'lokasi_ruangan'          => trim((string)($row[15] ?? ($row[13] ?? '-'))) ?: '-',
                    'lokasi_lemari'           => trim((string)($row[16] ?? ($row[14] ?? '-'))) ?: '-',
                    'lokasi_boks'             => trim((string)($row[8] ?? ($row[15] ?? '-'))) ?: '-',

                    'tingkat_klasifikasi'     => trim((string)($row[17] ?? ($row[16] ?? '-'))) ?: '-',
                    'jenis_klasifikasi'       => trim((string)($row[18] ?? ($row[17] ?? '-'))) ?: '-',
                    'kode_klasifikasi'        => trim((string)($row[19] ?? ($row[18] ?? '-'))) ?: '-',

                    'jenis_fisik'             => trim((string)($row[20] ?? ($row[19] ?? '-'))) ?: '-',
                    'sifat'                   => trim((string)($row[21] ?? ($row[20] ?? '-'))) ?: '-',
                    'keterangan'              => trim((string)($row[22] ?? ($row[21] ?? '-'))) ?: '-',

                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                $imported++;
                if (count($batch) >= 500) {
                    DB::table('arsip_vitals')->insert($batch);
                    $batch = [];
                }
            }
            fclose($f);
        } else {
            // XLSX
            $reader = new XlsxReader();
            $reader->open($filePath);

            foreach ($reader->getSheetIterator() as $sheet) {
                $sheetName = $sheet->getName();
                $rowNum = 0;
                $lastBerkas = '-';
                $lastJenis = '-';
                $lastNoBerkas = '-';
                $lastPencipta = '-';
                $lastAsal = '-';
                $itemCounter = 0;
                $consecutiveEmpty = 0;

                // Tentukan letak kolom berdasarkan header di baris 2
                $colMap = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $rowNum++;
                    $cells = $row->toArray();

                    if ($rowNum === 2) {
                        // Baca header untuk dynamic mapping
                        foreach ($cells as $idx => $headerText) {
                            $h = strtolower(trim((string)$headerText));
                            if (str_contains($h, 'judul berkas') || str_contains($h, 'uraian berkas')) $colMap['berkas'] = $idx;
                            elseif (str_contains($h, 'no berkas') || str_contains($h, 'nomor berkas')) $colMap['no_berkas'] = $idx;
                            elseif (str_contains($h, 'jenis arsip')) $colMap['jenis'] = $idx;
                            elseif (str_contains($h, 'validator')) $colMap['validator'] = $idx;
                            elseif (str_contains($h, 'no isi') || str_contains($h, 'no item')) $colMap['no_item'] = $idx;
                            elseif (str_contains($h, 'judul isi') || str_contains($h, 'uraian informasi')) $colMap['uraian_arsip'] = $idx;
                            elseif (str_contains($h, 'pencipta')) $colMap['pencipta'] = $idx;
                            elseif (str_contains($h, 'asal berkas')) $colMap['asal'] = $idx;
                            elseif (str_contains($h, 'tanggal')) $colMap['tanggal'] = $idx;
                            elseif (str_contains($h, 'jumlah') || str_contains($h, 'lembar')) $colMap['jumlah'] = $idx;
                            elseif (str_contains($h, 'kurun') || str_contains($h, 'tahun')) $colMap['tahun'] = $idx;
                            elseif (str_contains($h, 'retensi aktif')) $colMap['ret_aktif'] = $idx;
                            elseif (str_contains($h, 'retensi inaktif')) $colMap['ret_inaktif'] = $idx;
                            elseif (str_contains($h, 'ruangan')) $colMap['ruangan'] = $idx;
                            elseif (str_contains($h, 'lemari')) $colMap['lemari'] = $idx;
                            elseif (str_contains($h, 'folder') || str_contains($h, 'boks')) $colMap['boks'] = $idx;
                            elseif (str_contains($h, 'tingkat klasifikasi') || str_contains($h, 'hak akses')) $colMap['hak_akses'] = $idx;
                            elseif (str_contains($h, 'jenis klasifikasi')) $colMap['jenis_klas'] = $idx;
                            elseif (str_contains($h, 'klasifikasi')) $colMap['kode_klas'] = $idx;
                            elseif (str_contains($h, 'jenis fisik')) $colMap['jenis_fisik'] = $idx;
                            elseif (str_contains($h, 'sifat')) $colMap['sifat'] = $idx;
                            elseif (str_contains($h, 'keterangan') || str_contains($h, 'ket')) $colMap['ket'] = $idx;
                        }
                        continue;
                    }

                    if ($rowNum < 3) continue;

                    $idxBerkas = $colMap['berkas'] ?? 1;
                    $idxArsip  = $colMap['uraian_arsip'] ?? 5;
                    $idxNoItem = $colMap['no_item'] ?? 4;

                    $uraianBerkas = trim((string)($cells[$idxBerkas] ?? ''));
                    $uraianArsip  = trim((string)($cells[$idxArsip] ?? ''));
                    $rawNoItem    = trim((string)($cells[$idxNoItem] ?? ''));

                    if ($uraianArsip === '' && $uraianBerkas === '' && $rawNoItem === '') {
                        $consecutiveEmpty++;
                        if ($consecutiveEmpty > 50 && $rowNum > 15) break;
                        continue;
                    }
                    $consecutiveEmpty = 0;

                    if ($uraianBerkas !== '') {
                        $lastBerkas = $uraianBerkas;
                        $lastJenis = trim((string)($cells[$colMap['jenis'] ?? 1] ?? ($cells[1] ?? '-'))) ?: '-';
                        $lastNoBerkas = trim((string)($cells[$colMap['no_berkas'] ?? 2] ?? '-')) ?: '-';
                        $lastPencipta = trim((string)($cells[$colMap['pencipta'] ?? 6] ?? '-')) ?: '-';
                        $lastAsal = trim((string)($cells[$colMap['asal'] ?? 7] ?? '-')) ?: '-';
                        $itemCounter = 0;
                    }

                    if ($uraianArsip === '') continue;

                    if (is_numeric($rawNoItem)) {
                        $itemCounter = (int)$rawNoItem;
                        $finalNoItem = (string)$itemCounter;
                    } else {
                        $itemCounter++;
                        $finalNoItem = (string)$itemCounter;
                    }

                    $rawTgl = $cells[$colMap['tanggal'] ?? 8] ?? '-';
                    if ($rawTgl instanceof \DateTimeInterface) {
                        $rawTgl = $rawTgl->format('d/m/Y');
                    } else {
                        $rawTgl = trim((string)$rawTgl) ?: '-';
                    }

                    $batch[] = [
                        'jenis_arsip'             => $lastJenis,
                        'no_berkas'               => $lastNoBerkas,
                        'uraian_informasi_berkas' => $lastBerkas,
                        'validator'               => trim((string)($cells[$colMap['validator'] ?? 2] ?? '-')) ?: '-',
                        'pencipta'                => $lastPencipta,
                        'asal_berkas'             => $lastAsal,
                        'kurun_waktu'             => trim((string)($cells[$colMap['tahun'] ?? 10] ?? '-')) ?: '-',

                        'no_item_arsip'           => $finalNoItem,
                        'uraian_informasi_arsip'  => $uraianArsip,
                        'tanggal'                 => $rawTgl,
                        'jumlah_lembar'           => trim((string)($cells[$colMap['jumlah'] ?? 9] ?? '-')) ?: '-',

                        'retensi_aktif'           => trim((string)($cells[$colMap['ret_aktif'] ?? 11] ?? '-')) ?: '-',
                        'retensi_inaktif'         => trim((string)($cells[$colMap['ret_inaktif'] ?? 12] ?? '-')) ?: '-',

                        'lokasi_ruangan'          => trim((string)($cells[$colMap['ruangan'] ?? 13] ?? '-')) ?: '-',
                        'lokasi_lemari'           => trim((string)($cells[$colMap['lemari'] ?? 14] ?? '-')) ?: '-',
                        'lokasi_boks'             => trim((string)($cells[$colMap['boks'] ?? 15] ?? '-')) ?: '-',

                        'tingkat_klasifikasi'     => trim((string)($cells[$colMap['hak_akses'] ?? 16] ?? '-')) ?: '-',
                        'jenis_klasifikasi'       => trim((string)($cells[$colMap['jenis_klas'] ?? 17] ?? '-')) ?: '-',
                        'kode_klasifikasi'        => trim((string)($cells[$colMap['kode_klas'] ?? 18] ?? '-')) ?: '-',

                        'jenis_fisik'             => trim((string)($cells[$colMap['jenis_fisik'] ?? 19] ?? '-')) ?: '-',
                        'sifat'                   => trim((string)($cells[$colMap['sifat'] ?? 20] ?? '-')) ?: '-',
                        'keterangan'              => trim((string)($cells[$colMap['ket'] ?? 21] ?? '-')) ?: '-',

                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ];

                    $imported++;
                    if (count($batch) >= 500) {
                        DB::table('arsip_vitals')->insert($batch);
                        $batch = [];
                    }
                }
            }
            $reader->close();
        }

        if (!empty($batch)) {
            DB::table('arsip_vitals')->insert($batch);
        }

        return $imported;
    }
}
