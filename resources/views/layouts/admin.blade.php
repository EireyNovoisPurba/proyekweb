<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ url('/admin/dashboard') }}">
                <div class="sidebar-brand-icon">
                    <!-- Memanggil gambar logo BIG -->
                    <img src="{{ asset('admin/img/logo-big.png') }}" alt="Biro UK"
                        style="width: 45px; object-fit: contain;">
                </div>
                <div class="sidebar-brand-text mx-3">Biro UK<sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/admin/dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Data Arsiparis -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('arsiparis.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Arsiparis</span>
                </a>
            </li>

            <!-- Nav Item - Pojok Edukasi / Fun Fact -->
            <li class="nav-item {{ request()->routeIs('fun-fact.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('fun-fact.index') }}">
                    <i class="fas fa-fw fa-lightbulb"></i>
                    <span>Pojok Edukasi </span>
                </a>
            </li>
            <!-- Nav Item - Kelola Berita -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('berita.index') }}">
                    <i class="fas fa-fw fa-newspaper"></i>
                    <span>Kelola Berita</span>
                </a>
            </li>
            <!-- ================= 1. MANAJEMEN INDUK BERKAS (FOLDER) ================= -->
            <div class="sidebar-heading mt-3">
                Manajemen Berkas
            </div>

            <!-- Nav Item - Uraian Informasi Berkas (Arsip Umum) -->
            <li class="nav-item {{ request()->routeIs('arsip.index') ? 'active' : '' }}">
                <a class="nav-link pb-1" href="{{ route('arsip.index') }}">
                    <i class="fas fa-fw fa-folder-open"></i>
                    <span>Uraian Informasi Berkas</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider mt-2 mb-2">

            <!-- ================= 2. PENGELOLAAN DOKUMEN (ISI BERKAS) ================= -->
            <div class="sidebar-heading">
                Kelola Arsip
            </div>

            <!-- Nav Item - Arsip Vital (Dropdown Accordion) -->
            @can('input-vital')
                <li class="nav-item {{ request()->routeIs('admin.arsip_vital.*') ? 'active' : '' }}">
                    <a class="nav-link pb-1 collapsed" href="#collapseArsipVital"
                        data-toggle="collapse" data-target="#collapseArsipVital"
                        aria-expanded="{{ request()->routeIs('admin.arsip_vital.*') ? 'true' : 'false' }}"
                        aria-controls="collapseArsipVital">
                        <i class="fas fa-fw fa-exclamation-triangle"></i>
                        <span>Arsip Vital</span>
                    </a>
                    <div id="collapseArsipVital"
                        class="collapse {{ request()->routeIs('admin.arsip_vital.*') ? 'show' : '' }}"
                        aria-labelledby="headingArsipVital" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Menu Arsip Vital:</h6>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_vital.index') || request()->routeIs('admin.arsip_vital.create') || request()->routeIs('admin.arsip_vital.show') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_vital.index') }}">
                                <i class="fas fa-folder-open fa-sm mr-1"></i> Uraian Informasi Berkas
                            </a>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_vital.daftar-arsip') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_vital.daftar-arsip') }}">
                                <i class="fas fa-file-alt fa-sm mr-1"></i> Uraian Informasi Arsip
                            </a>
                        </div>
                    </div>
                </li>
            @endcan

            <!-- Nav Item - Arsip Terjaga (Dropdown Accordion) -->
            @can('input-terjaga')
                <li class="nav-item {{ request()->routeIs('admin.arsip_terjaga.*') ? 'active' : '' }}">
                    <a class="nav-link pb-1 collapsed" href="#collapseArsipTerjaga"
                        data-toggle="collapse" data-target="#collapseArsipTerjaga"
                        aria-expanded="{{ request()->routeIs('admin.arsip_terjaga.*') ? 'true' : 'false' }}"
                        aria-controls="collapseArsipTerjaga">
                        <i class="fas fa-fw fa-shield-alt"></i>
                        <span>Arsip Terjaga</span>
                    </a>
                    <div id="collapseArsipTerjaga"
                        class="collapse {{ request()->routeIs('admin.arsip_terjaga.*') ? 'show' : '' }}"
                        aria-labelledby="headingArsipTerjaga" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Menu Arsip Terjaga:</h6>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_terjaga.index') || request()->routeIs('admin.arsip_terjaga.create') || request()->routeIs('admin.arsip_terjaga.show') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_terjaga.index') }}">
                                <i class="fas fa-folder-open fa-sm mr-1"></i> Uraian Informasi Berkas
                            </a>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_terjaga.daftar-arsip') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_terjaga.daftar-arsip') }}">
                                <i class="fas fa-file-alt fa-sm mr-1"></i> Uraian Informasi Arsip
                            </a>
                        </div>
                    </div>
                </li>
            @endcan

            <!-- Nav Item - Arsip Inaktif (Dropdown Accordion) -->
            @can('input-inaktif')
                <li class="nav-item {{ request()->routeIs('admin.arsip_inaktif.*') ? 'active' : '' }}">
                    <a class="nav-link pb-1 collapsed" href="#collapseArsipInaktif"
                        data-toggle="collapse" data-target="#collapseArsipInaktif"
                        aria-expanded="{{ request()->routeIs('admin.arsip_inaktif.*') ? 'true' : 'false' }}"
                        aria-controls="collapseArsipInaktif">
                        <i class="fas fa-fw fa-archive"></i>
                        <span>Arsip Inaktif</span>
                    </a>
                    <div id="collapseArsipInaktif"
                        class="collapse {{ request()->routeIs('admin.arsip_inaktif.*') ? 'show' : '' }}"
                        aria-labelledby="headingArsipInaktif" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Menu Arsip Inaktif:</h6>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_inaktif.index') || request()->routeIs('admin.arsip_inaktif.create') || request()->routeIs('admin.arsip_inaktif.show') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_inaktif.index') }}">
                                <i class="fas fa-folder-open fa-sm mr-1"></i> Uraian Informasi Berkas
                            </a>
                            <a class="collapse-item {{ request()->routeIs('admin.arsip_inaktif.daftar-arsip') ? 'active' : '' }}"
                                href="{{ route('admin.arsip_inaktif.daftar-arsip') }}">
                                <i class="fas fa-file-alt fa-sm mr-1"></i> Uraian Informasi Arsip
                            </a>
                        </div>
                    </div>
                </li>
            @endcan
            @can('is-admin')
                <li class="nav-item {{ request()->is('admin/users*') || request()->is('users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-fw fa-users-cog"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
            @endcan
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>-->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- Menampilkan Nama User -->
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>

                                <!-- Menampilkan Foto Profil / Gambar Default -->
                                <img class="img-profile rounded-circle shadow-sm" style="object-fit: cover;"
                                    src="{{ Auth::user()->foto_profil ? asset('foto_profil/' . Auth::user()->foto_profil) : asset('admin/img/undraw_profile.svg') }}">
                            </a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <!-- Link ke Halaman Profile -->
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- Tombol Logout -->
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Konten dari index.blade.php akan masuk ke sini -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>

                    <!-- Form Logout Bawaan Laravel Breeze -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
