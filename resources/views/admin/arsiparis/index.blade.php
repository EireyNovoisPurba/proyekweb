@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Arsiparis</h1>

        <div class="text-right">
            <!-- Tombol Pemicu Modal Import -->
            <button type="button" class="btn btn-success mr-2 shadow-sm" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-file-excel"></i> Import Excel
            </button>

            <a href="{{ route('arsiparis.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Tambah Data Baru
            </a>
        </div>
    </div>

    <!-- Form Pencarian -->
    <form action="{{ route('arsiparis.index') }}" method="GET" class="form-inline mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIP..."
                value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </div>
        <!-- Tombol Reset akan muncul jika sedang melakukan pencarian -->
        @if (request('search'))
            <a href="{{ route('arsiparis.index') }}" class="btn btn-danger ml-2">Reset</a>
        @endif
    </form>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar SDM Kearsipan</h6>
        </div>
        <div class="card-body">
            <!-- Notifikasi sukses -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <!-- ================= MULAI BAGIAN YANG DIGANTI ================= -->
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIP</th>
                            <th>Pangkat</th>
                            <th>Golongan</th>
                            <th>Pendidikan</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsiparis as $item)
                            <tr>
                                <td>{{ $arsiparis->firstItem() + $loop->index }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ str_contains($item->nip, 'NONASN') ? '-' : $item->nip }}</td>
                                <td>{{ $item->pangkat }}</td>
                                <td>{{ $item->golongan }}</td>
                                <td>{{ $item->pendidikan }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->unit_kerja }}</td>
                                <td>
                                    <a href="{{ route('arsiparis.edit', $item->id) }}"
                                        class="btn btn-warning btn-sm shadow-sm mb-1">Edit</a>

                                    <form action="{{ route('arsiparis.destroy', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1"
                                            onclick="return confirm('Yakin ingin menghapus data SDM ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center font-italic">Belum ada data SDM Arsiparis yang
                                    diinputkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- ================= SELESAI BAGIAN YANG DIGANTI ================= -->
                </table>
            </div>

            <!-- Tombol Paginasi -->
            <div class="mt-4">
                {{ $arsiparis->links() }}
            </div>

        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data SDM via Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('arsiparis.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih File Excel (.xlsx / .xls)</label>
                            <input type="file" name="file_excel" class="form-control-file" required>
                            <small class="text-danger mt-2 d-block">*Pastikan format kolom berurutan: Nama, NIP, TTL,
                                Pangkat, Pendidikan, Jabatan, Unit Kerja (Baris 1 khusus judul kolom).</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Mulai Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
