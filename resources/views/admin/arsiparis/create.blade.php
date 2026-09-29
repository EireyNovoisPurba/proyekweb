@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Arsiparis</h1>
        <a href="{{ route('arsiparis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input SDM</h6>
        </div>
        <div class="card-body">

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

            <form action="{{ route('arsiparis.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                        <div class="form-group">
                            <label>NIP</label>
                            <!-- REQUIRED DIHAPUS, DIGANTI PLACEHOLDER -->
                            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}"
                                placeholder="Kosongkan jika Non-ASN">
                        </div>
                        <div class="form-group">
                            <label>Tempat, Tanggal Lahir</label>
                            <input type="text" name="tempat_tanggal_lahir" class="form-control"
                                placeholder="Contoh: Jakarta, 17 Agustus 1990" value="{{ old('tempat_tanggal_lahir') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pangkat</label>
                                <input type="text" name="pangkat" class="form-control" value="{{ old('pangkat') }}"
                                    placeholder="Cth: Pembina">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Golongan</label>
                                <input type="text" name="golongan" class="form-control" value="{{ old('golongan') }}"
                                    placeholder="Cth: IV/b">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan" class="form-control" value="{{ old('pendidikan') }}">
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
                        </div>
                        <div class="form-group">
                            <label>Unit Kerja</label>
                            <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
            </form>
        </div>
    </div>
@endsection
