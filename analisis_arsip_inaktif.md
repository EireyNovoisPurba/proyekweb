# Analisis Sistem Arsip Inaktif — SDM Kearsipan

## 📋 Hasil Baca CSV

### 1. DAFTAR ARSIP INAKTIF (Sheet: GABUNGAN SD JUNI 2026)
Header baris 3 (index 0-based):
| Index | Nama Kolom | Field DB |
|-------|-----------|----------|
| 0 | No | - |
| 1 | Asal Unit Pengolah | asal_unit_pengolah |
| 2 | No. Berkas | no_berkas |
| 3 | Kode Klasifikasi | kode_klasifikasi |
| 4 | Uraian Informasi Berkas | uraian_informasi_berkas |
| 5 | Kurun Waktu | kurun_waktu |
| 6 | Jumlah Fisik Berkas | jumlah_fisik_berkas |
| 7 | No. Item Arsip | no_item_arsip |
| 8 | Uraian Informasi Arsip | uraian_informasi_arsip |
| 9 | Tanggal | tanggal |
| 10 | Jumlah Fisik Item Arsip | jumlah_fisik_item |
| 11 | Jenis Fisik | jenis_fisik |
| 12 | Tingkat Perkembangan | tingkat_perkembangan |
| 13 | Kondisi | kondisi |
| 14 | Boks (Lokasi Simpan) | lokasi_boks |
| 15 | Rak | lokasi_rak |
| 16 | Ruangan | lokasi_ruangan |
| 17 | Aktif (Retensi) | retensi_aktif |
| 18 | Inaktif (Retensi) | retensi_inaktif |
| 19 | Klasifikasi Keamanan | klasifikasi_keamanan |
| 20 | Kategori Arsip | kategori_arsip |
| 21 | Tingkat Akses Publik | tingkat_akses_publik |
| 22 | Hak Akses | hak_akses |
| 23 | Dasar Pertimbangan | dasar_pertimbangan |
| 24 | Keterangan | keterangan |
| 25 | Tanggal Pemindahan | tanggal_pemindahan |

**Catatan**: Import sudah benar! startRow=3, index 0-25, field $lastUnit, dll

### 2. DAFTAR ARSIP TERJAGA (Sheet: GABUNGAN V1)
Header di baris 4, sub-header baris 5:
| Index | Nama Kolom | Keterangan |
|-------|-----------|-----------|
| 0 | No. | Nomor urut |
| 1 | Klasifikasi Arsip | Kode klasifikasi |
| 2 | No. Berkas | Nomor berkas |
| 3 | Uraian Informasi Berkas | INDUK |
| 4 | No.Item Arsip | Nomor item |
| 5 | Uraian Informasi Arsip | DETAIL |
| 6 | Pencipta | |
| 7 | Asal Berkas | |
| 8 | Jumlah Isi Berkas | |
| 9 | Tanggal | |
| 10 | Kurun Waktu | |
| 11 | Jenis Fisik | |
| 12 | Tk. Perkembangan | |
| 13 | Kondisi | |
| 14 | Lokasi Simpan - Ruangan | |
| 15 | Lokasi Simpan - Rak | |
| 16 | Lokasi Simpan - Boks | |
| 17 | Jadwal Retensi Arsip - Aktif | |
| 18 | Jadwal Retensi Arsip - Inaktif | |
| 19 | Uraian Aktif | |
| 20 | Uraian Inaktif | |
| 21 | Klasifikasi Keamanan | |
| 22 | Kategori Arsip | |
| 23 | Tingkat Akses Publik | |
| 24 | Hak Akses | |
| 25 | Dasar Pertimbangan | |
| 26 | Metode Penyimpanan | |
| 27 | Ket. | Keterangan |

### 3. DAFTAR ARSIP VITAL (Sheet: vital)
Header di baris 3:
| Index | Nama Kolom | Keterangan |
|-------|-----------|-----------|
| 0 | No | |
| 1 | Judul Berkas | INDUK |
| 2 | Validator | |
| 3 | Jenis Arsip | |
| 4 | No Isi Berkas | |
| 5 | Judul Isi | DETAIL |
| 6 | Pencipta | |
| 7 | Asal Berkas | |
| 8 | Tanggal | |
| 9 | Jumlah/Lembar | |
| 10 | Tahun | |
| 11 | Retensi Aktif | |
| 12 | Retensi Inaktif | |
| 13 | Lokasi Ruangan | |
| 14 | Lemari | |
| 15 | Folder/Boks | |
| 16 | Tingkat Klasifikasi | |
| 17 | Jenis Klasifikasi | |
| 18 | Klasifikasi | Kode klasifikasi |
| 19 | Jenis Fisik | |
| 20 | Sifat | |
| 21 | Keterangan | |
| 22 | Kondisi | |
| 23 | Metode Perlindungan | |

## 🎯 Rencana Perubahan Arsip Inaktif

### Perubahan Navbar / Sidebar
Saat ini: "Arsip Inaktif" → 1 link langsung ke index (Uraian Berkas)
Menjadi: Dropdown "Arsip Inaktif" dengan 2 sub-menu:
- 📂 Uraian Informasi Berkas (index daftar berkas)
- 📄 Uraian Informasi Arsip (daftar semua item/detail arsip langsung)

### Perubahan Nama/Label
- "Daftar Berkas" → "Uraian Informasi Berkas"
- "Judul Isi" / "Item Arsip" → "Uraian Informasi Arsip"
- Heading di halaman disesuaikan

### Field yang perlu diperbaiki di Create/Edit
Saat ini field name di form tidak cocok dengan field DB:
- form: `asal_unit` → DB: `asal_unit_pengolah` ❌
- form: `no_item` → DB: `no_item_arsip` ❌  
- form: `tanggal_dokumen` → DB: `tanggal` ❌
- form: `jumlah_berkas` → DB: `jumlah_fisik_berkas` ❌
- form: `jumlah_item` → DB: `jumlah_fisik_item` ❌
- form: `folder_boks` → DB: `lokasi_boks` ❌
- form: `tempat` → DB: `lokasi_rak` ❌

### Rekomendasi Format Import: XLSX vs CSV
**XLSX lebih baik** karena:
1. Bisa multi-sheet (GABUNGAN, VITAL, TERJAGA dalam 1 file)
2. Format cell preserved (tanggal tidak error)
3. Sudah pakai library maatwebsite/excel yang support XLSX
4. Format resmi arsip pemerintah biasanya .xlsx

**Catatan**: File CSV bisa juga tapi harus single sheet dan encoding perlu diperhatikan.
