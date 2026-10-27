<?php
namespace App\Exports;

use App\Models\ArsipInaktif;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArsipInaktifExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ArsipInaktif::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'Asal Unit', 'No Berkas', 'Kode Klasifikasi', 'Uraian Informasi Berkas',
            'Kurun Waktu', 'Jumlah Berkas', 'No Item', 'Uraian Informasi Arsip',
            'Tanggal Dokumen', 'Jumlah Item', 'Kondisi', 'Jenis Fisik',
            'Folder / Boks', 'Tempat (Rak)', 'Lokasi Ruangan', 'Retensi Aktif',
            'Retensi Inaktif', 'Tingkat Akses Publik', 'Keterangan',
            'Tanggal Pemindahan', 'Dasar Pertimbangan'
        ];
    }

    public function map($arsip): array
    {
        return [
            $arsip->id,
            $arsip->asal_unit,
            $arsip->no_berkas,
            $arsip->kode_klasifikasi,
            $arsip->uraian_informasi_berkas,
            $arsip->kurun_waktu,
            $arsip->jumlah_berkas,
            $arsip->no_item,
            $arsip->uraian_informasi_arsip,
            $arsip->tanggal_dokumen,
            $arsip->jumlah_item,
            $arsip->kondisi,
            $arsip->jenis_fisik,
            $arsip->folder_boks,
            $arsip->tempat,
            $arsip->lokasi_ruangan,
            $arsip->retensi_aktif,
            $arsip->retensi_inaktif,
            $arsip->tingkat_akses_publik,
            $arsip->keterangan,
            $arsip->tanggal_pemindahan,
            $arsip->dasar_pertimbangan
        ];
    }
}