@extends('layouts.admin')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen {{ $kategori_aktif ?? 'Arsip Keseluruhan' }}</h1>
        <div>
            <!-- Tombol Import Excel -->
            <!-- Tombol Import Excel -->
            @can('is-admin')
                <button class="btn btn-sm btn-success shadow-sm mr-2" data-toggle="modal" data-target="#modalImport">
                    <i class="fas fa-file-excel fa-sm text-white-50"></i> Import Data Excel
                </button>
            @endcan
            <!-- Tombol Tambah Manual -->
            <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Arsip Manual
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTales -->
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar {{ $kategori_aktif ?? 'Arsip Keseluruhan' }}</h6>
            <!-- Form Pencarian Sederhana -->
            <form action="{{ route('arsip.index') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul/tahun..."
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
                            <th width="15%">Kategori</th>
                            <th>Judul Berkas</th>
                            <th width="15%">Pencipta</th>
                            <th width="10%">Tahun</th>
                            <th width="20%">Aksi File & Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsip as $item)
                            <tr>
                                <td>{{ $arsip->firstItem() + $loop->index }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $item->kategori_arsip == 'Arsip Vital' ? 'danger' : ($item->kategori_arsip == 'Arsip Terjaga' ? 'warning' : 'info') }}">
                                        {{ $item->kategori_arsip }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Hanya tampilkan nama berkas utama -->
                                    <strong class="text-dark">{{ $item->judul_berkas }}</strong>
                                </td>
                                <td>{{ $item->pencipta }}</td>
                                <td>{{ $item->tahun }}</td>

                                <!-- ================= BAGIAN YANG DIGANTI ================= -->
                                <td>
                                    <!-- Tombol Detail / Review ala SIKN -->
                                    <a href="{{ route('arsip.show', $item->id) }}" class="btn btn-sm btn-info shadow-sm"
                                        title="Review Arsip">
                                        <i class="fas fa-list-alt"></i> Detail Review
                                    </a>

                                    <!-- ================= TOMBOL EDIT (BARU DITAMBAHKAN) ================= -->
                                    <a href="{{ route('arsip.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning text-white shadow-sm mx-1" title="Edit Arsip">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('arsip.destroy', $item->id) }}" method="POST"
                                        class="d-inline ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                            onclick="return confirm('Hapus arsip permanen?')" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <!-- ======================================================= -->
                                <!-- ======================================================= -->

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open mb-2 text-gray-400" style="font-size: 2rem;"></i><br>
                                    Belum ada data arsip. Silakan tambah manual atau import dari Excel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Paginasi -->
            <div class="mt-3">
                {{ $arsip->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH MANUAL ================= -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-primary" id="modalTambahLabel">Tambah Arsip Surat
                            Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-12 text-center mb-4">
                            <span class="badge badge-primary px-3 py-2">Pembuatan Folder Berkas</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Kategori Arsip <span class="text-danger">*</span></label>
                            <select name="kategori_arsip" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @can('input-vital')
                                    <option value="Arsip Vital">Arsip Vital</option>
                                @endcan
                                @can('input-terjaga')
                                    <option value="Arsip Terjaga">Arsip Terjaga</option>
                                @endcan
                                @can('input-inaktif')
                                    <option value="Arsip Inaktif">Arsip Inaktif</option>
                                @endcan
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tahun Berkas</label>
                            <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2026">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Judul Berkas (Nama Folder Utama) <span class="text-danger">*</span></label>
                            <input type="text" name="judul_berkas" class="form-control" required
                                placeholder="Contoh: Laporan Pengerjaan Program ABC...">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Pencipta Arsip</label>
                            <input type="text" name="pencipta" class="form-control"
                                placeholder="Contoh: Bakosurtanal...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan
                            Arsip</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL IMPORT EXCEL ================= -->
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-success">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-success" id="modalImportLabel">Import Daftar Arsip
                            (Excel)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Unggah file <strong>Daftar Arsip Sistem (.xlsx)</strong> untuk
                            memperbarui data secara massal. Pastikan format kolom sesuai dengan template standar BIG.</p>
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
