<!-- resources/views/admin/fun_fact/create.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tambah Konten Edukasi Baru</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('fun-fact.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control"
                                    placeholder="Contoh: 🕵️‍♂️ Mitos vs Fakta: Dunia Kearsipan!" required>
                            </div>
                            <div class="mb-3">
                                <label>Tag / Badge <span class="text-danger">*</span></label>
                                <input type="text" name="tag_badge" class="form-control"
                                    placeholder="Contoh: #CekFaktaArsip" required>
                            </div>
                            <div class="mb-3">
                                <label>Deskripsi Singkat <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_singkat" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Gambar Konten (Vertikal 9:16) <span class="text-danger">*</span></label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                                <small class="text-muted">Format: JPG/PNG, Maksimal 2MB.</small>
                            </div>
                        </div>

                        <!-- Kolom Kanan (Poin-poin) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Poin 1: Judul</label>
                                <input type="text" name="poin_1_judul" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Poin 1: Isi</label>
                                <input type="text" name="poin_1_isi" class="form-control">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label>Poin 2: Judul</label>
                                <input type="text" name="poin_2_judul" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Poin 2: Isi</label>
                                <input type="text" name="poin_2_isi" class="form-control">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label>Kesimpulan Akhir</label>
                                <textarea name="kesimpulan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="isActive"
                            checked>
                        <label class="form-check-label" for="isActive">Jadikan konten aktif di Halaman Publik</label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Konten</button>
                    <a href="{{ route('fun-fact.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
