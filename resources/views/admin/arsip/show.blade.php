@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Deskripsi Arsip</h1>
        <button onclick="window.history.back();" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- 1. DAFTAR DOKUMEN DALAM BERKAS (SCROLLABLE) -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-folder-open mr-2"></i>Daftar Dokumen dalam Berkas: {{ $arsip->judul_berkas }}
            </h6>
        </div>
        <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
            <div class="list-group list-group-flush">
                @isset($isiBerkasTerkait)
                    @forelse($isiBerkasTerkait as $berkasItem)
                        @if ($berkasItem->judul_isi != '-')
                            <a href="{{ route('arsip.show', $berkasItem->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-4 py-3 {{ $arsip->id == $berkasItem->id ? 'active bg-primary border-primary' : '' }}">
                                <div>
                                    <i
                                        class="far fa-file-alt mr-2 {{ $arsip->id == $berkasItem->id ? 'text-white' : 'text-secondary' }}"></i>
                                    <span
                                        class="font-weight-bold {{ $arsip->id == $berkasItem->id ? 'text-white' : 'text-dark' }}">
                                        {{ $berkasItem->judul_isi }}
                                    </span>
                                    <small
                                        class="d-block ml-4 {{ $arsip->id == $berkasItem->id ? 'text-light' : 'text-muted' }}">Pencipta:
                                        {{ $berkasItem->pencipta }}</small>
                                </div>
                                <span
                                    class="badge {{ $arsip->id == $berkasItem->id ? 'bg-white text-primary' : 'badge-light border text-muted' }} px-2 py-1">Tahun:
                                    {{ $berkasItem->tahun }}</span>
                            </a>
                        @endif
                    @empty
                        <div class="list-group-item px-4 text-muted font-italic">Belum ada dokumen.</div>
                    @endforelse
                @endisset
            </div>
        </div>
    </div>

    <!-- 2. TABEL IDENTITAS & INFORMASI LENGKAP (21 KOLOM EXCEL) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Bagian Identitas & Informasi Arsip Terperinci</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0" width="100%">
                    <tbody>
                        <tr>
                            <td width="30%" class="font-weight-bold">Judul Berkas (Induk)</td>
                            <td width="2%">:</td>
                            <td>{{ $arsip->judul_berkas }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Judul Isi (Dokumen)</td>
                            <td>:</td>
                            <td class="text-primary font-weight-bold">{{ $arsip->judul_isi }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jenis / Kategori Arsip</td>
                            <td>:</td>
                            <td><span class="badge badge-info">{{ $arsip->kategori_arsip }}</span></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Validator</td>
                            <td>:</td>
                            <td>{{ $arsip->validator }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Pencipta Arsip</td>
                            <td>:</td>
                            <td>{{ $arsip->pencipta }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Asal Berkas</td>
                            <td>:</td>
                            <td>{{ $arsip->asal_berkas }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tahun Penciptaan</td>
                            <td>:</td>
                            <td>{{ $arsip->tahun }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Retensi Aktif</td>
                            <td>:</td>
                            <td>{{ $arsip->retensi_aktif }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Retensi Inaktif</td>
                            <td>:</td>
                            <td>{{ $arsip->retensi_inaktif }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Lokasi Ruangan</td>
                            <td>:</td>
                            <td>{{ $arsip->lokasi_ruangan }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tempat (Kabinet/Lemari/Rak)</td>
                            <td>:</td>
                            <td>{{ $arsip->tempat }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Folder / Boks</td>
                            <td>:</td>
                            <td>{{ $arsip->folder_boks }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tingkat Klasifikasi</td>
                            <td>:</td>
                            <td>{{ $arsip->tingkat_klasifikasi }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jenis Klasifikasi</td>
                            <td>:</td>
                            <td>{{ $arsip->jenis_klasifikasi }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Klasifikasi BIG</td>
                            <td>:</td>
                            <td>{{ $arsip->klasifikasi_big }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Klasifikasi Bakosurtanal</td>
                            <td>:</td>
                            <td>{{ $arsip->klasifikasi_bakosurtanal }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jenis Fisik / Media</td>
                            <td>:</td>
                            <td>{{ $arsip->jenis_fisik }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Sifat</td>
                            <td>:</td>
                            <td>{{ $arsip->sifat }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Kondisi</td>
                            <td>:</td>
                            <td>{{ $arsip->kondisi }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Keterangan</td>
                            <td>:</td>
                            <td>{{ $arsip->keterangan }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jumlah</td>
                            <td>:</td>
                            <td>{{ $arsip->jumlah }}</td>
                        </tr>

                        <!-- FILE PDF / GAMBAR -->
                        <tr>
                            <td class="font-weight-bold align-middle">File Dokumen Lampiran</td>
                            <td class="align-middle">:</td>
                            <td class="align-middle">
                                @if ($arsip->file_arsip)
                                    <a href="{{ asset('file_arsip_pdf/' . $arsip->file_arsip) }}" target="_blank"
                                        class="btn btn-sm btn-info shadow-sm mr-1"><i class="fas fa-eye mr-1"></i> Lihat</a>
                                    <a href="{{ asset('file_arsip_pdf/' . $arsip->file_arsip) }}" download
                                        class="btn btn-sm btn-primary shadow-sm mr-3"><i class="fas fa-download mr-1"></i>
                                        Unduh</a>

                                    <!-- TOMBOL HAPUS FILE MANDIRI -->
                                    <form action="{{ route('arsip.hapusFile', $arsip->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"
                                            onclick="return confirm('Hapus file lampiran ini saja? Data teks arsip tidak akan hilang.')">
                                            <i class="fas fa-times-circle mr-1"></i> Hapus File Saja
                                        </button>
                                    </form>
                                @else
                                    <span class="text-danger font-italic"><i
                                            class="fas fa-exclamation-circle mr-1"></i>Belum ada file digital (PDF/Gambar)
                                        yang dilampirkan.</span>
                                @endif
                            </td>
                        </tr>

                        <!-- AKSI EDIT & HAPUS -->
                        @if ($arsip->judul_isi != '-')
                            <tr class="bg-light">
                                <td class="font-weight-bold text-gray-800 align-middle">Aksi Pengelolaan Data</td>
                                <td class="align-middle">:</td>
                                <td class="align-middle">
                                    <button class="btn btn-sm btn-warning text-white shadow-sm mr-2" data-toggle="modal"
                                        data-target="#modalEditDokumen">
                                        <i class="fas fa-edit mr-1"></i> Edit Detail Dokumen
                                    </button>
                                    <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                            onclick="return confirm('Hapus dokumen ini selamanya?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus Dokumen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. MODAL EDIT SUPER LENGKAP -->
    <div class="modal fade" id="modalEditDokumen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('arsip.updateDokumen', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-content border-bottom-warning">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-warning">Edit Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body row">

                        <div class="form-group col-md-6"><label>Judul Isi <span class="text-danger">*</span></label><input
                                type="text" name="judul_isi" class="form-control" required
                                value="{{ $arsip->judul_isi }}"></div>
                        <div class="form-group col-md-6"><label>Validator</label><input type="text" name="validator"
                                class="form-control" value="{{ $arsip->validator }}"></div>

                        <div class="form-group col-md-4">
                            <label>Pencipta</label>
                            <select name="pencipta" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Bakosurtanal" {{ $arsip->pencipta == 'Bakosurtanal' ? 'selected' : '' }}>1
                                    - Bakosurtanal</option>
                                <option value="Badan Informasi Geospasial"
                                    {{ $arsip->pencipta == 'Badan Informasi Geospasial' ? 'selected' : '' }}>2 - Badan
                                    Informasi Geospasial</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4"><label>Asal Berkas</label><input type="text"
                                name="asal_berkas" class="form-control" value="{{ $arsip->asal_berkas }}"></div>
                        <div class="form-group col-md-4">
                            <label>Tahun Cipta</label>
                            <select name="tahun" class="form-control">
                                <option value="-">-- Pilih Tahun --</option>
                                @for ($i = date('Y'); $i >= 1980; $i--)
                                    <option value="{{ $i }}" {{ $arsip->tahun == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Retensi Aktif</label>
                            <select name="retensi_aktif" class="form-control">
                                <option value="-">-- Pilih Tahun --</option>
                                @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                    <option value="{{ $i }}"
                                        {{ $arsip->retensi_aktif == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Retensi Inaktif</label>
                            <select name="retensi_inaktif" class="form-control">
                                <option value="-">-- Pilih Tahun --</option>
                                @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                    <option value="{{ $i }}"
                                        {{ $arsip->retensi_inaktif == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4"><label>Jumlah</label><input type="number" name="jumlah"
                                class="form-control" value="{{ $arsip->jumlah }}"></div>

                        <div class="form-group col-md-4"><label>Lokasi Ruangan</label><input type="text"
                                name="lokasi_ruangan" class="form-control" value="{{ $arsip->lokasi_ruangan }}"></div>
                        <div class="form-group col-md-4"><label>Tempat (Rak/Lemari)</label><input type="text"
                                name="tempat" class="form-control" value="{{ $arsip->tempat }}"></div>
                        <div class="form-group col-md-4"><label>Folder / Boks</label><input type="text"
                                name="folder_boks" class="form-control" value="{{ $arsip->folder_boks }}"></div>

                        <div class="form-group col-md-6">
                            <label>Tingkat Klasifikasi</label>
                            <select name="tingkat_klasifikasi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Biasa" {{ $arsip->tingkat_klasifikasi == 'Biasa' ? 'selected' : '' }}>1 -
                                    Biasa</option>
                                <option value="Rahasia" {{ $arsip->tingkat_klasifikasi == 'Rahasia' ? 'selected' : '' }}>2
                                    - Rahasia</option>
                                <option value="Terbatas"
                                    {{ $arsip->tingkat_klasifikasi == 'Terbatas' ? 'selected' : '' }}>3 - Terbatas</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jenis Klasifikasi</label>
                            <select name="jenis_klasifikasi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Bakosurtanal"
                                    {{ $arsip->jenis_klasifikasi == 'Bakosurtanal' ? 'selected' : '' }}>1 - Bakosurtanal
                                </option>
                                <option value="Badan Informasi Geospasial"
                                    {{ $arsip->jenis_klasifikasi == 'Badan Informasi Geospasial' ? 'selected' : '' }}>2 -
                                    Badan Informasi Geospasial</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6"><label>Klasifikasi BIG</label><input type="text"
                                name="klasifikasi_big" class="form-control" value="{{ $arsip->klasifikasi_big }}"></div>
                        <div class="form-group col-md-6"><label>Klasif. Bakosurtanal</label><input type="text"
                                name="klasifikasi_bakosurtanal" class="form-control"
                                value="{{ $arsip->klasifikasi_bakosurtanal }}"></div>

                        <div class="form-group col-md-4">
                            <label>Jenis Fisik</label>
                            <select name="jenis_fisik" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Berkas" {{ $arsip->jenis_fisik == 'Berkas' ? 'selected' : '' }}>1 - Berkas
                                </option>
                                <option value="Buku" {{ $arsip->jenis_fisik == 'Buku' ? 'selected' : '' }}>2 - Buku
                                </option>
                                <option value="CD" {{ $arsip->jenis_fisik == 'CD' ? 'selected' : '' }}>3 - CD</option>
                                <option value="Jilid" {{ $arsip->jenis_fisik == 'Jilid' ? 'selected' : '' }}>4 - Jilid
                                </option>
                                <option value="Lembar" {{ $arsip->jenis_fisik == 'Lembar' ? 'selected' : '' }}>5 - Lembar
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sifat</label>
                            <select name="sifat" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Asli" {{ $arsip->sifat == 'Asli' ? 'selected' : '' }}>1 - Asli</option>
                                <option value="Asli dan Copy" {{ $arsip->sifat == 'Asli dan Copy' ? 'selected' : '' }}>2 -
                                    Asli dan Copy</option>
                                <option value="Copy" {{ $arsip->sifat == 'Copy' ? 'selected' : '' }}>3 - Copy</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Kondisi</label>
                            <select name="kondisi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Baik" {{ $arsip->kondisi == 'Baik' ? 'selected' : '' }}>1 - Baik</option>
                                <option value="Kurang Baik" {{ $arsip->kondisi == 'Kurang Baik' ? 'selected' : '' }}>2 -
                                    Kurang Baik</option>
                                <option value="Rusak" {{ $arsip->kondisi == 'Rusak' ? 'selected' : '' }}>3 - Rusak
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <select name="keterangan" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="Dinilai Kembali"
                                    {{ $arsip->keterangan == 'Dinilai Kembali' ? 'selected' : '' }}>1 - Dinilai Kembali
                                </option>
                                <option value="Permanen" {{ $arsip->keterangan == 'Permanen' ? 'selected' : '' }}>2 -
                                    Permanen</option>
                                <option value="Musnah" {{ $arsip->keterangan == 'Musnah' ? 'selected' : '' }}>3 - Musnah
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-12 mt-2">
                            <label>Upload/Ganti File Lampiran <small class="text-muted">(Format: PDF, JPG, PNG - Maks
                                    10MB)</small></label>
                            <input type="file" name="file_arsip" class="form-control-file border p-2 rounded"
                                accept="application/pdf, image/jpeg, image/png, image/jpg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save mr-1"></i> Update
                            Dokumen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
