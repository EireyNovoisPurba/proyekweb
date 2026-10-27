@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading & Action Buttons -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Uraian Informasi Berkas — Arsip Inaktif</h1>
                <small class="text-muted">Daftar berkas induk. Klik "Lihat Item" untuk melihat Uraian Informasi Arsip di dalamnya.</small>
            </div>
            <div>
                <a href="{{ route('admin.arsip_inaktif.create') }}" class="btn btn-sm btn-primary shadow-sm mb-1">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Manual
                </a>
                <button class="btn btn-sm btn-success shadow-sm mb-1" data-toggle="modal" data-target="#importExcelModal">
                    <i class="fas fa-file-excel fa-sm text-white-50"></i> Import Excel / CSV
                </button>
                <a href="{{ route('admin.arsip_inaktif.export') }}" class="btn btn-sm btn-info shadow-sm mb-1">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Excel
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger shadow-sm mb-1" data-toggle="modal" data-target="#hapusSemuaModal">
                    <i class="fas fa-trash-alt fa-sm mr-1"></i> Hapus Semua Data
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Info panduan -->
        <div class="alert alert-info py-2 mb-3 d-flex align-items-center">
            <i class="fas fa-info-circle mr-2"></i>
            <span><strong>Format Import:</strong> Gunakan file <code>.xlsx</code> sesuai format <em>Daftar Arsip Inaktif BIG</em>. Data berkas (kolom 1–7) akan otomatis dikelompokkan, dan setiap item arsip (kolom 8–dst) dicatat sebagai Uraian Informasi Arsip.</span>
        </div>

        <!-- DataTables Card -->
        <div class="card shadow mb-4 border-bottom-primary">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-folder-open mr-2"></i>Daftar Uraian Informasi Berkas
                </h6>
                <span class="badge badge-primary badge-pill">{{ $arsip->total() }} berkas</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="4%" class="text-center">No</th>
                                <th width="12%">Asal Unit</th>
                                <th width="10%">No. Berkas</th>
                                <th width="10%">Kode Klasifikasi</th>
                                <th>Uraian Informasi Berkas</th>
                                <th width="10%" class="text-center">Kurun Waktu</th>
                                <th width="10%" class="text-center">Jml Fisik</th>
                                <th width="16%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsip as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->asal_unit_pengolah ?? '-' }}</td>
                                    <td class="text-center">{{ $item->no_berkas ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($item->kode_klasifikasi && $item->kode_klasifikasi != '-')
                                            <span class="badge badge-info">{{ $item->kode_klasifikasi }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><strong class="text-dark">{{ $item->uraian_informasi_berkas }}</strong></td>
                                    <td class="text-center">{{ $item->kurun_waktu ?? '-' }}</td>
                                    <td class="text-center">{{ $item->jumlah_fisik_berkas ?? '-' }}</td>
                                    <td class="text-center">
                                        <!-- Lihat Isi Berkas -->
                                        <a href="{{ route('admin.arsip_inaktif.show', $item->id) }}"
                                            class="btn btn-info btn-sm shadow-sm mb-1" title="Lihat Item Arsip">
                                            <i class="fas fa-list-alt"></i>
                                        </a>
                                        <!-- Edit Berkas (Induk) -->
                                        <a href="{{ route('admin.arsip_inaktif.editBerkas', $item->id) }}"
                                            class="btn btn-warning btn-sm shadow-sm mb-1" title="Edit Berkas (Induk)">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Hapus Seluruh Berkas -->
                                        <form action="{{ route('admin.arsip_inaktif.destroyBerkas', urlencode($item->uraian_informasi_berkas)) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1"
                                                onclick="return confirm('⚠️ Hapus SELURUH berkas dan semua item arsip di dalamnya?\n\nJudul: {{ addslashes($item->uraian_informasi_berkas) }}\n\nTindakan ini tidak dapat dibatalkan!')"
                                                title="Hapus Seluruh Berkas">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-gray-300 mb-3 d-block"></i>
                                        <span class="text-muted">Belum ada data Uraian Informasi Berkas.</span><br>
                                        <small class="text-muted">Tambah manual atau import file Excel.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $arsip->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-bottom-success">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-success" id="importExcelModalLabel">
                        <i class="fas fa-file-excel mr-1"></i> Import Daftar Arsip Inaktif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.arsip_inaktif.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning py-2 small">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Panduan Import File:</strong>
                            <ul class="mb-0 mt-1 pl-3">
                                <li>Format yang didukung: <strong>.xlsx, .xls, atau .csv</strong></li>
                                <li>Data otomatis dibaca mulai dari baris data arsip.</li>
                                <li>Kolom 1–7: Data Berkas (Induk) | Kolom 8–25: Data Item Arsip.</li>
                                <li class="text-success font-weight-bold">💡 Tips File Besar: Untuk file gabungan ukuran besar (>10 MB / puluhan ribu baris), gunakan format <code>.csv</code> (Simpan dari Excel sebagai CSV UTF-8) agar import selesai super cepat dalam beberapa detik tanpa timeout!</li>
                            </ul>
                        </div>
                        <div class="form-group mb-0">
                            <label for="file_excel" class="font-weight-bold">Pilih File (.xlsx, .xls, .csv)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_excel" name="file_excel"
                                    accept=".xls,.xlsx,.csv" required>
                                <label class="custom-file-label" for="file_excel">Pilih file...</label>
                            </div>
                        </div>

                        <!-- Opsi Bersihkan Data Lama -->
                        <div class="card border-left-danger bg-light mt-3 py-2 px-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="reset_before_import" name="reset_before_import" value="1">
                                <label class="custom-control-label font-weight-bold text-danger" for="reset_before_import">
                                    <i class="fas fa-sync-alt mr-1"></i> Kosongkan data lama sebelum import (Fresh Import)
                                </label>
                                <small class="d-block text-muted mt-1">
                                    Centang opsi ini jika file yang Anda upload adalah master data lengkap, sehingga sistem akan membersihkan data lama terlebih dahulu agar <strong>bebas dari duplikasi data</strong>.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="btnImport">
                            <i class="fas fa-upload mr-1"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Arsip Inaktif -->
    <div class="modal fade" id="hapusSemuaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus Semua Data
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center text-danger mb-3">
                        <i class="fas fa-trash-alt fa-3x"></i>
                    </div>
                    <p class="font-weight-bold text-dark text-center mb-2">
                        Apakah Anda yakin ingin MENGHAPUS SEMUA DATA Arsip Inaktif?
                    </p>
                    <div class="alert alert-warning small mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Perhatian:</strong> Tindakan ini akan menghapus <strong>seluruh berkas induk</strong> dan <strong>seluruh dokumen / item uraian informasi arsip inaktif</strong> secara permanen dari database. Data yang sudah dihapus tidak dapat dipulihkan kembali.
                    </div>
                    <p class="small text-muted mb-0">
                        Fitur ini sangat berguna untuk mengosongkan data lama sebelum Anda melakukan import ulang file master yang bersih dan lengkap.
                    </p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.arsip_inaktif.destroyAll') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger font-weight-bold">
                            <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus Semua Data Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "ordering": true,
                "info": true,
                "paging": false, // Gunakan pagination Laravel
                "searching": true,
                "columnDefs": [
                    { "orderable": false, "targets": [7] }
                ]
            });

            // Update label custom file input
            $('#file_excel').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });

            // Disable tombol saat form submit
            $('form[action="{{ route("admin.arsip_inaktif.import") }}"]').on('submit', function() {
                $('#btnImport').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengimpor...');
            });
        });
    </script>
@endpush
