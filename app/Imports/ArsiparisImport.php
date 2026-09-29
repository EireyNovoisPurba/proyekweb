<?php

namespace App\Imports;

use App\Models\Arsiparis;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ArsiparisImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama = !empty($row[3]) ? trim($row[3]) : null;
            if (empty($nama)) {
                continue;
            }

            // ========================================================
            // PENGECUALIAN: Abaikan baris penjelasan / Kategori Excel
            // ========================================================
            $penjelasan = ['Arsiparis Terampil', 'PPPK Paruh Waktu', 'Pengelola Arsip Non ASN', 'Arsiparis Keahlian'];
            foreach ($penjelasan as $teks) {
                // Jika isi kolom nama mengandung kata-kata di atas, langsung lewati (jangan dimasukkan ke database)
                if (stripos($nama, $teks) !== false) {
                    continue 2;
                }
            }

            // Jika NIP kosong, beri NIP sistem agar tidak bentrok (Nanti di layar akan kita ubah jadi strip "-")
            $nip = !empty($row[4]) ? trim($row[4]) : 'NONASN-' . rand(1000, 9999);

            Arsiparis::updateOrCreate(
                ['nip' => $nip],
                [
                    'nama'       => $nama,
                    'pangkat'    => !empty($row[5]) ? trim($row[5]) : '-',
                    'golongan'   => !empty($row[6]) ? trim($row[6]) : '-',
                    'pendidikan' => !empty($row[7]) ? trim($row[7]) : '-',
                    'jabatan'    => !empty($row[8]) ? trim($row[8]) : '-',
                    'unit_kerja' => !empty($row[9]) ? trim($row[9]) : '-',
                ]
            );
        }
    }
}