<!-- resources/views/layouts/manager.blade.php -->
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
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.css') }}">
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
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                @php
                    $pendingOrdersCount = \App\Models\PesananBarang::where('status', 'pending')->count();
                    $restockRequestsCount = \App\Models\RestockRequest::where('status', 'pending')->count();
                    $totalNotifications = $pendingOrdersCount + $restockRequestsCount;
                @endphp
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    @if($totalNotifications > 0)
                        <span class="badge badge-danger navbar-badge">{{ $totalNotifications }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ $totalNotifications }} Notifikasi</span>
                    <div class="dropdown-divider"></div>
                    @if($pendingOrdersCount > 0)
                        <a href="{{ route('manager.pesanan-barang.index') }}" class="dropdown-item">
                            <i class="fas fa-shopping-bag mr-2"></i> {{ $pendingOrdersCount }} pesanan menunggu persetujuan
                        </a>
                    @endif
                    @if($restockRequestsCount > 0)
                        <a href="{{ route('manager.restock-requests.index') }}" class="dropdown-item">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ $restockRequestsCount }} permintaan restock
                        </a>
                    @endif
                </div>
            </li>

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
        <a href="{{ route('manager.dashboard') }}" class="brand-link">
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
                        <a href="{{ route('manager.dashboard') }}" class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('manager.pesanan-barang.index') }}" class="nav-link {{ request()->routeIs('manager.pesanan-barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>
                                Pesanan Suppplier
                                @if($pendingOrdersCount > 0)
                                    <span class="badge badge-danger right">{{ $pendingOrdersCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>

{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('manager.restock-requests.index') }}" class="nav-link {{ request()->routeIs('manager.restock-requests.*') ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-clipboard-check"></i>--}}
{{--                            <p>--}}
{{--                                Permintaan Restock--}}
{{--                                @if($restockRequestsCount > 0)--}}
{{--                                    <span class="badge badge-danger right">{{ $restockRequestsCount }}</span>--}}
{{--                                @endif--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="nav-item">
                        <a href="{{ route('manager.barang.index') }}" class="nav-link {{ request()->routeIs('manager.barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('manager.barang-masuk.index') }}" class="nav-link {{ request()->routeIs('manager.barang-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-circle-down"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('manager.barang-keluar.index') }}" class="nav-link {{ request()->routeIs('manager.barang-keluar.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-circle-up"></i>
                            <p>Barang Keluar</p>
                        </a>
                    </li>

                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('manager.laporan.stok') }}" class="nav-link {{ request()->routeIs('manager.laporan.stok') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Laporan Stok Barang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manager.laporan.barang-masuk') }}" class="nav-link {{ request()->routeIs('manager.laporan.barang-masuk') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-import"></i>
                            <p>Laporan Barang Masuk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manager.laporan.barang-keluar') }}" class="nav-link {{ request()->routeIs('manager.laporan.barang-keluar') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-export"></i>
                            <p>Laporan Barang Keluar</p>
                        </a>
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
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.js') }}"></script>
@yield('scripts')
</body>
</html>
