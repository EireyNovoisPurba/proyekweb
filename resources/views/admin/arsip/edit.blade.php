@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Arsip</h1>
        <a href="{{ route('arsip.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Menampilkan pesan error jika validasi gagal -->
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4 border-bottom-warning">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-edit mr-2"></i>Form Perbarui Arsip</h6>
        </div>
        <div class="card-body">
            <!-- Form diarahkan ke rute Update (Method PUT) -->
            <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Kategori Arsip <span class="text-danger">*</span></label>
                        <select name="kategori_arsip" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @can('input-vital')
                                <option value="Arsip Vital" {{ $arsip->kategori_arsip == 'Arsip Vital' ? 'selected' : '' }}>
                                    Arsip Vital</option>
                            @endcan
                            @can('input-terjaga')
                                <option value="Arsip Terjaga" {{ $arsip->kategori_arsip == 'Arsip Terjaga' ? 'selected' : '' }}>
                                    Arsip Terjaga</option>
                            @endcan
                            @can('input-inaktif')
                                <option value="Arsip Inaktif" {{ $arsip->kategori_arsip == 'Arsip Inaktif' ? 'selected' : '' }}>
                                    Arsip Inaktif</option>
                            @endcan
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Tahun Berkas</label>
                        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $arsip->tahun) }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label class="font-weight-bold">Judul Berkas (Nama Folder Utama) <span
                                class="text-danger">*</span></label>
                        <input type="text" name="judul_berkas" class="form-control" required
                            value="{{ old('judul_berkas', $arsip->judul_berkas) }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label class="font-weight-bold">Pencipta Arsip</label>
                        <input type="text" name="pencipta" class="form-control"
                            value="{{ old('pencipta', $arsip->pencipta) }}">
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-warning text-white shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
