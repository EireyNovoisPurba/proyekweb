@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                    <i class="fas fa-folder-open text-primary mr-2"></i>Edit Uraian Informasi Berkas (Induk)
                </h1>
                <p class="text-muted small mb-0">Ubah data berkas induk. Perubahan data ini otomatis berlaku untuk seluruh item arsip di dalamnya.</p>
            </div>
            <div>
                <a href="{{ route('admin.arsip_inaktif.show', $berkas->id) }}" class="btn btn-sm btn-info shadow-sm mr-1">
                    <i class="fas fa-list-alt fa-sm text-white-50 mr-1"></i> Lihat Isi Berkas
                </a>
                <a href="{{ route('admin.arsip_inaktif.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Berkas
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-edit mr-2"></i>Formulir Data Berkas (Induk)
                        </h6>
                        <span class="badge badge-primary px-3 py-2">
                            Total Item: {{ \App\Models\ArsipInaktif::where('uraian_informasi_berkas', $berkas->uraian_informasi_berkas)->count() }} Dokumen
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.arsip_inaktif.updateBerkas', $berkas->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Uraian Informasi Berkas -->
                            <div class="form-group">
                                <label class="font-weight-bold text-gray-800">
                                    Uraian Informasi Berkas <span class="text-danger">*</span>
                                </label>
                                <textarea name="uraian_informasi_berkas" class="form-control form-control-lg @error('uraian_informasi_berkas') is-invalid @enderror" 
                                    rows="3" required placeholder="Masukkan judul / uraian berkas induk...">{{ old('uraian_informasi_berkas', $berkas->uraian_informasi_berkas) }}</textarea>
                                <small class="form-text text-muted">Contoh: <em>Laporan Hasil Pemeriksaan BPK RI Tahun 2016</em></small>
                                @error('uraian_informasi_berkas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Asal Unit Pengolah -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Asal Unit Pengolah</label>
                                        <input type="text" name="asal_unit_pengolah" class="form-control" 
                                            value="{{ old('asal_unit_pengolah', $berkas->asal_unit_pengolah) }}"
                                            placeholder="Contoh: INSPEKTORAT, Balai Diklat, dll">
                                    </div>
                                </div>

                                <!-- No. Berkas -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">No. Berkas</label>
                                        <input type="text" name="no_berkas" class="form-control" 
                                            value="{{ old('no_berkas', $berkas->no_berkas) }}"
                                            placeholder="Contoh: 107">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Kode Klasifikasi -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Kode Klasifikasi</label>
                                        <input type="text" name="kode_klasifikasi" class="form-control" 
                                            value="{{ old('kode_klasifikasi', $berkas->kode_klasifikasi) }}"
                                            placeholder="Contoh: AW.08">
                                    </div>
                                </div>

                                <!-- Kurun Waktu -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Kurun Waktu</label>
                                        <input type="text" name="kurun_waktu" class="form-control" 
                                            value="{{ old('kurun_waktu', $berkas->kurun_waktu) }}"
                                            placeholder="Contoh: 2017">
                                    </div>
                                </div>

                                <!-- Jumlah Fisik Berkas -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-gray-800">Jumlah Fisik Berkas</label>
                                        <input type="text" name="jumlah_fisik_berkas" class="form-control" 
                                            value="{{ old('jumlah_fisik_berkas', $berkas->jumlah_fisik_berkas) }}"
                                            placeholder="Contoh: 1 atau 1 berkas">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.arsip_inaktif.show', $berkas->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan Berkas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Kolom Berkas (Induk) -->
            <div class="col-lg-4">
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle mr-2"></i>Panduan Berkas (Induk)
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            <strong>Berkas</strong> adalah wadah induk yang menaungi satu atau beberapa dokumen/item arsip. Sesuai standar kearsipan inaktif:
                        </p>
                        <ul class="small text-muted pl-3 mb-3">
                            <li class="mb-2"><strong>Uraian Informasi Berkas</strong>: Judul dossier/map/kumpulan arsip.</li>
                            <li class="mb-2"><strong>Asal Unit Pengolah</strong>: Satuan kerja pencipta berkas.</li>
                            <li class="mb-2"><strong>No. Berkas</strong>: Nomor urut berkas dalam daftar/boks.</li>
                            <li class="mb-2"><strong>Kode Klasifikasi</strong>: Sandi urusan (misal: AW.08, HM.03).</li>
                            <li class="mb-2"><strong>Kurun Waktu</strong>: Tahun berkas diciptakan.</li>
                            <li class="mb-2"><strong>Jumlah Fisik Berkas</strong>: Kuantitas map/dossier.</li>
                        </ul>
                        <div class="alert alert-light border small text-muted mb-0">
                            <i class="fas fa-lightbulb text-warning mr-1"></i>
                            Untuk mengubah detail per surat (nomor surat, tanggal, jenis fisik, lokasi boks/rak), lakukan pada halaman <strong>Lihat Isi Berkas</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
