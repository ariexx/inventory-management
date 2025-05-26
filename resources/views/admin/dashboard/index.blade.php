@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBarang ?? 0 }}</h3>
                    <p>Total Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('admin.barang.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBarangMasuk ?? 0 }}</h3>
                    <p>Barang Masuk (Bulan Ini)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-down"></i>
                </div>
                <a href="{{ route('admin.barang-masuk.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalBarangKeluar ?? 0 }}</h3>
                    <p>Barang Keluar (Bulan Ini)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-up"></i>
                </div>
                <a href="{{ route('admin.barang-keluar.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Laporan Cards -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Laporan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Laporan Stok Barang -->
                        <div class="col-md-4 mb-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laporan Stok Barang</span>
                                    <span class="info-box-number">{{ $totalBarang ?? 0 }} item</span>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.laporan.stok') }}" class="btn btn-sm btn-primary">Lihat Laporan</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Barang Masuk -->
                        <div class="col-md-4 mb-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-file-import"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laporan Barang Masuk</span>
                                    <span class="info-box-number">{{ $totalBarangMasukAll ?? 0 }} transaksi</span>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.laporan.barang-masuk') }}" class="btn btn-sm btn-success">Lihat Laporan</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Barang Keluar -->
                        <div class="col-md-4 mb-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger"><i class="fas fa-file-export"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laporan Barang Keluar</span>
                                    <span class="info-box-number">{{ $totalBarangKeluarAll ?? 0 }} transaksi</span>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.laporan.barang-keluar') }}" class="btn btn-sm btn-danger">Lihat Laporan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Items -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Barang Masuk Terbaru</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentBarangMasuk ?? [] as $item)
                                <tr>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->barang->nama }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.barang-masuk.index') }}" class="text-uppercase">Lihat Semua</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Barang Keluar Terbaru</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentBarangKeluar ?? [] as $item)
                                <tr>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->barang->nama }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.barang-keluar.index') }}" class="text-uppercase">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alert -->
    <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Peringatan Stok Rendah</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Minimal Stok</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lowStockItems ?? [] as $item)
                                <tr>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>{{ $item->minimal_stok }}</td>
                                    <td>
                                        <span class="badge badge-danger">Stok Rendah</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada barang dengan stok rendah</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            // Add any dashboard-specific JavaScript here
        });
    </script>
@endsection
