@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Uraian Informasi Arsip</h1>
                <small class="text-muted">Item arsip di dalam berkas: <strong>{{ $induk->uraian_informasi_berkas }}</strong></small>
            </div>
            <div>
                <!-- Tombol Cetak Label -->
                <a href="{{ route('admin.arsip_inaktif.cetakLabel', $induk->uraian_informasi_berkas) }}" target="_blank"
                    class="btn btn-warning btn-sm shadow-sm mr-1">
                    <i class="fas fa-print fa-sm text-white-50 mr-1"></i> Cetak Label Boks
                </a>
                <a href="{{ route('admin.arsip_inaktif.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Berkas
                </a>
            </div>
        </div>

        <!-- IDENTITAS BERKAS INDUK -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-folder-open mr-2"></i>Uraian Informasi Berkas (Induk)
                </h6>
                <!-- Tombol Edit Berkas Induk -->
                <a href="{{ route('admin.arsip_inaktif.editBerkas', $induk->id) }}"
                    class="btn btn-sm btn-outline-primary shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Berkas Ini
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="45%" class="font-weight-bold text-gray-700">Uraian Informasi Berkas</td>
                                <td>: <strong>{{ $induk->uraian_informasi_berkas }}</strong></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Asal Unit Pengolah</td>
                                <td>: {{ $induk->asal_unit_pengolah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">No. Berkas</td>
                                <td>: {{ $induk->no_berkas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="45%" class="font-weight-bold text-gray-700">Kode Klasifikasi</td>
                                <td>: <span class="badge badge-info">{{ $induk->kode_klasifikasi ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Kurun Waktu</td>
                                <td>: {{ $induk->kurun_waktu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Jumlah Fisik Berkas</td>
                                <td>: {{ $induk->jumlah_fisik_berkas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DAFTAR ITEM ARSIP (ANAK) -->
        <div class="card shadow mb-4 border-bottom-success">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-file-alt mr-2"></i>Daftar Uraian Informasi Arsip
                    <span class="badge badge-success ml-2">{{ $itemArsip->count() }} item</span>
                </h6>
                <!-- Tombol Tambah Item Arsip Baru -->
                <a href="{{ route('admin.arsip_inaktif.create') }}?berkas={{ urlencode($induk->uraian_informasi_berkas) }}"
                    class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Item Arsip
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="dataTableShow" width="100%" cellspacing="0">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th width="5%" class="text-center">No. Item</th>
                                <th>Uraian Informasi Arsip</th>
                                <th width="10%" class="text-center">Tanggal</th>
                                <th width="8%" class="text-center">Jml Fisik</th>
                                <th width="12%">Kondisi / Jenis</th>
                                <th width="14%">Lokasi Simpan</th>
                                <th width="8%" class="text-center">Retensi</th>
                                <th width="9%" class="text-center">Klasifikasi</th>
                                <th width="9%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($itemArsip as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $item->no_item_arsip ?? $index + 1 }}</td>
                                    <td>
                                        <span class="text-dark">{{ $item->uraian_informasi_arsip ?? '-' }}</span>
                                    </td>
                                    <td class="text-center" style="font-size:0.85em;">{{ $item->tanggal ?? '-' }}</td>
                                    <td class="text-center" style="font-size:0.85em;">{{ $item->jumlah_fisik_item ?? '-' }}</td>
                                    <td style="font-size:0.85em;">
                                        @php $kondisi = $item->kondisi ?? '-'; @endphp
                                        <span class="badge {{ $kondisi == 'Baik' ? 'badge-success' : ($kondisi == 'Tidak Baik' ? 'badge-warning' : ($kondisi == 'Rusak' || $kondisi == 'Rusak Berat' ? 'badge-danger' : 'badge-secondary')) }}">
                                            {{ $kondisi }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ $item->jenis_fisik ?? '' }}
                                            @if($item->tingkat_perkembangan && $item->tingkat_perkembangan != '-')
                                                / {{ $item->tingkat_perkembangan }}
                                            @endif
                                        </small>
                                    </td>
                                    <td style="font-size:0.82em;">
                                        @if($item->lokasi_ruangan && $item->lokasi_ruangan != '-')
                                            <i class="fas fa-building fa-xs text-muted"></i> {{ $item->lokasi_ruangan }}<br>
                                        @endif
                                        @if($item->lokasi_rak && $item->lokasi_rak != '-')
                                            <i class="fas fa-layer-group fa-xs text-muted"></i> {{ $item->lokasi_rak }}<br>
                                        @endif
                                        @if($item->lokasi_boks && $item->lokasi_boks != '-')
                                            <i class="fas fa-box fa-xs text-muted"></i> {{ $item->lokasi_boks }}
                                        @endif
                                    </td>
                                    <td class="text-center" style="font-size:0.82em;">
                                        <small>A: {{ $item->retensi_aktif ?? '-' }}<br>I: {{ $item->retensi_inaktif ?? '-' }}</small>
                                    </td>
                                    <td class="text-center" style="font-size:0.82em;">
                                        @if($item->klasifikasi_keamanan && $item->klasifikasi_keamanan != '-')
                                            <span class="badge {{ $item->klasifikasi_keamanan == 'Biasa' ? 'badge-secondary' : ($item->klasifikasi_keamanan == 'Terbatas' ? 'badge-warning' : 'badge-danger') }}">
                                                {{ $item->klasifikasi_keamanan }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.arsip_inaktif.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning text-white shadow-sm mb-1" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.arsip_inaktif.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm mb-1"
                                                onclick="return confirm('Hapus item arsip ini secara permanen?')"
                                                title="Hapus Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="fas fa-file-alt fa-3x text-gray-300 mb-3 d-block"></i>
                                        Belum ada item arsip dalam berkas ini.<br>
                                        <a href="{{ route('admin.arsip_inaktif.create') }}?berkas={{ urlencode($induk->uraian_informasi_berkas) }}"
                                            class="btn btn-sm btn-success mt-2">
                                            <i class="fas fa-plus mr-1"></i> Tambah Item Arsip Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($itemArsip->count() > 0)
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <small class="text-muted">Total: {{ $itemArsip->count() }} item arsip dalam berkas ini</small>
                <!-- Hapus seluruh berkas -->
                <form action="{{ route('admin.arsip_inaktif.destroyBerkas', urlencode($induk->uraian_informasi_berkas)) }}"
                    method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('⚠️ Hapus SELURUH berkas ini beserta {{ $itemArsip->count() }} item arsip di dalamnya?\n\nTindakan ini TIDAK DAPAT DIBATALKAN!')">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus Seluruh Berkas Ini
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTableShow').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "ordering": false,
                "pageLength": 25
            });
        });
    </script>
@endpush
