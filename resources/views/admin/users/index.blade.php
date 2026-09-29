@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Hak Akses Pengguna</h1>
        <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahUser">
            <i class="fas fa-user-plus fa-sm text-white-50 mr-1"></i> Tambah Pengguna Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Akun Terdaftar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Akun</th>
                            <th>Email</th>
                            <th>Status / Role</th>
                            <th>Tautan Identitas SDM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td class="font-weight-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'arsiparis_uk' ? 'success' : 'primary') }}">
                                        {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->arsiparis)
                                        <span class="text-primary"><i
                                                class="fas fa-user-check mr-1"></i>{{ $user->arsiparis->nama }}</span>
                                    @else
                                        <span class="text-danger italic small"><i
                                                class="fas fa-exclamation-triangle mr-1"></i>Belum dikaitkan</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info shadow-sm" data-toggle="modal"
                                        data-target="#editModal{{ $user->id }}">
                                        <i class="fas fa-edit mr-1"></i> Atur Akses
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit untuk Setiap User -->
                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title font-weight-bold text-primary">Atur Hak Akses:
                                                    {{ $user->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status Hak Akses (Role)</label>
                                                    <select name="role" class="form-control" required>
                                                        <option value="arsiparis_up"
                                                            {{ $user->role == 'arsiparis_up' ? 'selected' : '' }}>Arsiparis
                                                            Unit Pengolah (UP)</option>
                                                        <option value="arsiparis_uk"
                                                            {{ $user->role == 'arsiparis_uk' ? 'selected' : '' }}>Arsiparis
                                                            Unit Kearsipan (UK)</option>
                                                        <option value="admin"
                                                            {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tautkan ke Data SDM Kearsipan</label>
                                                    <select name="arsiparis_id" class="form-control">
                                                        <option value="">-- Jangan Tautkan (Kosongkan) --</option>
                                                        @foreach ($daftarArsiparis as $sdm)
                                                            <option value="{{ $sdm->id }}"
                                                                {{ $user->arsiparis_id == $sdm->id ? 'selected' : '' }}>
                                                                {{ $sdm->nama }} (NIP: {{ $sdm->nip ?? '-' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted mt-1 d-block">Menautkan akun ke data SDM
                                                        memungkinkan sistem mengenali jabatan pengguna saat login.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- ================= MODAL TAMBAH USER ================= -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-primary">Buat Akun Pengguna Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Masukkan nama...">
                        </div>
                        <div class="form-group">
                            <label>Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="email@big.com">
                        </div>
                        <div class="form-group">
                            <label>Password Akun <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required
                                placeholder="Minimal 8 karakter...">
                        </div>
                        <div class="form-group">
                            <label>Status Hak Akses (Role) <span class="text-danger">*</span></label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Akses --</option>
                                <option value="arsiparis_up">Arsiparis Unit Pengolah (UP)</option>
                                <option value="arsiparis_uk">Arsiparis Unit Kearsipan (UK)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tautkan ke Data SDM <small class="text-muted">(Opsional)</small></label>
                            <select name="arsiparis_id" class="form-control">
                                <option value="">-- Jangan Tautkan Dulu --</option>
                                @foreach ($daftarArsiparis as $sdm)
                                    <option value="{{ $sdm->id }}">{{ $sdm->nama }} (NIP:
                                        {{ $sdm->nip ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan
                            Akun</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
