<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bahai"></i>
                            <p>Data Supplier</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pesanan-barang.index') }}" class="nav-link {{ request()->routeIs('admin.pesanan-barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Pembelian Supplier</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.barang.index') }}" class="nav-link {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.barang-masuk.index') }}" class="nav-link {{ request()->routeIs('admin.barang-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-circle-down"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.barang-keluar.index') }}" class="nav-link {{ request()->routeIs('admin.barang-keluar.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-circle-up"></i>
                            <p>Barang Keluar</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Kategori</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Data User</p>
                        </a>
                    </li>


{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.admin.login-activities.index') }}" class="nav-link {{ request()->routeIs('admin.admin.login-activities.*') ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-clock"></i>--}}
{{--                            <p>Aktifitas</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}


                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.stok') }}" class="nav-link {{ request()->routeIs('admin.laporan.stok') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Laporan Stok Barang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.barang-masuk') }}" class="nav-link {{ request()->routeIs('admin.laporan.barang-masuk') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-import"></i>
                            <p>Laporan Barang Masuk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.barang-keluar') }}" class="nav-link {{ request()->routeIs('admin.laporan.barang-keluar') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-export"></i>
                            <p>Laporan Barang Keluar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.penjualan.index') }}" class="nav-link {{ request()->routeIs('admin.penjualan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Penjualan</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.penjualan.laporan*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.penjualan.laporan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.penjualan.laporan') }}" class="nav-link {{ request()->routeIs('admin.penjualan.laporan') && !request('periode') || request('periode') == 'harian' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Harian</p>
                                </a>
                            </li>
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('admin.penjualan.laporan', ['periode' => 'bulanan']) }}" class="nav-link {{ request()->routeIs('admin.penjualan.laporan') && request('periode') == 'bulanan' ? 'active' : '' }}">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Laporan Bulanan</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li class="nav-item">
                                <a href="{{ route('admin.penjualan.laporan-stok') }}" class="nav-link {{ request()->routeIs('admin.penjualan.laporan-stok') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Stok</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.name') }}</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@yield('scripts')
</body>
</html>
