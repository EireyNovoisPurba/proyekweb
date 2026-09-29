@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dokumen: {{ $kategori }}</h1>
        <button class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#modalTambahDokumen">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Dokumen ke Berkas
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4 border-bottom-success">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-file-alt mr-2"></i>Daftar Judul Isi
            </h6>
            <form action="{{ route('arsip.kategori', $kategori) }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari isi/berkas..."
                        value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Judul Isi (Dokumen)</th>
                            <th>Induk Berkas</th>
                            <th>Tingkat Klasifikasi</th>
                            <th width="20%">Aksi File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsip as $item)
                            <tr>
                                <td class="align-middle">{{ $arsip->firstItem() + $loop->index }}</td>
                                <td class="align-middle font-weight-bold">{{ $item->judul_isi }}</td>
                                <td class="align-middle"><i class="fas fa-folder text-warning mr-1"></i>
                                    {{ $item->judul_berkas }}</td>
                                <td class="align-middle">{{ $item->tingkat_klasifikasi }}</td>
                                <td class="align-middle">
                                    <!-- TOMBOL DETAIL/PREVIEW BARU -->
                                    <a href="{{ route('arsip.show', $item->id) }}"
                                        class="btn btn-sm btn-info shadow-sm mx-1" title="Detail Review">
                                        <i class="fas fa-list-alt"></i> Detail
                                    </a>

                                    <button class="btn btn-sm btn-warning text-white shadow-sm mx-1" data-toggle="modal"
                                        data-target="#modalEditDokumen{{ $item->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('arsip.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm mx-1"
                                            onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- MODAL EDIT DOKUMEN SUPER LENGKAP -->
                            <div class="modal fade" id="modalEditDokumen{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('arsip.updateDokumen', $item->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="modal-content border-bottom-warning">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title font-weight-bold text-warning">Edit Dokumen:
                                                    {{ $item->judul_isi }}</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body row text-left">
                                                <div class="form-group col-md-6"><label>Judul Isi <span
                                                            class="text-danger">*</span></label><input type="text"
                                                        name="judul_isi" class="form-control" required
                                                        value="{{ $item->judul_isi }}"></div>
                                                <div class="form-group col-md-6"><label>Validator</label><input
                                                        type="text" name="validator" class="form-control"
                                                        value="{{ $item->validator }}"></div>

                                                <div class="form-group col-md-4">
                                                    <label>Pencipta</label>
                                                    <select name="pencipta" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Bakosurtanal"
                                                            {{ $item->pencipta == 'Bakosurtanal' ? 'selected' : '' }}>1 -
                                                            Bakosurtanal</option>
                                                        <option value="Badan Informasi Geospasial"
                                                            {{ $item->pencipta == 'Badan Informasi Geospasial' ? 'selected' : '' }}>
                                                            2 - Badan Informasi Geospasial</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4"><label>Asal Berkas</label><input
                                                        type="text" name="asal_berkas" class="form-control"
                                                        value="{{ $item->asal_berkas }}"></div>
                                                <div class="form-group col-md-4">
                                                    <label>Tahun Cipta</label>
                                                    <select name="tahun" class="form-control">
                                                        <option value="-">-- Pilih Tahun --</option>
                                                        @for ($i = date('Y'); $i >= 1980; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ $item->tahun == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label>Retensi Aktif</label>
                                                    <select name="retensi_aktif" class="form-control">
                                                        <option value="-">-- Pilih Tahun --</option>
                                                        <!-- Retensi ditambah 20 tahun ke depan untuk jadwal pemusnahan -->
                                                        @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ $item->retensi_aktif == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Retensi Inaktif</label>
                                                    <select name="retensi_inaktif" class="form-control">
                                                        <option value="-">-- Pilih Tahun --</option>
                                                        @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ $item->retensi_inaktif == $i ? 'selected' : '' }}>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4"><label>Jumlah</label><input
                                                        type="number" name="jumlah" class="form-control"
                                                        value="{{ $item->jumlah }}"></div>

                                                <div class="form-group col-md-4"><label>Lokasi Ruangan</label><input
                                                        type="text" name="lokasi_ruangan" class="form-control"
                                                        value="{{ $item->lokasi_ruangan }}"></div>
                                                <div class="form-group col-md-4"><label>Tempat</label><input
                                                        type="text" name="tempat" class="form-control"
                                                        value="{{ $item->tempat }}"></div>
                                                <div class="form-group col-md-4"><label>Folder / Boks</label><input
                                                        type="text" name="folder_boks" class="form-control"
                                                        value="{{ $item->folder_boks }}"></div>

                                                <div class="form-group col-md-6">
                                                    <label>Tingkat Klasifikasi</label>
                                                    <select name="tingkat_klasifikasi" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Biasa"
                                                            {{ $item->tingkat_klasifikasi == 'Biasa' ? 'selected' : '' }}>1
                                                            - Biasa</option>
                                                        <option value="Rahasia"
                                                            {{ $item->tingkat_klasifikasi == 'Rahasia' ? 'selected' : '' }}>
                                                            2 - Rahasia</option>
                                                        <option value="Terbatas"
                                                            {{ $item->tingkat_klasifikasi == 'Terbatas' ? 'selected' : '' }}>
                                                            3 - Terbatas</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Jenis Klasifikasi</label>
                                                    <select name="jenis_klasifikasi" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Bakosurtanal"
                                                            {{ $item->jenis_klasifikasi == 'Bakosurtanal' ? 'selected' : '' }}>
                                                            1 - Bakosurtanal</option>
                                                        <option value="Badan Informasi Geospasial"
                                                            {{ $item->jenis_klasifikasi == 'Badan Informasi Geospasial' ? 'selected' : '' }}>
                                                            2 - Badan Informasi Geospasial</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6"><label>Klasifikasi BIG</label><input
                                                        type="text" name="klasifikasi_big" class="form-control"
                                                        value="{{ $item->klasifikasi_big }}"></div>
                                                <div class="form-group col-md-6"><label>Klasif. Bakosurtanal</label><input
                                                        type="text" name="klasifikasi_bakosurtanal"
                                                        class="form-control"
                                                        value="{{ $item->klasifikasi_bakosurtanal }}"></div>

                                                <div class="form-group col-md-4">
                                                    <label>Jenis Fisik</label>
                                                    <select name="jenis_fisik" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Berkas"
                                                            {{ $item->jenis_fisik == 'Berkas' ? 'selected' : '' }}>1 -
                                                            Berkas</option>
                                                        <option value="Buku"
                                                            {{ $item->jenis_fisik == 'Buku' ? 'selected' : '' }}>2 - Buku
                                                        </option>
                                                        <option value="CD"
                                                            {{ $item->jenis_fisik == 'CD' ? 'selected' : '' }}>3 - CD
                                                        </option>
                                                        <option value="Jilid"
                                                            {{ $item->jenis_fisik == 'Jilid' ? 'selected' : '' }}>4 - Jilid
                                                        </option>
                                                        <option value="Lembar"
                                                            {{ $item->jenis_fisik == 'Lembar' ? 'selected' : '' }}>5 -
                                                            Lembar</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Sifat</label>
                                                    <select name="sifat" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Asli"
                                                            {{ $item->sifat == 'Asli' ? 'selected' : '' }}>1 - Asli
                                                        </option>
                                                        <option value="Asli dan Copy"
                                                            {{ $item->sifat == 'Asli dan Copy' ? 'selected' : '' }}>2 -
                                                            Asli dan Copy</option>
                                                        <option value="Copy"
                                                            {{ $item->sifat == 'Copy' ? 'selected' : '' }}>3 - Copy
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Kondisi</label>
                                                    <select name="kondisi" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Baik"
                                                            {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>1 - Baik
                                                        </option>
                                                        <option value="Kurang Baik"
                                                            {{ $item->kondisi == 'Kurang Baik' ? 'selected' : '' }}>2 -
                                                            Kurang Baik</option>
                                                        <option value="Rusak"
                                                            {{ $item->kondisi == 'Rusak' ? 'selected' : '' }}>3 - Rusak
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Keterangan</label>
                                                    <select name="keterangan" class="form-control">
                                                        <option value="-">-- Pilih --</option>
                                                        <option value="Dinilai Kembali"
                                                            {{ $item->keterangan == 'Dinilai Kembali' ? 'selected' : '' }}>
                                                            1 - Dinilai Kembali</option>
                                                        <option value="Permanen"
                                                            {{ $item->keterangan == 'Permanen' ? 'selected' : '' }}>2 -
                                                            Permanen</option>
                                                        <option value="Musnah"
                                                            {{ $item->keterangan == 'Musnah' ? 'selected' : '' }}>3 -
                                                            Musnah</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-12 mt-2">
                                                    <label class="font-weight-bold">Upload File Lampiran <small
                                                            class="font-weight-normal text-muted">(PDF / JPG / PNG - Maks
                                                            10MB)</small></label>
                                                    <input type="file" name="file_arsip"
                                                        class="form-control-file border p-2 rounded"
                                                        accept="application/pdf, image/jpeg, image/png, image/jpg">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning text-white"><i
                                                        class="fas fa-save"></i> Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada dokumen di kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="m-3">{{ $arsip->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>

    <!-- MODAL TAMBAH DOKUMEN INTEGRASI PARENT-CHILD -->
    <div class="modal fade" id="modalTambahDokumen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('arsip.storeDokumen') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-success">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-success">Tambah Dokumen Baru</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body row">
                        <!-- 1. Induk Berkas -->
                        <div class="form-group col-md-12 mb-3 p-3 bg-light border rounded">
                            <label class="font-weight-bold text-primary"><i class="fas fa-link mr-1"></i> Pilih Induk
                                Berkas (Folder Utama) *</label>
                            <select name="judul_berkas" class="form-control border-primary" required>
                                <option value="">-- Pilih Folder Berkas --</option>
                                @foreach ($daftarFolder as $folder)
                                    <option value="{{ $folder->judul_berkas }}">{{ $folder->judul_berkas }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1 d-block">Tahun, Jenis Arsip, dan Kategori akan otomatis mewarisi
                                folder ini.</small>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Judul Isi (Nama Dokumen) <span class="text-danger">*</span></label>
                            <input type="text" name="judul_isi" class="form-control" required
                                placeholder="Cth: Laporan Awal...">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Validator</label>
                            <input type="text" name="validator" class="form-control" placeholder="Nama Validator...">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Pencipta (Sesuai Kamus)</label>
                            <select name="pencipta" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Bakosurtanal</option>
                                <option value="2">2 - Badan Informasi Geospasial</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Asal Berkas</label>
                            <input type="text" name="asal_berkas" class="form-control"
                                placeholder="Cth: KemenPAN-RB">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tahun Cipta</label>
                            <select name="tahun" class="form-control">
                                <option value="-">-- Ikuti Folder Induk --</option>
                                @for ($i = date('Y'); $i >= 1980; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- 3. Retensi & Jumlah -->
                        <div class="form-group col-md-4">
                            <label>Retensi Aktif</label>
                            <select name="retensi_aktif" class="form-control">
                                <option value="-">-- Pilih Tahun --</option>
                                @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Retensi Inaktif</label>
                            <select name="retensi_inaktif" class="form-control">
                                <option value="-">-- Pilih Tahun --</option>
                                @for ($i = date('Y') + 20; $i >= 1980; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="1">
                        </div>

                        <!-- 4. Lokasi Simpan -->
                        <div class="form-group col-md-4">
                            <label>Lokasi Ruangan</label>
                            <input type="text" name="lokasi_ruangan" class="form-control" placeholder="Cth: R.1.01">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tempat (Rak/Lemari)</label>
                            <input type="text" name="tempat" class="form-control" placeholder="Cth: R-1">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Folder / Boks</label>
                            <input type="text" name="folder_boks" class="form-control" placeholder="Cth: B.1.12">
                        </div>

                        <!-- 5. Klasifikasi -->
                        <div class="form-group col-md-6">
                            <label>Tingkat Klasifikasi</label>
                            <select name="tingkat_klasifikasi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Biasa</option>
                                <option value="2">2 - Rahasia</option>
                                <option value="3">3 - Terbatas</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jenis Klasifikasi</label>
                            <select name="jenis_klasifikasi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Bakosurtanal</option>
                                <option value="2">2 - Badan Informasi Geospasial</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Klasifikasi BIG (Kode/Angka)</label>
                            <input type="text" name="klasifikasi_big" class="form-control"
                                placeholder="Cth: 2 atau AW.01">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Klasif. Bakosurtanal (Kode/Angka)</label>
                            <input type="text" name="klasifikasi_bakosurtanal" class="form-control"
                                placeholder="Cth: 45 atau PR.01">
                        </div>

                        <!-- 6. Atribut Fisik & Kondisi -->
                        <div class="form-group col-md-4">
                            <label>Jenis Fisik</label>
                            <select name="jenis_fisik" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Berkas</option>
                                <option value="2">2 - Buku</option>
                                <option value="3">3 - CD</option>
                                <option value="4">4 - Jilid</option>
                                <option value="5">5 - Lembar</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sifat</label>
                            <select name="sifat" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Asli</option>
                                <option value="2">2 - Asli dan Copy</option>
                                <option value="3">3 - Copy</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Kondisi</label>
                            <select name="kondisi" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Baik</option>
                                <option value="2">2 - Kurang Baik</option>
                                <option value="3">3 - Rusak</option>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <select name="keterangan" class="form-control">
                                <option value="-">-- Pilih --</option>
                                <option value="1">1 - Dinilai Kembali</option>
                                <option value="2">2 - Permanen</option>
                                <option value="3">3 - Musnah</option>
                            </select>
                        </div>

                        <!-- 7. Upload -->
                        <div class="form-group col-md-12 mt-2">
                            <label class="font-weight-bold">Upload File Lampiran <small
                                    class="font-weight-normal text-muted">(PDF / JPG / PNG - Maks 10MB)</small></label>
                            <input type="file" name="file_arsip" class="form-control-file border p-2 rounded"
                                accept="application/pdf, image/jpeg, image/png, image/jpg">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan
                            Dokumen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
