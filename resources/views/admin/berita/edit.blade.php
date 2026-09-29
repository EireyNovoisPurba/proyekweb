@extends('layouts.admin')

@section('content')
    <!-- CSS untuk Text Editor Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Berita</h1>
        <a href="{{ route('berita.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Berita Instansi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="font-weight-bold">Judul Berita</label>
                    <input type="text" name="Judul" class="form-control" value="{{ $berita->Judul }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Gambar Utama Saat Ini</label><br>
                    <img src="{{ asset('gambar_berita/' . $berita->Gambar) }}" width="200"
                        class="mb-3 rounded border shadow-sm"><br>
                    <label>Ganti Gambar Utama (Opsional)</label>
                    <input type="file" name="Gambar" class="form-control-file border p-2 rounded" accept="image/*">
                    <small class="text-danger">*Maksimal 10MB. Biarkan kosong jika tidak ingin mengganti gambar
                        header.</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Isi/Konten Berita</label>
                    <textarea id="summernote" name="Konten" class="form-control" required>{{ $berita->Konten }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning text-white mt-3"><i class="fas fa-save mr-1"></i> Update
                    Berita</button>
            </form>
        </div>
    </div>

    <!-- Script jQuery & Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Ketik paragraf berita Anda di sini. Gunakan ikon "Picture / Gambar" di menu atas untuk menyisipkan foto...',
                tabsize: 2,
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
