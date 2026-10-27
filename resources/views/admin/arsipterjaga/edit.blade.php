@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-edit text-success mr-2"></i>Edit Item Dokumen Arsip Terjaga
            </h1>
            <button onclick="window.history.back();" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </button>
        </div>

        <form action="{{ route('admin.arsip_terjaga.update', $arsip->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Berkas Induk Banner (Read Only) -->
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-header py-3 bg-light d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-folder mr-2"></i>Berkas Induk Tempat Dokumen Ini Berada
                    </h6>
                    <a href="{{ route('admin.arsip_terjaga.editBerkas', $arsip->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-edit mr-1"></i> Edit Berkas Induk
                    </a>
                </div>
                <div class="card-body py-2">
                    <p class="mb-1"><strong>Uraian Informasi Berkas:</strong> {{ $arsip->uraian_informasi_berkas }}</p>
                    <div class="small text-muted">
                        <span>Klasifikasi: <span class="badge badge-success">{{ $arsip->klasifikasi_arsip ?? '-' }}</span></span> &bull;
                        <span>Pencipta: <strong>{{ $arsip->pencipta ?? '-' }}</strong></span> &bull;
                        <span>Kurun Waktu: <strong>{{ $arsip->kurun_waktu ?? '-' }}</strong></span>
                    </div>
                    <!-- Hidden input to preserve relation -->
                    <input type="hidden" name="uraian_informasi_berkas" value="{{ $arsip->uraian_informasi_berkas }}">
                    <input type="hidden" name="klasifikasi_arsip" value="{{ $arsip->klasifikasi_arsip }}">
                    <input type="hidden" name="no_berkas" value="{{ $arsip->no_berkas }}">
                    <input type="hidden" name="pencipta" value="{{ $arsip->pencipta }}">
                    <input type="hidden" name="asal_berkas" value="{{ $arsip->asal_berkas }}">
                    <input type="hidden" name="kurun_waktu" value="{{ $arsip->kurun_waktu }}">
                    <input type="hidden" name="jumlah_isi_berkas" value="{{ $arsip->jumlah_isi_berkas }}">
                </div>
            </div>

            <!-- Detail Dokumen -->
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt mr-2"></i>Detail Dokumen (Uraian Informasi Arsip)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">No. Item Arsip</label>
                                <input type="text" name="no_item_arsip" class="form-control" value="{{ $arsip->no_item_arsip }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Dokumen</label>
                                <input type="text" name="tanggal" class="form-control" value="{{ $arsip->tanggal }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Fisik</label>
                                <input type="text" name="jenis_fisik" class="form-control" value="{{ $arsip->jenis_fisik }}" placeholder="Contoh: Buku, Kertas, dll">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Uraian Informasi Arsip (Isi Dokumen) <span class="text-danger">*</span></label>
                        <textarea name="uraian_informasi_arsip" class="form-control" rows="3" required>{{ $arsip->uraian_informasi_arsip }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tingkat Perkembangan</label>
                                <select name="tingkat_perkembangan" class="form-control">
                                    <option value="Asli" {{ $arsip->tingkat_perkembangan == 'Asli' ? 'selected' : '' }}>Asli</option>
                                    <option value="Copy" {{ $arsip->tingkat_perkembangan == 'Copy' ? 'selected' : '' }}>Copy</option>
                                    <option value="2Copy" {{ $arsip->tingkat_perkembangan == '2Copy' ? 'selected' : '' }}>2Copy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kondisi Fisik</label>
                                <select name="kondisi" class="form-control">
                                    <option value="Baik" {{ $arsip->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak" {{ $arsip->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lokasi Simpan & Retensi -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi Simpan & Retensi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Ruangan</label>
                                <input type="text" name="lokasi_ruangan" class="form-control" value="{{ $arsip->lokasi_ruangan }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" class="form-control" value="{{ $arsip->lokasi_rak }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Boks</label>
                                <input type="text" name="lokasi_boks" class="form-control" value="{{ $arsip->lokasi_boks }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Klasifikasi Keamanan</label>
                                <input type="text" name="klasifikasi_keamanan" class="form-control" value="{{ $arsip->klasifikasi_keamanan }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Hak Akses</label>
                                <input type="text" name="hak_akses" class="form-control" value="{{ $arsip->hak_akses }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ $arsip->keterangan }}</textarea>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <button type="button" onclick="window.history.back();" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Item
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
