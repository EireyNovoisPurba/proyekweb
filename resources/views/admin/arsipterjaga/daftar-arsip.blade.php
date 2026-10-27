@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                    <i class="fas fa-list-ul text-success mr-2"></i>Arsip Terjaga — Uraian Informasi Arsip
                </h1>
                <p class="text-muted small mb-0">Daftar seluruh item/dokumen arsip terjaga dari semua berkas batas negara.</p>
            </div>
            <div>
                <a href="{{ route('admin.arsip_terjaga.index') }}" class="btn btn-outline-success btn-sm shadow-sm mr-1">
                    <i class="fas fa-folder fa-sm mr-1"></i> Uraian Informasi Berkas
                </a>
                <a href="{{ route('admin.arsip_terjaga.create') }}" class="btn btn-success btn-sm shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Item Baru
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-2 bg-light">
                <span class="font-weight-bold text-gray-700 small"><i class="fas fa-filter mr-1"></i> Filter Pencarian</span>
            </div>
            <div class="card-body py-3">
                <form action="{{ route('admin.arsip_terjaga.daftar-arsip') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-gray-600">Kata Kunci Pencarian</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari isi dokumen, berkas, klasifikasi, pencipta..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-gray-600">Kondisi Fisik</label>
                            <select name="kondisi" class="form-control form-control-sm">
                                <option value="">-- Semua Kondisi --</option>
                                <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak" {{ request('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-gray-600">Perkembangan</label>
                            <select name="tingkat_perkembangan" class="form-control form-control-sm">
                                <option value="">-- Semua --</option>
                                <option value="Asli" {{ request('tingkat_perkembangan') == 'Asli' ? 'selected' : '' }}>Asli</option>
                                <option value="Copy" {{ request('tingkat_perkembangan') == 'Copy' ? 'selected' : '' }}>Copy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success btn-sm btn-block">
                                <i class="fas fa-search fa-sm mr-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Item Arsip -->
        <div class="card shadow mb-4 border-left-success">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="3%" class="text-center">No</th>
                                <th width="20%">Uraian Informasi Berkas (Induk)</th>
                                <th width="6%" class="text-center">No Item</th>
                                <th>Uraian Informasi Arsip (Isi Dokumen)</th>
                                <th width="10%">Tanggal</th>
                                <th width="8%" class="text-center">Kondisi</th>
                                <th width="12%">Lokasi Simpan</th>
                                <th width="9%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsipItems as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($arsipItems->currentPage() - 1) * $arsipItems->perPage() }}</td>
                                    <td>
                                        <a href="{{ route('admin.arsip_terjaga.show', $item->id) }}" class="font-weight-bold text-success">
                                            {{ $item->uraian_informasi_berkas }}
                                        </a>
                                        @if($item->klasifikasi_arsip)
                                            <br><small class="badge badge-light border">{{ $item->klasifikasi_arsip }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $item->no_item_arsip ?? '-' }}</td>
                                    <td>
                                        <div class="text-dark">{{ $item->uraian_informasi_arsip }}</div>
                                        <small class="text-muted">
                                            Pencipta: <strong>{{ $item->pencipta ?? '-' }}</strong> &bull;
                                            Fisik: <strong>{{ $item->jenis_fisik ?? '-' }}</strong> ({{ $item->tingkat_perkembangan ?? '-' }})
                                        </small>
                                    </td>
                                    <td><small>{{ $item->tanggal ?? '-' }}</small></td>
                                    <td class="text-center">
                                        @if(stripos($item->kondisi, 'baik') !== false)
                                            <span class="badge badge-success">{{ $item->kondisi }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $item->kondisi ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="d-block"><strong>Ruang:</strong> {{ $item->lokasi_ruangan ?? '-' }}</small>
                                        <small class="d-block"><strong>Rak:</strong> {{ $item->lokasi_rak ?? '-' }}</small>
                                        <small class="d-block"><strong>Boks:</strong> {{ $item->lokasi_boks ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.arsip_terjaga.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm shadow-sm mb-1" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.arsip_terjaga.destroy', $item->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1"
                                                onclick="return confirm('Hapus item arsip ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-file-alt fa-3x text-gray-300 mb-3 d-block"></i>
                                        <span class="text-muted">Tidak ada data item arsip terjaga.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $arsipItems->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
