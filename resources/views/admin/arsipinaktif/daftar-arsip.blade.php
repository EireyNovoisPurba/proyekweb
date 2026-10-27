@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Uraian Informasi Arsip — Arsip Inaktif</h1>
                <small class="text-muted">Daftar seluruh item arsip inaktif dari semua berkas.</small>
            </div>
            <div>
                <a href="{{ route('admin.arsip_inaktif.index') }}" class="btn btn-sm btn-primary shadow-sm mb-1">
                    <i class="fas fa-folder-open fa-sm text-white-50"></i> Lihat Uraian Berkas
                </a>
                <a href="{{ route('admin.arsip_inaktif.export') }}" class="btn btn-sm btn-info shadow-sm mb-1">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Excel
                </a>
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

        <!-- Filter & Pencarian -->
        <div class="card shadow mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('admin.arsip_inaktif.daftar-arsip') }}" class="form-inline">
                    <div class="input-group mr-3 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search fa-sm"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari uraian informasi arsip, berkas..." value="{{ request('search') }}"
                            style="width: 280px;">
                    </div>
                    <div class="input-group mr-3 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-filter fa-sm"></i></span>
                        </div>
                        <select name="kondisi" class="form-control form-control-sm">
                            <option value="">Semua Kondisi</option>
                            @foreach(['Baik', 'Tidak Baik', 'Rusak', 'Rusak Berat'] as $k)
                                <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mr-3 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span>
                        </div>
                        <select name="klasifikasi_keamanan" class="form-control form-control-sm">
                            <option value="">Semua Klasifikasi</option>
                            @foreach(['Biasa', 'Terbatas', 'Rahasia', 'Sangat Rahasia'] as $k)
                                <option value="{{ $k }}" {{ request('klasifikasi_keamanan') == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mb-1"><i class="fas fa-search mr-1"></i> Filter</button>
                    @if(request('search') || request('kondisi') || request('klasifikasi_keamanan'))
                        <a href="{{ route('admin.arsip_inaktif.daftar-arsip') }}" class="btn btn-secondary btn-sm mb-1 ml-2">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Uraian Informasi Arsip -->
        <div class="card shadow mb-4 border-bottom-success">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-file-alt mr-2"></i>Daftar Uraian Informasi Arsip Inaktif
                </h6>
                <span class="badge badge-success badge-pill">{{ $arsipItems->total() }} item</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th width="3%" class="text-center">No. Item</th>
                                <th width="12%">Uraian Berkas (Induk)</th>
                                <th>Uraian Informasi Arsip</th>
                                <th width="9%" class="text-center">Tanggal</th>
                                <th width="7%" class="text-center">Jml Fisik</th>
                                <th width="8%">Kondisi / Jenis</th>
                                <th width="12%">Lokasi Simpan</th>
                                <th width="7%" class="text-center">Retensi</th>
                                <th width="9%" class="text-center">Klasifikasi</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsipItems as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $item->no_item_arsip ?? ($arsipItems->firstItem() + $index) }}</td>
                                    <td>
                                        <a href="{{ route('admin.arsip_inaktif.show', $item->id) }}"
                                            class="text-primary font-weight-bold" style="font-size:0.85em;">
                                            {{ Str::limit($item->uraian_informasi_berkas, 50) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-dark" style="font-size:0.88em;">{{ Str::limit($item->uraian_informasi_arsip, 120) }}</span>
                                    </td>
                                    <td class="text-center" style="font-size:0.85em;">{{ $item->tanggal ?? '-' }}</td>
                                    <td class="text-center" style="font-size:0.85em;">{{ $item->jumlah_fisik_item ?? '-' }}</td>
                                    <td style="font-size:0.85em;">
                                        @php $kondisi = $item->kondisi ?? '-'; @endphp
                                        <span class="badge {{ $kondisi == 'Baik' ? 'badge-success' : ($kondisi == 'Tidak Baik' ? 'badge-warning' : ($kondisi == 'Rusak' || $kondisi == 'Rusak Berat' ? 'badge-danger' : 'badge-secondary')) }}">
                                            {{ $kondisi }}
                                        </span>
                                        <small class="d-block text-muted">{{ $item->jenis_fisik ?? '' }}</small>
                                    </td>
                                    <td style="font-size:0.82em;">
                                        @if($item->lokasi_ruangan && $item->lokasi_ruangan != '-')
                                            <i class="fas fa-building fa-xs text-muted mr-1"></i>{{ $item->lokasi_ruangan }}<br>
                                        @endif
                                        @if($item->lokasi_rak && $item->lokasi_rak != '-')
                                            <i class="fas fa-layer-group fa-xs text-muted mr-1"></i>{{ $item->lokasi_rak }}<br>
                                        @endif
                                        @if($item->lokasi_boks && $item->lokasi_boks != '-')
                                            <i class="fas fa-box fa-xs text-muted mr-1"></i>{{ $item->lokasi_boks }}
                                        @endif
                                    </td>
                                    <td class="text-center" style="font-size:0.82em;">
                                        <span class="badge badge-light border">A: {{ $item->retensi_aktif ?? '-' }}</span><br>
                                        <span class="badge badge-light border">I: {{ $item->retensi_inaktif ?? '-' }}</span>
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
                                            class="btn btn-warning btn-sm shadow-sm mb-1 text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.arsip_inaktif.destroy', $item->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1"
                                                onclick="return confirm('Hapus item arsip ini secara permanen?')"
                                                title="Hapus Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fas fa-file-alt fa-3x text-gray-300 mb-3 d-block"></i>
                                        <span class="text-muted">Belum ada data Uraian Informasi Arsip.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $arsipItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
