<?php

namespace App\Imports;

use App\Models\ArsipInaktif;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Reader\CSV\Reader as CsvReader;

class ArsipInaktifImport
{
    /**
     * Import file CSV atau XLSX secara streaming dan cepat (Batch Insert 1000)
     * Mengatasi formula Excel (=H7+1), membersihkan baris kosong, dan bebas timeout.
     */
    public static function importFile(string $filePath, string $extension): int
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $ext = strtolower($extension);
        $imported = 0;
        $batch = [];

        $lastUnit = '-';
        $lastNoBerkas = '-';
        $lastKode = '-';
        $lastUraianBerkas = '-';
        $lastKurun = '-';
        $lastJmlBerkas = '-';
        $itemCounter = 0;
        $consecutiveEmpty = 0;

        if ($ext === 'csv' || $ext === 'txt') {
            // High-speed native CSV streaming
            $f = fopen($filePath, 'r');
            if (!$f) {
                throw new \Exception("Gagal membuka file CSV.");
            }

            $rowNum = 0;
            while (($row = fgetcsv($f)) !== false) {
                $rowNum++;
                if ($rowNum < 7) {
                    continue; // Header 6 baris pertama
                }

                $uraianBerkas = trim((string)($row[4] ?? ''));
                $uraianArsip  = trim((string)($row[8] ?? ''));
                $rawNoItem    = trim((string)($row[7] ?? ''));

                // Deteksi baris kosong berurutan untuk berhenti dini (hemat CPU)
                if ($uraianArsip === '' && $uraianBerkas === '' && $rawNoItem === '') {
                    $consecutiveEmpty++;
                    if ($consecutiveEmpty > 80 && $rowNum > 100) {
                        break;
                    }
                    continue;
                }
                $consecutiveEmpty = 0;

                // Jika ada Uraian Berkas baru, perbarui data induk
                if ($uraianBerkas !== '') {
                    $lastUnit = trim((string)($row[1] ?? '-')) ?: '-';
                    $lastNoBerkas = trim((string)($row[2] ?? '-')) ?: '-';
                    $lastKode = trim((string)($row[3] ?? '-')) ?: '-';
                    $lastUraianBerkas = $uraianBerkas;
                    $lastKurun = trim((string)($row[5] ?? '-')) ?: '-';
                    $lastJmlBerkas = trim((string)($row[6] ?? '-')) ?: '-';
                    $itemCounter = 0;
                }

                // Abaikan jika tidak ada uraian arsip dan nomor item
                if ($uraianArsip === '' && $rawNoItem === '') {
                    continue;
                }

                // Hitung nomor urut item (atasi formula =H7+1)
                if (is_numeric($rawNoItem)) {
                    $itemCounter = (int)$rawNoItem;
                    $finalNoItem = (string)$itemCounter;
                } else {
                    $itemCounter++;
                    $finalNoItem = (string)$itemCounter;
                }

                // Sanitasi formula retensi
                $retensiAktif = self::sanitizeFormula(trim((string)($row[17] ?? '-')), $lastKurun);
                $retensiInaktif = self::sanitizeFormula(trim((string)($row[18] ?? '-')), $retensiAktif);

                $batch[] = [
                    'asal_unit_pengolah'      => $lastUnit,
                    'no_berkas'               => $lastNoBerkas,
                    'kode_klasifikasi'        => $lastKode,
                    'uraian_informasi_berkas' => $lastUraianBerkas,
                    'kurun_waktu'             => $lastKurun,
                    'jumlah_fisik_berkas'     => $lastJmlBerkas,

                    'no_item_arsip'           => $finalNoItem,
                    'uraian_informasi_arsip'  => $uraianArsip ?: '-',
                    'tanggal'                 => trim((string)($row[9] ?? '-')) ?: '-',
                    'jumlah_fisik_item'       => trim((string)($row[10] ?? '-')) ?: '-',
                    'jenis_fisik'             => trim((string)($row[11] ?? '-')) ?: '-',
                    'tingkat_perkembangan'    => trim((string)($row[12] ?? '-')) ?: '-',
                    'kondisi'                 => trim((string)($row[13] ?? '-')) ?: '-',
                    'lokasi_boks'             => trim((string)($row[14] ?? '-')) ?: '-',
                    'lokasi_rak'              => trim((string)($row[15] ?? '-')) ?: '-',
                    'lokasi_ruangan'          => trim((string)($row[16] ?? '-')) ?: '-',
                    'retensi_aktif'           => $retensiAktif ?: '-',
                    'retensi_inaktif'         => $retensiInaktif ?: '-',
                    'klasifikasi_keamanan'    => trim((string)($row[19] ?? '-')) ?: '-',
                    'kategori_arsip'          => trim((string)($row[20] ?? '-')) ?: '-',
                    'tingkat_akses_publik'    => trim((string)($row[21] ?? '-')) ?: '-',
                    'hak_akses'               => trim((string)($row[22] ?? '-')) ?: '-',
                    'dasar_pertimbangan'      => trim((string)($row[23] ?? '-')) ?: '-',
                    'keterangan'              => trim((string)($row[24] ?? '-')) ?: '-',
                    'tanggal_pemindahan'      => trim((string)($row[25] ?? '-')) ?: '-',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                $imported++;

                if (count($batch) >= 1000) {
                    DB::table('arsip_inaktifs')->insert($batch);
                    $batch = [];
                }
            }
            fclose($f);

        } else {
            // Streaming XLSX via OpenSpout
            $reader = new XlsxReader();
            $reader->open($filePath);

            $rowNum = 0;
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rowNum++;
                    if ($rowNum < 7) {
                        continue;
                    }

                    $cells = $row->toArray();
                    $uraianBerkas = trim((string)($cells[4] ?? ''));
                    $uraianArsip  = trim((string)($cells[8] ?? ''));
                    $rawNoItem    = trim((string)($cells[7] ?? ''));

                    // Deteksi baris kosong berurutan untuk berhenti
                    if ($uraianArsip === '' && $uraianBerkas === '' && $rawNoItem === '') {
                        $consecutiveEmpty++;
                        if ($consecutiveEmpty > 80 && $rowNum > 100) {
                            break;
                        }
                        continue;
                    }
                    $consecutiveEmpty = 0;

                    if ($uraianBerkas !== '') {
                        $lastUnit = trim((string)($cells[1] ?? '-')) ?: '-';
                        $lastNoBerkas = trim((string)($cells[2] ?? '-')) ?: '-';
                        $lastKode = trim((string)($cells[3] ?? '-')) ?: '-';
                        $lastUraianBerkas = $uraianBerkas;
                        $lastKurun = trim((string)($cells[5] ?? '-')) ?: '-';
                        $lastJmlBerkas = trim((string)($cells[6] ?? '-')) ?: '-';
                        $itemCounter = 0;
                    }

                    if ($uraianArsip === '' && $rawNoItem === '') {
                        continue;
                    }

                    if (is_numeric($rawNoItem)) {
                        $itemCounter = (int)$rawNoItem;
                        $finalNoItem = (string)$itemCounter;
                    } else {
                        $itemCounter++;
                        $finalNoItem = (string)$itemCounter;
                    }

                    // Format tanggal jika objek DateTime dari Excel
                    $rawTanggal = $cells[9] ?? '-';
                    if ($rawTanggal instanceof \DateTimeInterface) {
                        $rawTanggal = $rawTanggal->format('d/m/Y');
                    } else {
                        $rawTanggal = trim((string)$rawTanggal) ?: '-';
                    }

                    $retensiAktif = self::sanitizeFormula(trim((string)($cells[17] ?? '-')), $lastKurun);
                    $retensiInaktif = self::sanitizeFormula(trim((string)($cells[18] ?? '-')), $retensiAktif);

                    $batch[] = [
                        'asal_unit_pengolah'      => $lastUnit,
                        'no_berkas'               => $lastNoBerkas,
                        'kode_klasifikasi'        => $lastKode,
                        'uraian_informasi_berkas' => $lastUraianBerkas,
                        'kurun_waktu'             => $lastKurun,
                        'jumlah_fisik_berkas'     => $lastJmlBerkas,

                        'no_item_arsip'           => $finalNoItem,
                        'uraian_informasi_arsip'  => $uraianArsip ?: '-',
                        'tanggal'                 => $rawTanggal,
                        'jumlah_fisik_item'       => trim((string)($cells[10] ?? '-')) ?: '-',
                        'jenis_fisik'             => trim((string)($cells[11] ?? '-')) ?: '-',
                        'tingkat_perkembangan'    => trim((string)($cells[12] ?? '-')) ?: '-',
                        'kondisi'                 => trim((string)($cells[13] ?? '-')) ?: '-',
                        'lokasi_boks'             => trim((string)($cells[14] ?? '-')) ?: '-',
                        'lokasi_rak'              => trim((string)($cells[15] ?? '-')) ?: '-',
                        'lokasi_ruangan'          => trim((string)($cells[16] ?? '-')) ?: '-',
                        'retensi_aktif'           => $retensiAktif ?: '-',
                        'retensi_inaktif'         => $retensiInaktif ?: '-',
                        'klasifikasi_keamanan'    => trim((string)($cells[19] ?? '-')) ?: '-',
                        'kategori_arsip'          => trim((string)($cells[20] ?? '-')) ?: '-',
                        'tingkat_akses_publik'    => trim((string)($cells[21] ?? '-')) ?: '-',
                        'hak_akses'               => trim((string)($cells[22] ?? '-')) ?: '-',
                        'dasar_pertimbangan'      => trim((string)($cells[23] ?? '-')) ?: '-',
                        'keterangan'              => trim((string)($cells[24] ?? '-')) ?: '-',
                        'tanggal_pemindahan'      => trim((string)($cells[25] ?? '-')) ?: '-',
                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ];

                    $imported++;

                    if (count($batch) >= 1000) {
                        DB::table('arsip_inaktifs')->insert($batch);
                        $batch = [];
                    }
                }
                break; // Hanya baca sheet pertama
            }
            $reader->close();
        }

        // Simpan sisa batch terakhir
        if (!empty($batch)) {
            DB::table('arsip_inaktifs')->insert($batch);
        }

        return $imported;
    }

    /**
     * Sanitasi formula Excel (=H7+1, =F1507+2) menjadi angka atau nilai bersih
     */
    protected static function sanitizeFormula(string $val, ?string $baseYear = null): string
    {
        if ($val === '' || $val === '-') {
            return '-';
        }

        if (str_starts_with($val, '=')) {
            if (preg_match('/\+\s*(\d+)/', $val, $m) && is_numeric($baseYear)) {
                return (string)((int)$baseYear + (int)$m[1]);
            }
            return '-';
        }

        return $val;
    }
}
