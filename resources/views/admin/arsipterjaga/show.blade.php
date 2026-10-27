@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                    <i class="fas fa-folder-open text-success mr-2"></i>Detail Berkas Arsip Terjaga
                </h1>
                <small class="text-muted">Item arsip di dalam berkas: <strong>{{ $induk->uraian_informasi_berkas }}</strong></small>
            </div>
            <div>
                <a href="{{ route('admin.arsip_terjaga.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Berkas
                </a>
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

        <!-- IDENTITAS BERKAS INDUK -->
        <div class="card shadow mb-4 border-left-success">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-folder mr-2"></i>Uraian Informasi Berkas (Induk)
                </h6>
                <a href="{{ route('admin.arsip_terjaga.editBerkas', $induk->id) }}" class="btn btn-sm btn-outline-success shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Edit Berkas Ini
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="40%" class="font-weight-bold text-gray-700">Uraian Informasi Berkas</td>
                                <td>: <strong>{{ $induk->uraian_informasi_berkas }}</strong></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Klasifikasi Arsip</td>
                                <td>: <span class="badge badge-success">{{ $induk->klasifikasi_arsip ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">No. Berkas</td>
                                <td>: {{ $induk->no_berkas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Jumlah Isi Berkas</td>
                                <td>: {{ $induk->jumlah_isi_berkas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="40%" class="font-weight-bold text-gray-700">Pencipta</td>
                                <td>: {{ $induk->pencipta ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Asal Berkas</td>
                                <td>: {{ $induk->asal_berkas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Kurun Waktu</td>
                                <td>: {{ $induk->kurun_waktu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-700">Total Item Terdaftar</td>
                                <td>: <span class="badge badge-info">{{ $itemArsip->count() }} Dokumen</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- DAFTAR ITEM DOKUMEN DALAM BERKAS -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                <h6 class="m-0 font-weight-bold text-gray-800">
                    <i class="fas fa-file-alt mr-2"></i>Daftar Item Dokumen (Uraian Informasi Arsip)
                </h6>
                <a href="{{ route('admin.arsip_terjaga.create', ['berkas' => $induk->uraian_informasi_berkas]) }}"
                    class="btn btn-success btn-sm shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Item Dokumen
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="4%" class="text-center">No</th>
                                <th width="7%" class="text-center">No Item</th>
                                <th>Uraian Informasi Arsip (Isi Berkas)</th>
                                <th width="12%">Tanggal</th>
                                <th width="8%" class="text-center">Fisik</th>
                                <th width="8%" class="text-center">Kondisi</th>
                                <th width="14%">Lokasi Simpan</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($itemArsip as $doc)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center font-weight-bold">{{ $doc->no_item_arsip ?? '-' }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $doc->uraian_informasi_arsip }}</div>
                                        <small class="text-muted">
                                            Perkembangan: <strong>{{ $doc->tingkat_perkembangan ?? '-' }}</strong> &bull;
                                            Keamanan: <span class="badge badge-warning">{{ $doc->klasifikasi_keamanan ?? 'Terbatas' }}</span>
                                        </small>
                                    </td>
                                    <td><small>{{ $doc->tanggal ?? '-' }}</small></td>
                                    <td class="text-center"><small>{{ $doc->jenis_fisik ?? '-' }}</small></td>
                                    <td class="text-center">
                                        @if(stripos($doc->kondisi, 'baik') !== false)
                                            <span class="badge badge-success">{{ $doc->kondisi }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $doc->kondisi ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="d-block"><strong>R:</strong> {{ $doc->lokasi_ruangan ?? '-' }}</small>
                                        <small class="d-block"><strong>Rak:</strong> {{ $doc->lokasi_rak ?? '-' }}</small>
                                        <small class="d-block"><strong>Boks:</strong> {{ $doc->lokasi_boks ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.arsip_terjaga.edit', $doc->id) }}"
                                            class="btn btn-warning btn-sm shadow-sm" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.arsip_terjaga.destroy', $doc->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm"
                                                onclick="return confirm('Hapus item ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Belum ada dokumen dalam berkas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
