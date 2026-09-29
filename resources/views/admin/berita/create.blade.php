@extends('layouts.admin')

@section('content')
    <!-- CSS untuk Text Editor Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Berita Baru</h1>
        <a href="{{ route('berita.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input Berita</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="font-weight-bold">Judul Berita</label>
                    <input type="text" name="Judul" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Upload Gambar Utama (Thumbnail Header)</label>
                    <input type="file" class="form-control-file border p-2 rounded" name="Gambar" accept="image/*"
                        required>
                    <small class="text-danger">*Wajib diisi. Maksimal ukuran gambar adalah 10MB.</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Isi/Konten Berita</label>
                    <!-- Kotak Teks ini akan disulap oleh script di bawah -->
                    <textarea id="summernote" name="Konten" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-paper-plane mr-1"></i> Publikasikan
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
                placeholder: 'Ketik paragraf berita Anda di sini. Gunakan ikon "Picture / Gambar" di menu atas untuk menyisipkan puluhan foto...',
                tabsize: 2,
                height: 500, // Tinggi awal kotak editor
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    // Menu Picture inilah yang membolehkan Anda upload banyak foto ke sela teks!
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
