<?php

namespace App\Imports;

use App\Models\ArsipTerjaga;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

class ArsipTerjagaImport
{
    /**
     * Import file XLSX atau CSV Arsip Terjaga
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
            $lastKlasifikasi = '-';
            $lastAsal = '-';
            $lastPencipta = '-';
            $itemCounter = 0;

            while (($row = fgetcsv($f)) !== false) {
                $rowNum++;
                if ($rowNum < 4) continue;

                $uraianBerkas = trim((string)($row[3] ?? ''));
                $uraianArsip  = trim((string)($row[5] ?? ''));
                $rawNoItem    = trim((string)($row[4] ?? ''));

                if ($uraianArsip === '' && $uraianBerkas === '') continue;

                if ($uraianBerkas !== '') {
                    $lastBerkas = $uraianBerkas;
                    $lastKlasifikasi = trim((string)($row[1] ?? '-')) ?: '-';
                    $lastPencipta = trim((string)($row[6] ?? '-')) ?: '-';
                    $lastAsal = trim((string)($row[7] ?? '-')) ?: '-';
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
                    'klasifikasi_arsip'       => $lastKlasifikasi,
                    'no_berkas'               => trim((string)($row[2] ?? '-')) ?: '-',
                    'uraian_informasi_berkas' => $lastBerkas,
                    'pencipta'                => $lastPencipta,
                    'asal_berkas'             => $lastAsal,
                    'kurun_waktu'             => trim((string)($row[10] ?? '-')) ?: '-',
                    'jumlah_isi_berkas'       => trim((string)($row[8] ?? '-')) ?: '-',
                    'validator'               => '-',

                    'no_item_arsip'           => $finalNoItem,
                    'uraian_informasi_arsip'  => $uraianArsip,
                    'tanggal'                 => trim((string)($row[9] ?? '-')) ?: '-',
                    'jenis_fisik'             => trim((string)($row[11] ?? '-')) ?: '-',
                    'tingkat_perkembangan'    => trim((string)($row[12] ?? '-')) ?: '-',
                    'kondisi'                 => trim((string)($row[13] ?? '-')) ?: '-',

                    'lokasi_ruangan'          => trim((string)($row[14] ?? '-')) ?: '-',
                    'lokasi_rak'              => trim((string)($row[15] ?? '-')) ?: '-',
                    'lokasi_boks'             => trim((string)($row[16] ?? '-')) ?: '-',

                    'retensi_aktif'           => trim((string)($row[17] ?? '-')) ?: '-',
                    'retensi_inaktif'         => trim((string)($row[18] ?? '-')) ?: '-',
                    'uraian_retensi_aktif'    => trim((string)($row[19] ?? '-')) ?: '-',
                    'uraian_retensi_inaktif'  => trim((string)($row[20] ?? '-')) ?: '-',

                    'klasifikasi_keamanan'    => trim((string)($row[21] ?? '-')) ?: '-',
                    'kategori_arsip'          => trim((string)($row[22] ?? '-')) ?: '-',
                    'tingkat_akses_publik'    => trim((string)($row[23] ?? '-')) ?: '-',
                    'hak_akses'               => trim((string)($row[24] ?? '-')) ?: '-',
                    'dasar_pertimbangan'      => trim((string)($row[25] ?? '-')) ?: '-',
                    'metode_penyimpanan'      => trim((string)($row[26] ?? '-')) ?: '-',
                    'keterangan'              => trim((string)($row[27] ?? '-')) ?: '-',

                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                $imported++;
                if (count($batch) >= 500) {
                    DB::table('arsip_terjagas')->insert($batch);
                    $batch = [];
                }
            }
            fclose($f);
        } else {
            // XLSX reader
            $reader = new XlsxReader();
            $reader->open($filePath);

            $hasGabungan = false;
            foreach ($reader->getSheetIterator() as $sheet) {
                if (stripos($sheet->getName(), 'gabungan') !== false) {
                    $hasGabungan = true;
                    break;
                }
            }

            foreach ($reader->getSheetIterator() as $sheet) {
                $sheetName = $sheet->getName();
                if ($hasGabungan && stripos($sheetName, 'gabungan') === false) {
                    continue; // Jika ada sheet GABUNGAN, cukup baca sheet GABUNGAN
                }
                if (stripos($sheetName, 'sheet') !== false && !preg_match('/[A-Za-z0-9]/', str_replace('Sheet', '', $sheetName))) {
                    continue; // Skip blank sheet1 default jika ada
                }

                $rowNum = 0;
                $lastBerkas = '-';
                $lastKlasifikasi = '-';
                $lastAsal = '-';
                $lastPencipta = '-';
                $itemCounter = 0;
                $consecutiveEmpty = 0;

                // Tentukan format sheet
                $isGabunganSheet = stripos($sheetName, 'gabungan') !== false;

                foreach ($sheet->getRowIterator() as $row) {
                    $rowNum++;
                    $cells = $row->toArray();

                    if ($isGabunganSheet) {
                        // Data dimulai pada baris ke-6 di sheet GABUNGAN
                        if ($rowNum < 6) continue;

                        $uraianBerkas = trim((string)($cells[3] ?? ''));
                        $uraianArsip  = trim((string)($cells[5] ?? ''));
                        $rawNoItem    = trim((string)($cells[4] ?? ''));

                        if ($uraianArsip === '' && $uraianBerkas === '' && $rawNoItem === '') {
                            $consecutiveEmpty++;
                            if ($consecutiveEmpty > 50 && $rowNum > 20) break;
                            continue;
                        }
                        $consecutiveEmpty = 0;

                        if ($uraianBerkas !== '') {
                            $lastBerkas = $uraianBerkas;
                            $lastKlasifikasi = trim((string)($cells[1] ?? '-')) ?: '-';
                            $lastPencipta = trim((string)($cells[6] ?? '-')) ?: '-';
                            $lastAsal = trim((string)($cells[7] ?? '-')) ?: '-';
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

                        $rawTgl = $cells[9] ?? '-';
                        if ($rawTgl instanceof \DateTimeInterface) {
                            $rawTgl = $rawTgl->format('d/m/Y');
                        } else {
                            $rawTgl = trim((string)$rawTgl) ?: '-';
                        }

                        $batch[] = [
                            'klasifikasi_arsip'       => $lastKlasifikasi,
                            'no_berkas'               => trim((string)($cells[2] ?? '-')) ?: '-',
                            'uraian_informasi_berkas' => $lastBerkas,
                            'pencipta'                => $lastPencipta,
                            'asal_berkas'             => $lastAsal,
                            'kurun_waktu'             => trim((string)($cells[10] ?? '-')) ?: '-',
                            'jumlah_isi_berkas'       => trim((string)($cells[8] ?? '-')) ?: '-',
                            'validator'               => '-',

                            'no_item_arsip'           => $finalNoItem,
                            'uraian_informasi_arsip'  => $uraianArsip,
                            'tanggal'                 => $rawTgl,
                            'jenis_fisik'             => trim((string)($cells[11] ?? '-')) ?: '-',
                            'tingkat_perkembangan'    => trim((string)($cells[12] ?? '-')) ?: '-',
                            'kondisi'                 => trim((string)($cells[13] ?? '-')) ?: '-',

                            'lokasi_ruangan'          => trim((string)($cells[14] ?? '-')) ?: '-',
                            'lokasi_rak'              => trim((string)($cells[15] ?? '-')) ?: '-',
                            'lokasi_boks'             => trim((string)($cells[16] ?? '-')) ?: '-',

                            'retensi_aktif'           => trim((string)($cells[17] ?? '-')) ?: '-',
                            'retensi_inaktif'         => trim((string)($cells[18] ?? '-')) ?: '-',
                            'uraian_retensi_aktif'    => trim((string)($cells[19] ?? '-')) ?: '-',
                            'uraian_retensi_inaktif'  => trim((string)($cells[20] ?? '-')) ?: '-',

                            'klasifikasi_keamanan'    => trim((string)($cells[21] ?? '-')) ?: '-',
                            'kategori_arsip'          => trim((string)($cells[22] ?? '-')) ?: '-',
                            'tingkat_akses_publik'    => trim((string)($cells[23] ?? '-')) ?: '-',
                            'hak_akses'               => trim((string)($cells[24] ?? '-')) ?: '-',
                            'dasar_pertimbangan'      => trim((string)($cells[25] ?? '-')) ?: '-',
                            'metode_penyimpanan'      => trim((string)($cells[26] ?? '-')) ?: '-',
                            'keterangan'              => trim((string)($cells[27] ?? '-')) ?: '-',

                            'created_at'              => now(),
                            'updated_at'              => now(),
                        ];
                    } else {
                        // Sheet Per-Negara (RI-Malaysia, RI-Vietnam, RI-India, RI-PNG, dll)
                        if ($rowNum < 5) continue;

                        $uraianBerkas = trim((string)($cells[6] ?? ''));
                        $uraianArsip  = trim((string)($cells[8] ?? ''));
                        $rawNoItem    = trim((string)($cells[7] ?? ''));

                        if ($uraianArsip === '' && $uraianBerkas === '' && $rawNoItem === '') {
                            $consecutiveEmpty++;
                            if ($consecutiveEmpty > 50 && $rowNum > 15) break;
                            continue;
                        }
                        $consecutiveEmpty = 0;

                        if ($uraianBerkas !== '') {
                            $lastBerkas = $uraianBerkas;
                            $lastKlasifikasi = trim((string)($cells[2] ?? '-')) ?: '-';
                            $lastAsal = trim((string)($cells[3] ?? '-')) ?: '-';
                            $lastPencipta = trim((string)($cells[4] ?? '-')) ?: '-';
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

                        $rawTgl = $cells[10] ?? '-';
                        if ($rawTgl instanceof \DateTimeInterface) {
                            $rawTgl = $rawTgl->format('d/m/Y');
                        } else {
                            $rawTgl = trim((string)$rawTgl) ?: '-';
                        }

                        $batch[] = [
                            'klasifikasi_arsip'       => $lastKlasifikasi,
                            'no_berkas'               => trim((string)($cells[5] ?? '-')) ?: '-',
                            'uraian_informasi_berkas' => $lastBerkas,
                            'pencipta'                => $lastPencipta,
                            'asal_berkas'             => $lastAsal,
                            'kurun_waktu'             => trim((string)($cells[11] ?? '-')) ?: '-',
                            'jumlah_isi_berkas'       => trim((string)($cells[9] ?? '-')) ?: '-',
                            'validator'               => trim((string)($cells[1] ?? '-')) ?: '-',

                            'no_item_arsip'           => $finalNoItem,
                            'uraian_informasi_arsip'  => $uraianArsip,
                            'tanggal'                 => $rawTgl,
                            'jenis_fisik'             => trim((string)($cells[12] ?? '-')) ?: '-',
                            'tingkat_perkembangan'    => trim((string)($cells[13] ?? '-')) ?: '-',
                            'kondisi'                 => trim((string)($cells[14] ?? '-')) ?: '-',

                            'lokasi_ruangan'          => trim((string)($cells[15] ?? '-')) ?: '-',
                            'lokasi_rak'              => trim((string)($cells[16] ?? '-')) ?: '-',
                            'lokasi_boks'             => trim((string)($cells[17] ?? '-')) ?: '-',

                            'retensi_aktif'           => '-',
                            'retensi_inaktif'         => '-',
                            'uraian_retensi_aktif'    => '-',
                            'uraian_retensi_inaktif'  => '-',
                            'klasifikasi_keamanan'    => 'Terbatas',
                            'kategori_arsip'          => 'Substantif',
                            'tingkat_akses_publik'    => 'Terbatas',
                            'hak_akses'               => 'Eselon III',
                            'dasar_pertimbangan'      => '-',
                            'metode_penyimpanan'      => 'Vaulting',
                            'keterangan'              => '-',

                            'created_at'              => now(),
                            'updated_at'              => now(),
                        ];
                    }

                    $imported++;
                    if (count($batch) >= 500) {
                        DB::table('arsip_terjagas')->insert($batch);
                        $batch = [];
                    }
                }
            }
            $reader->close();
        }

        if (!empty($batch)) {
            DB::table('arsip_terjagas')->insert($batch);
        }

        return $imported;
    }
}
