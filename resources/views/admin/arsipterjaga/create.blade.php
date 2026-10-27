@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-plus-circle text-success mr-2"></i>Tambah Arsip Terjaga
            </h1>
            <a href="{{ route('admin.arsip_terjaga.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.arsip_terjaga.store') }}" method="POST">
            @csrf

            <!-- Bagian 1: Berkas Induk -->
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-folder mr-2"></i>Data Berkas Induk</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Uraian Informasi Berkas (Judul Berkas) <span class="text-danger">*</span></label>
                        <input type="text" name="uraian_informasi_berkas" class="form-control" 
                            value="{{ old('uraian_informasi_berkas', $berkasDefault) }}" required placeholder="Contoh: Demarkasi RI-Malaysia">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Klasifikasi Arsip</label>
                                <input type="text" name="klasifikasi_arsip" class="form-control" placeholder="Contoh: IGD.04.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. Berkas</label>
                                <input type="text" name="no_berkas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Kurun Waktu</label>
                                <input type="text" name="kurun_waktu" class="form-control" placeholder="Contoh: 2017">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Pencipta</label>
                                <input type="text" name="pencipta" class="form-control" placeholder="Contoh: PPBW">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Asal Berkas</label>
                                <input type="text" name="asal_berkas" class="form-control" placeholder="Contoh: BIG">
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
                                <input type="text" name="tanggal" class="form-control" placeholder="Contoh: 28 September 2017">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Fisik</label>
                                <input type="text" name="jenis_fisik" class="form-control" value="Buku">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Uraian Informasi Arsip (Isi Dokumen) <span class="text-danger">*</span></label>
                        <textarea name="uraian_informasi_arsip" class="form-control" rows="3" required placeholder="Masukkan isi/subjek dokumen arsip terjaga..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Ruangan</label>
                                <input type="text" name="lokasi_ruangan" class="form-control" placeholder="Contoh: Gd. E Lt.2">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" class="form-control" placeholder="Contoh: Pintu 1 Malaysia RO 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Boks</label>
                                <input type="text" name="lokasi_boks" class="form-control" placeholder="Contoh: 1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tingkat Perkembangan</label>
                                <select name="tingkat_perkembangan" class="form-control">
                                    <option value="Asli">Asli</option>
                                    <option value="Copy">Copy</option>
                                    <option value="2Copy">2Copy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kondisi</label>
                                <select name="kondisi" class="form-control">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.arsip_terjaga.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
