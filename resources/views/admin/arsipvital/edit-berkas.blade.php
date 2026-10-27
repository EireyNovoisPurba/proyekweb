@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                    <i class="fas fa-folder-open text-warning mr-2"></i>Edit Berkas Induk Arsip Vital
                </h1>
                <p class="text-muted small mb-0">Ubah data berkas induk. Perubahan otomatis diselaraskan ke seluruh dokumen di dalamnya.</p>
            </div>
            <div>
                <a href="{{ route('admin.arsip_vital.show', $berkas->id) }}" class="btn btn-sm btn-info shadow-sm mr-1">
                    <i class="fas fa-list-alt mr-1"></i> Lihat Dokumen
                </a>
                <a href="{{ route('admin.arsip_vital.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-edit mr-2"></i>Formulir Data Berkas (Induk)
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.arsip_vital.updateBerkas', $berkas->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold text-gray-800">
                                    Uraian Informasi Berkas (Judul Berkas) <span class="text-danger">*</span>
                                </label>
                                <textarea name="uraian_informasi_berkas" class="form-control form-control-lg @error('uraian_informasi_berkas') is-invalid @enderror" 
                                    rows="3" required>{{ old('uraian_informasi_berkas', $berkas->uraian_informasi_berkas) }}</textarea>
                                @error('uraian_informasi_berkas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Jenis Arsip</label>
                                        <input type="text" name="jenis_arsip" class="form-control" 
                                            value="{{ old('jenis_arsip', $berkas->jenis_arsip) }}" placeholder="Contoh: Kerugian Negara, BMN, dll">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">No. Berkas</label>
                                        <input type="text" name="no_berkas" class="form-control" 
                                            value="{{ old('no_berkas', $berkas->no_berkas) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Pencipta</label>
                                        <input type="text" name="pencipta" class="form-control" 
                                            value="{{ old('pencipta', $berkas->pencipta) }}" placeholder="Contoh: Bakosurtanal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Asal Berkas</label>
                                        <input type="text" name="asal_berkas" class="form-control" 
                                            value="{{ old('asal_berkas', $berkas->asal_berkas) }}" placeholder="Contoh: Biro Umum dan Keuangan">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Kurun Waktu</label>
                                        <input type="text" name="kurun_waktu" class="form-control" 
                                            value="{{ old('kurun_waktu', $berkas->kurun_waktu) }}" placeholder="Contoh: 2019">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Validator</label>
                                        <input type="text" name="validator" class="form-control" 
                                            value="{{ old('validator', $berkas->validator) }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.arsip_vital.show', $berkas->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning text-white font-weight-bold px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan Berkas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-info-circle mr-2"></i>Panduan Berkas Induk</h6>
                    </div>
                    <div class="card-body small text-muted">
                        <p>Halaman ini khusus untuk mengubah atribut <strong>Berkas (Induk)</strong> Arsip Vital.</p>
                        <p class="mb-0">Perubahan judul atau asal berkas ini akan otomatis diterapkan ke seluruh dokumen di bawahnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
