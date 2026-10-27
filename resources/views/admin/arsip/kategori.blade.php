@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen {{ $kategori }}</h1>
        <div>
            @can('is-admin')
                <button class="btn btn-sm btn-success shadow-sm mr-2" data-toggle="modal" data-target="#modalImport">
                    <i class="fas fa-file-excel fa-sm text-white-50"></i> Import Data Excel
                </button>
            @endcan
            <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Berkas Manual
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- TABEL URAIAN INFORMASI BERKAS -->
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Uraian Informasi Berkas ({{ $kategori }})</h6>
            <form action="{{ route('arsip.kategori', $kategori) }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari uraian/tahun..."
                        value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th width="5%">No</th>
                            <!-- TAMBAHAN: Kolom No. Berkas -->
                            <th width="10%">No. Berkas</th>
                            <th>Uraian Informasi Berkas</th>
                            <th width="15%">Pencipta</th>
                            <th width="10%">Kurun Waktu</th>
                            <th width="15%">Aksi Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsip as $item)
                            <tr>
                                <td>{{ $arsip->firstItem() + $loop->index }}</td>
                                <!-- TAMBAHAN: Menampilkan No. Berkas dari Database -->
                                <td>{{ $item->no_berkas != '-' ? $item->no_berkas : 'Belum Ada' }}</td>
                                <td><strong class="text-dark">{{ $item->judul_berkas }}</strong></td>
                                <td>{{ $item->pencipta }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>
                                    <a href="{{ route('arsip.show', $item->id) }}" class="btn btn-sm btn-info shadow-sm"
                                        title="Lihat Informasi Arsip">
                                        <i class="fas fa-list-alt"></i> Detail Arsip
                                    </a>
                                    <!-- Hapus Arsip Berkas -->
                                    <form action="{{ route('arsip.destroy', $item->id) }}" method="POST"
                                        class="d-inline ml-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                            onclick="return confirm('Hapus Uraian Berkas ini beserta seluruh arsip di dalamnya?')"
                                            title="Hapus Berkas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <!-- DIUBAH MENJADI COLSPAN 6 -->
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open mb-2 text-gray-400" style="font-size: 2rem;"></i><br>
                                    Belum ada Uraian Informasi Berkas. Silakan tambah manual atau import Excel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $arsip->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>

    <!-- MODAL TAMBAH BERKAS MANUAL -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('arsip.store') }}" method="POST">
                @csrf
                <!-- Mengirimkan Kategori Aktif Secara Otomatis -->
                <input type="hidden" name="kategori_arsip" value="{{ $kategori }}">

                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-primary">Tambah Uraian Informasi Berkas</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-12">
                            <label>Uraian Informasi Berkas (Nama Folder) <span class="text-danger">*</span></label>
                            <input type="text" name="judul_berkas" class="form-control" required
                                placeholder="Contoh: Laporan Pengerjaan Program...">
                        </div>
                        <div class="form-group col-md-6">
                            <label>No. Berkas</label>
                            <input type="text" name="no_berkas" class="form-control" placeholder="Contoh: 01">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Kurun Waktu (Tahun)</label>
                            <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2026">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Pencipta (Asal Unit Pengolah)</label>
                            <select name="pencipta" class="form-control">
                                <option value="">-- Pilih Pencipta --</option>
                                @foreach ($penciptaList as $p)
                                    <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan
                            Berkas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL IMPORT EXCEL (TERISOLASI) -->
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis_import" value="{{ $kategori }}">

                <div class="modal-content border-bottom-success">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-success">Import Daftar {{ $kategori }} (Excel)
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> Pastikan format kolom sesuai dengan template
                            <strong>{{ $kategori }}</strong>.
                        </div>
                        <div class="form-group">
                            <label>Pilih File Excel</label>
                            <input type="file" name="file_excel" class="form-control-file border p-2 rounded"
                                accept=".xls, .xlsx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload mr-1"></i> Mulai
                            Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
