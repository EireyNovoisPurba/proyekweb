@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading & Action Buttons -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Arsip Vital — Uraian Informasi Berkas
                </h1>
                <p class="text-muted small mb-0">Daftar Berkas Induk Arsip Vital (Aset BMN, Kerugian Negara, Dokumen Hukum & Kepegawaian Penting).</p>
            </div>
            <div class="d-flex align-items-center mt-3 mt-sm-0">
                <a href="{{ route('admin.arsip_vital.daftar-arsip') }}" class="btn btn-outline-warning btn-sm shadow-sm mr-2 text-dark font-weight-bold">
                    <i class="fas fa-list-ul fa-sm mr-1"></i> Uraian Informasi Arsip
                </a>
                <a href="{{ route('admin.arsip_vital.create') }}" class="btn btn-warning btn-sm shadow-sm mr-2 text-white font-weight-bold">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Berkas
                </a>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#importExcelModal">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Import Excel / CSV
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Card Tabel Uraian Informasi Berkas -->
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-folder-open mr-2"></i>Daftar Uraian Informasi Berkas (Induk)
                </h6>
                <form action="{{ route('admin.arsip_vital.index') }}" method="GET" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control bg-light border-0 small"
                            placeholder="Cari berkas / jenis arsip..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning text-white" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="4%" class="text-center">No</th>
                                <th width="14%">Jenis Arsip</th>
                                <th width="8%" class="text-center">No. Berkas</th>
                                <th>Uraian Informasi Berkas (Induk)</th>
                                <th width="15%">Asal Berkas / Pencipta</th>
                                <th width="9%" class="text-center">Kurun Waktu</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsip as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-warning text-white">{{ $item->jenis_arsip ?? 'Arsip Vital' }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->no_berkas ?? '-' }}</td>
                                    <td>
                                        <strong class="text-dark">{{ $item->uraian_informasi_berkas }}</strong>
                                        @php
                                            $totalChild = \App\Models\ArsipVital::where('uraian_informasi_berkas', $item->uraian_informasi_berkas)->count();
                                        @endphp
                                        <span class="badge badge-secondary ml-1">{{ $totalChild }} Item</span>
                                    </td>
                                    <td>
                                        <small class="d-block font-weight-bold">{{ $item->asal_berkas ?? '-' }}</small>
                                        <small class="text-muted">{{ $item->pencipta ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->kurun_waktu ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.arsip_vital.show', $item->id) }}"
                                            class="btn btn-info btn-sm shadow-sm mb-1" title="Lihat Item Arsip">
                                            <i class="fas fa-list-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.arsip_vital.editBerkas', $item->id) }}"
                                            class="btn btn-warning btn-sm shadow-sm mb-1 text-white" title="Edit Berkas (Induk)">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.arsip_vital.destroyBerkas', urlencode($item->uraian_informasi_berkas)) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1"
                                                onclick="return confirm('⚠️ Hapus SELURUH berkas vital ini beserta seluruh dokumen di dalamnya?\n\nJudul: {{ addslashes($item->uraian_informasi_berkas) }}')"
                                                title="Hapus Seluruh Berkas">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-gray-300 mb-3 d-block"></i>
                                        <span class="text-muted">Belum ada data Uraian Informasi Berkas Arsip Vital.</span><br>
                                        <small class="text-muted">Gunakan tombol <strong>Import Excel / CSV</strong> atau <strong>Tambah Berkas</strong>.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $arsip->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel/CSV -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-bottom-warning">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-warning">
                        <i class="fas fa-file-excel mr-1"></i> Import Arsip Vital (.xlsx / .csv)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.arsip_vital.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning py-2 small">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Panduan Import:</strong>
                            <ul class="mb-0 mt-1 pl-3">
                                <li>Format yang didukung: <strong>.xlsx, .xls, atau .csv</strong>.</li>
                                <li>Mendukung file multi-sheet (seperti <code>DAFTAR ARSIP VITAL BIRO UK (1).xlsx</code> dengan sheet 2025, 2026, VITAL).</li>
                                <li>Sistem otomatis membaca seluruh sheet dan memetakan kolom-kolomnya ke database secara presisi.</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="file_excel" class="font-weight-bold">Pilih File Arsip Vital</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_excel" name="file_excel"
                                    accept=".xls,.xlsx,.csv" required>
                                <label class="custom-file-label" for="file_excel">Pilih file...</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white font-weight-bold">
                            <i class="fas fa-upload mr-1"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
