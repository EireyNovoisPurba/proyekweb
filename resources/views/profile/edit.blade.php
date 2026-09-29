@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
    </div>

    <!-- Menampilkan pesan sukses setelah update -->
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil dan foto berhasil diperbarui!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <!-- Form wajib menggunakan enctype untuk kirim gambar -->
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="form-group text-center mb-4">
                            <!-- Menampilkan foto profil saat ini atau avatar default -->
                            <img src="{{ Auth::user()->foto_profil ? asset('foto_profil/' . Auth::user()->foto_profil) : asset('admin/img/undraw_profile.svg') }}"
                                class="img-profile rounded-circle border shadow-sm mb-3"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            <br>
                            <input type="file" name="foto_profil" class="form-control-file d-inline-block w-auto"
                                accept="image/*">
                            <small class="d-block text-muted mt-1">*Opsional. Maksimal 2MB (JPG/PNG).</small>

                            <!-- Menampilkan pesan error jika foto kebesaran -->
                            @error('foto_profil')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email Instansi</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
