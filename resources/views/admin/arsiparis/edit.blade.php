@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Arsiparis</h1>
        <a href="{{ route('arsiparis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input SDM</h6>
        </div>
        <div class="card-body">

            <!-- Penangkap Pesan Error Validasi -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <strong><i class="fas fa-exclamation-triangle mr-2"></i>Gagal Menyimpan Data!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('arsiparis.update', $arsiparis->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <!-- Tambah value nama -->
                            <input type="text" name="nama" class="form-control" value="{{ $arsiparis->nama }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>NIP</label>
                            <!-- Tambah value nip -->
                            <input type="text" name="nip" class="form-control"
                                value="{{ str_contains($arsiparis->nip, 'NONASN') ? '' : $arsiparis->nip }}">
                        </div>
                        <div class="form-group">
                            <label>Tempat, Tanggal Lahir</label>
                            <!-- Tambah value tempat_tanggal_lahir -->
                            <input type="text" name="tempat_tanggal_lahir" class="form-control"
                                placeholder="Contoh: Jakarta, 17 Agustus 1990"
                                value="{{ $arsiparis->tempat_tanggal_lahir }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PANGKAT DAN GOLONGAN DIPISAH -->
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pangkat</label>
                                <input type="text" name="pangkat" class="form-control" value="{{ $arsiparis->pangkat }}"
                                    placeholder="Cth: Pembina">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Golongan</label>
                                <input type="text" name="golongan" class="form-control"
                                    value="{{ $arsiparis->golongan }}" placeholder="Cth: IV/b">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pendidikan Terakhir</label>
                            <!-- Tambah value pendidikan -->
                            <input type="text" name="pendidikan" class="form-control"
                                value="{{ $arsiparis->pendidikan }}">
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <!-- Tambah value jabatan -->
                            <input type="text" name="jabatan" class="form-control" value="{{ $arsiparis->jabatan }}">
                        </div>
                        <div class="form-group">
                            <label>Unit Kerja</label>
                            <!-- Tambah value unit_kerja -->
                            <input type="text" name="unit_kerja" class="form-control"
                                value="{{ $arsiparis->unit_kerja }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
            </form>
        </div>
    </div>
@endsection
