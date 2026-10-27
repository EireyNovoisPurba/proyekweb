<!-- resources/views/admin/fun_fact/edit.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Konten Edukasi</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('fun-fact.update', $funFact->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control"
                                    value="{{ old('judul', $funFact->judul) }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Tag / Badge <span class="text-danger">*</span></label>
                                <input type="text" name="tag_badge" class="form-control"
                                    value="{{ old('tag_badge', $funFact->tag_badge) }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Deskripsi Singkat <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_singkat" class="form-control" rows="3" required>{{ old('deskripsi_singkat', $funFact->deskripsi_singkat) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Gambar Saat Ini</label><br>
                                @if ($funFact->gambar)
                                    <img src="{{ asset('storage/' . $funFact->gambar) }}" width="150"
                                        class="mb-2 rounded border">
                                @endif
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            </div>
                        </div>

                        <!-- Kolom Kanan (Poin-poin) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Poin 1: Judul</label>
                                <input type="text" name="poin_1_judul" class="form-control"
                                    value="{{ old('poin_1_judul', $funFact->poin_1_judul) }}">
                            </div>
                            <div class="mb-3">
                                <label>Poin 1: Isi</label>
                                <input type="text" name="poin_1_isi" class="form-control"
                                    value="{{ old('poin_1_isi', $funFact->poin_1_isi) }}">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label>Poin 2: Judul</label>
                                <input type="text" name="poin_2_judul" class="form-control"
                                    value="{{ old('poin_2_judul', $funFact->poin_2_judul) }}">
                            </div>
                            <div class="mb-3">
                                <label>Poin 2: Isi</label>
                                <input type="text" name="poin_2_isi" class="form-control"
                                    value="{{ old('poin_2_isi', $funFact->poin_2_isi) }}">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label>Poin 3: Judul</label>
                                <input type="text" name="poin_3_judul" class="form-control"
                                    value="{{ old('poin_3_judul', $funFact->poin_3_judul) }}">
                            </div>
                            <div class="mb-3">
                                <label>Poin 3: Isi</label>
                                <input type="text" name="poin_3_isi" class="form-control"
                                    value="{{ old('poin_3_isi', $funFact->poin_3_isi) }}">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label>Kesimpulan Akhir</label>
                                <textarea name="kesimpulan" class="form-control" rows="2">{{ old('kesimpulan', $funFact->kesimpulan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="isActive"
                            {{ $funFact->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Jadikan konten aktif di Halaman Publik</label>
                    </div>

                    <button type="submit" class="btn btn-warning mt-3">Perbarui Konten</button>
                    <a href="{{ route('fun-fact.index') }}" class="btn btn-secondary mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
