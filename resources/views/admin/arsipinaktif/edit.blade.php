@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Data Arsip Inaktif</h1>
            <button onclick="window.history.back();" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
            </button>
        </div>

        <form action="{{ route('admin.arsip_inaktif.update', $arsip->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ===== IDENTITAS BERKAS INDUK (READ-ONLY) ===== --}}
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-header py-3 bg-light d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-folder mr-2"></i>Berkas Induk Tempat Item Ini Berada
                    </h6>
                    <a href="{{ route('admin.arsip_inaktif.editBerkas', $arsip->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Berkas Induk
                    </a>
                </div>
                <div class="card-body py-2">
                    <p class="mb-1"><strong>Uraian Informasi Berkas:</strong> {{ $arsip->uraian_informasi_berkas }}</p>
                    <div class="small text-muted">
                        <span>Unit: <strong>{{ $arsip->asal_unit_pengolah ?? '-' }}</strong></span> &bull; 
                        <span>No. Berkas: <strong>{{ $arsip->no_berkas ?? '-' }}</strong></span> &bull; 
                        <span>Kode: <span class="badge badge-info">{{ $arsip->kode_klasifikasi ?? '-' }}</span></span> &bull; 
                        <span>Kurun Waktu: <strong>{{ $arsip->kurun_waktu ?? '-' }}</strong></span>
                    </div>
                    <!-- Hidden input to preserve relation -->
                    <input type="hidden" name="uraian_informasi_berkas" value="{{ $arsip->uraian_informasi_berkas }}">
                    <input type="hidden" name="asal_unit_pengolah" value="{{ $arsip->asal_unit_pengolah }}">
                    <input type="hidden" name="no_berkas" value="{{ $arsip->no_berkas }}">
                    <input type="hidden" name="kode_klasifikasi" value="{{ $arsip->kode_klasifikasi }}">
                    <input type="hidden" name="kurun_waktu" value="{{ $arsip->kurun_waktu }}">
                    <input type="hidden" name="jumlah_fisik_berkas" value="{{ $arsip->jumlah_fisik_berkas }}">
                </div>
            </div>

            {{-- ===== BAGIAN 2: URAIAN INFORMASI ARSIP (DETAIL ITEM) ===== --}}
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-file-alt mr-2"></i>Uraian Informasi Arsip (Detail Item)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Item Arsip</label>
                                <input type="text" name="no_item_arsip" class="form-control" value="{{ $arsip->no_item_arsip }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Dokumen</label>
                                <input type="text" name="tanggal" class="form-control" value="{{ $arsip->tanggal }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah Fisik Item Arsip</label>
                                <input type="text" name="jumlah_fisik_item" class="form-control" value="{{ $arsip->jumlah_fisik_item }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Uraian Informasi Arsip (Isi/Subjek Dokumen)</label>
                        <textarea name="uraian_informasi_arsip" class="form-control" rows="3">{{ $arsip->uraian_informasi_arsip }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Fisik</label>
                                <select name="jenis_fisik" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Berkas', 'Kertas', 'Foto', 'CD/DVD', 'Buku'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->jenis_fisik == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tingkat Perkembangan</label>
                                <select name="tingkat_perkembangan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Asli', 'Copy', 'Tembusan'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->tingkat_perkembangan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kondisi</label>
                                <select name="kondisi" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Baik', 'Tidak Baik', 'Rusak', 'Rusak Berat'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->kondisi == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN 3: LOKASI SIMPAN & RETENSI ===== --}}
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi Simpan & Retensi Arsip</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Boks</label>
                                <input type="text" name="lokasi_boks" class="form-control" value="{{ $arsip->lokasi_boks }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" class="form-control" value="{{ $arsip->lokasi_rak }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Ruangan</label>
                                <input type="text" name="lokasi_ruangan" class="form-control" value="{{ $arsip->lokasi_ruangan }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Retensi Aktif</label>
                                <input type="text" name="retensi_aktif" class="form-control" value="{{ $arsip->retensi_aktif }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Retensi Inaktif</label>
                                <input type="text" name="retensi_inaktif" class="form-control" value="{{ $arsip->retensi_inaktif }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN 4: KLASIFIKASI KEAMANAN & AKSES ===== --}}
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-header py-3 bg-secondary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-shield-alt mr-2"></i>Sistem Klasifikasi Keamanan & Hak Akses</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Klasifikasi Keamanan</label>
                                <select name="klasifikasi_keamanan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Biasa', 'Terbatas', 'Rahasia', 'Sangat Rahasia'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->klasifikasi_keamanan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kategori Arsip</label>
                                <select name="kategori_arsip" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Fasilitatif', 'Substantif'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->kategori_arsip == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tingkat Akses Publik</label>
                                <select name="tingkat_akses_publik" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Terbuka', 'Terbatas', 'Tertutup'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->tingkat_akses_publik == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hak Akses</label>
                                <input type="text" name="hak_akses" class="form-control" value="{{ $arsip->hak_akses }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dasar Pertimbangan</label>
                        <input type="text" name="dasar_pertimbangan" class="form-control" value="{{ $arsip->dasar_pertimbangan }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keterangan / Nasib Akhir</label>
                                <select name="keterangan" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Musnah', 'Permanen', 'Dinilai Kembali', 'Sudah Cek Fisik'] as $opt)
                                        <option value="{{ $opt }}" {{ $arsip->keterangan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pemindahan</label>
                                <input type="text" name="tanggal_pemindahan" class="form-control" value="{{ $arsip->tanggal_pemindahan }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 text-right">
                <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save mr-1"></i> Update Arsip Inaktif</button>
            </div>
        </form>
    </div>
@endsection
