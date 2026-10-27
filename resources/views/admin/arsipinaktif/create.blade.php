@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Data Arsip Inaktif</h1>
            <a href="{{ route('admin.arsip_inaktif.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Berkas
            </a>
        </div>

        <form action="{{ route('admin.arsip_inaktif.store') }}" method="POST">
            @csrf

            {{-- ===== BAGIAN 1: URAIAN INFORMASI BERKAS (INDUK) ===== --}}
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-folder-open mr-2"></i>Uraian Informasi Berkas (Data Induk)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Asal Unit Pengolah</label>
                                <input type="text" name="asal_unit_pengolah" class="form-control" placeholder="Misal: Balai Diklat">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. Berkas</label>
                                <input type="text" name="no_berkas" class="form-control" placeholder="Misal: 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kode Klasifikasi</label>
                                <input type="text" name="kode_klasifikasi" class="form-control" placeholder="Misal: HM.03">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Uraian Informasi Berkas <span class="text-danger">*</span></label>
                        <textarea name="uraian_informasi_berkas" class="form-control" rows="2" required placeholder="Judul / topik utama berkas..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kurun Waktu</label>
                                <input type="text" name="kurun_waktu" class="form-control" placeholder="Misal: 2008-2010">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Fisik Berkas</label>
                                <input type="text" name="jumlah_fisik_berkas" class="form-control" placeholder="Misal: 1 berkas">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN 2: URAIAN INFORMASI ARSIP (DETAIL ITEM) ===== --}}
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-file-alt mr-2"></i>Uraian Informasi Arsip (Detail Item)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Item Arsip</label>
                                <input type="text" name="no_item_arsip" class="form-control" placeholder="Misal: 1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Dokumen</label>
                                <input type="text" name="tanggal" class="form-control" placeholder="Misal: 24 Des 2008">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah Fisik Item Arsip</label>
                                <input type="text" name="jumlah_fisik_item" class="form-control" placeholder="Misal: 1 berkas">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Uraian Informasi Arsip (Isi/Subjek Dokumen)</label>
                        <textarea name="uraian_informasi_arsip" class="form-control" rows="3" placeholder="Deskripsi lengkap isi arsip..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Fisik</label>
                                <select name="jenis_fisik" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Berkas">Berkas</option>
                                    <option value="Kertas">Kertas</option>
                                    <option value="Foto">Foto</option>
                                    <option value="CD/DVD">CD / DVD</option>
                                    <option value="Buku">Buku</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tingkat Perkembangan</label>
                                <select name="tingkat_perkembangan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Asli">Asli</option>
                                    <option value="Copy">Copy</option>
                                    <option value="Tembusan">Tembusan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kondisi</label>
                                <select name="kondisi" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Tidak Baik">Tidak Baik</option>
                                    <option value="Rusak">Rusak</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN 3: LOKASI SIMPAN & RETENSI ===== --}}
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi Simpan & Retensi Arsip</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Boks</label>
                                <input type="text" name="lokasi_boks" class="form-control" placeholder="Misal: Boks 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" class="form-control" placeholder="Misal: R.175.4">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Ruangan</label>
                                <input type="text" name="lokasi_ruangan" class="form-control" placeholder="Misal: Gedung L Lantai 2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Retensi Aktif (Tahun)</label>
                                <input type="text" name="retensi_aktif" class="form-control" placeholder="Misal: 2009">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Retensi Inaktif (Tahun)</label>
                                <input type="text" name="retensi_inaktif" class="form-control" placeholder="Misal: 2010">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN 4: KLASIFIKASI KEAMANAN & AKSES ===== --}}
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-header py-3 bg-secondary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-shield-alt mr-2"></i>Sistem Klasifikasi Keamanan & Hak Akses</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Klasifikasi Keamanan</label>
                                <select name="klasifikasi_keamanan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Biasa">Biasa</option>
                                    <option value="Terbatas">Terbatas</option>
                                    <option value="Rahasia">Rahasia</option>
                                    <option value="Sangat Rahasia">Sangat Rahasia</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kategori Arsip</label>
                                <select name="kategori_arsip" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Fasilitatif">Fasilitatif</option>
                                    <option value="Substantif">Substantif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tingkat Akses Publik</label>
                                <select name="tingkat_akses_publik" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Terbuka">Terbuka</option>
                                    <option value="Terbatas">Terbatas</option>
                                    <option value="Tertutup">Tertutup</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hak Akses</label>
                                <input type="text" name="hak_akses" class="form-control" placeholder="Misal: Eselon IV">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dasar Pertimbangan</label>
                        <input type="text" name="dasar_pertimbangan" class="form-control" placeholder="Misal: Tidak Memiliki dampak yang mengganggu kinerja BIG">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keterangan / Nasib Akhir</label>
                                <select name="keterangan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Musnah">Musnah</option>
                                    <option value="Permanen">Permanen</option>
                                    <option value="Dinilai Kembali">Dinilai Kembali</option>
                                    <option value="Sudah Cek Fisik">Sudah Cek Fisik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pemindahan</label>
                                <input type="text" name="tanggal_pemindahan" class="form-control" placeholder="Misal: 01-01-2025">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 text-right">
                <button type="reset" class="btn btn-warning text-white"><i class="fas fa-sync mr-1"></i> Reset Isi Form</button>
                <button type="submit" class="btn btn-primary ml-2"><i class="fas fa-save mr-1"></i> Simpan Arsip Inaktif</button>
            </div>
        </form>
    </div>
@endsection
