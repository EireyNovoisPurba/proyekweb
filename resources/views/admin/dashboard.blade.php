@extends('layouts.admin')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Kearsipan - Badan Informasi Geospasial</h1>
    </div>

    <!-- Alert Selamat Datang -->
    <div class="alert alert-success shadow-sm mb-4" role="alert">
        Selamat datang di Sistem Informasi Kearsipan, <strong>{{ Auth::user()->name }}</strong>! Gunakan
        menu navigasi di sebelah kiri untuk mengelola Induk Berkas dan Arsip Kearsipan sesuai dengan pembagian unit kerja
        Anda.
    </div>

    <!-- Content Row: 4 Kartu Statistik Baru -->
    <div class="row">
        <!-- Card Arsip Vital -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Arsip Vital</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vital }} Arsip</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Arsip Terjaga -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Arsip Terjaga</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $terjaga }} Arsip</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shield-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Arsip Inaktif -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Arsip Inaktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inaktif }} Arsip</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-archive fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pegawai Terdaftar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Hak Akses Sistem</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users }} Pengguna</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MENGAMBIL DATA REKAPITULASI JABATAN (Permintaan Ibu Mentor) -->
    @php
        $rekapJabatan = \App\Models\Arsiparis::select(
            'jabatan',
            \Illuminate\Support\Facades\DB::raw('count(*) as total'),
        )
            ->groupBy('jabatan')
            ->orderBy('total', 'desc')
            ->get();
    @endphp

    <!-- Baris Baru untuk Menampilkan Rekapitulasi -->
    <div class="row">
        <!-- Kotak Rekapitulasi Jabatan diletakkan dalam kolom lebar -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-2"></i>Rekapitulasi SDM
                        Berdasarkan Jabatan</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($rekapJabatan as $rekap)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-gray-800 font-weight-bold">{{ $rekap->jabatan }}</span>
                                <span class="badge badge-primary badge-pill px-3 py-2"
                                    style="font-size: 14px;">{{ $rekap->total }} Orang</span>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted italic">Belum ada data SDM Kearsipan. Silakan import
                                dari menu Data Arsiparis.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================================================= -->
@endsection
