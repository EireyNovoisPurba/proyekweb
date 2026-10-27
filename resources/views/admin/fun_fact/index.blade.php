<!-- resources/views/admin/fun_fact/index.blade.php -->
@extends('layouts.admin') <!-- Sesuaikan dengan nama layout dashboard Anda -->

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Pojok Edukasi</h1>
            <a href="{{ route('fun-fact.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Konten
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Tag</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($funFacts as $item)
                                <tr>
                                    <td><img src="{{ asset('storage/' . $item->gambar) }}" width="80" alt="Gambar">
                                    </td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->tag_badge }}</td>
                                    <td>
                                        @if ($item->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('fun-fact.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('fun-fact.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus data?')">Hapus</button>
                                        </form>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
