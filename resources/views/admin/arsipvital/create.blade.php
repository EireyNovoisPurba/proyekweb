@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-plus-circle text-warning mr-2"></i>Tambah Arsip Vital
            </h1>
            <a href="{{ route('admin.arsip_vital.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.arsip_vital.store') }}" method="POST">
            @csrf

            <!-- Bagian 1: Berkas Induk -->
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-folder mr-2"></i>Data Berkas Induk</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Uraian Informasi Berkas (Judul Berkas) <span class="text-danger">*</span></label>
                        <input type="text" name="uraian_informasi_berkas" class="form-control" 
                            value="{{ old('uraian_informasi_berkas', $berkasDefault) }}" required placeholder="Contoh: Pemindahtanganan BMN">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Arsip</label>
                                <input type="text" name="jenis_arsip" class="form-control" placeholder="Contoh: Kerugian Negara, BMN">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. Berkas</label>
                                <input type="text" name="no_berkas" class="form-control" placeholder="Contoh: 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Kurun Waktu / Tahun</label>
                                <input type="text" name="kurun_waktu" class="form-control" placeholder="Contoh: 2019">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Asal Berkas</label>
                                <input type="text" name="asal_berkas" class="form-control" placeholder="Contoh: Biro Umum dan Keuangan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Pencipta</label>
                                <input type="text" name="pencipta" class="form-control" placeholder="Contoh: Badan Informasi Geospasial">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Item Dokumen -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-file-alt mr-2"></i>Data Item Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. Item Arsip</label>
                                <input type="text" name="no_item_arsip" class="form-control" placeholder="Contoh: 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Dokumen</label>
                                <input type="text" name="tanggal" class="form-control" placeholder="Contoh: 25 Maret 2019">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah Lembar</label>
                                <input type="text" name="jumlah_lembar" class="form-control" placeholder="Contoh: 65 Lembar">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Uraian Informasi Arsip (Isi Dokumen) <span class="text-danger">*</span></label>
                        <textarea name="uraian_informasi_arsip" class="form-control" rows="3" required placeholder="Masukkan isi/subjek dokumen arsip vital..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Ruangan</label>
                                <input type="text" name="lokasi_ruangan" class="form-control" placeholder="Contoh: Central File Biro UK">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Lemari</label>
                                <input type="text" name="lokasi_lemari" class="form-control" placeholder="Contoh: F.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Boks / Folder</label>
                                <input type="text" name="lokasi_boks" class="form-control" placeholder="Contoh: 1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Fisik</label>
                                <input type="text" name="jenis_fisik" class="form-control" value="Jilid">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Sifat</label>
                                <input type="text" name="sifat" class="form-control" value="Asli">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Kode Klasifikasi</label>
                                <input type="text" name="kode_klasifikasi" class="form-control" placeholder="Contoh: UM.05">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.arsip_vital.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning text-white font-weight-bold px-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
