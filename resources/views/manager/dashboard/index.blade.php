@extends('layouts.manager')

@section('title', 'Manager Dashboard')

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
                <a href="{{ route('manager.barang.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box {{ $masukPercentageChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                <div class="inner">
                    <h3>{{ $totalBarangMasukCurrentMonth ?? 0 }}</h3>
                    <p>
                        Barang Masuk (Bulan Ini)
                        <span class="badge badge-light">
                            {{ $masukPercentageChange >= 0 ? '+' : '' }}{{ number_format($masukPercentageChange, 1) }}%
                        </span>
                    </p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-down"></i>
                </div>
                <a href="{{ route('manager.barang-masuk.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box {{ $keluarPercentageChange >= 0 ? 'bg-primary' : 'bg-secondary' }}">
                <div class="inner">
                    <h3>{{ $totalBarangKeluarCurrentMonth ?? 0 }}</h3>
                    <p>
                        Barang Keluar (Bulan Ini)
                        <span class="badge badge-light">
                            {{ $keluarPercentageChange >= 0 ? '+' : '' }}{{ number_format($keluarPercentageChange, 1) }}%
                        </span>
                    </p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-up"></i>
                </div>
                <a href="{{ route('manager.barang-keluar.index') }}" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Recent Transactions and Low Stock -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt mr-1"></i>
                        Transaksi Terbaru
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Jenis</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentTransactions ?? [] as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction['tanggal'])->format('d/m/Y') }}</td>
                                    <td>{{ $transaction['kode'] }}</td>
                                    <td>{{ $transaction['barang'] }}</td>
                                    <td>{{ $transaction['jumlah'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction['type'] == 'masuk' ? 'success' : 'primary' }}">
                                            {{ $transaction['type'] == 'masuk' ? 'Masuk' : 'Keluar' }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction['detail'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada transaksi terbaru</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        <a href="{{ route('manager.laporan.barang-masuk') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-import"></i> Laporan Barang Masuk
                        </a>
                        <a href="{{ route('manager.laporan.barang-keluar') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-export"></i> Laporan Barang Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Low Stock Alert -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Peringatan Stok Rendah
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok</th>
                                <th>Min</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lowStockItems ?? [] as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td class="font-weight-bold {{ $item->stok < $item->minimal_stok ? 'text-danger' : '' }}">
                                        {{ $item->stok }}
                                    </td>
                                    <td>{{ $item->minimal_stok }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada barang dengan stok rendah</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(($lowStockItems ?? collect())->count() > 5)
                    <div class="card-footer text-center">
                        <a href="{{ route('manager.laporan.stok') }}?status=low" class="btn btn-sm btn-warning">
                            Lihat Semua
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <a href="{{ route('manager.barang-masuk.create') }}" class="btn btn-block btn-success btn-sm">
                                <i class="fas fa-plus-circle mr-1"></i> Barang Masuk
                            </a>
                        </div>
                        <div class="col-6 mb-2">
                            <a href="{{ route('manager.barang-keluar.create') }}" class="btn btn-block btn-primary btn-sm">
                                <i class="fas fa-minus-circle mr-1"></i> Barang Keluar
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('manager.barang.index') }}" class="btn btn-block btn-secondary btn-sm">
                                <i class="fas fa-boxes mr-1"></i> Data Barang
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('manager.laporan.stok') }}" class="btn btn-block btn-info btn-sm">
                                <i class="fas fa-chart-bar mr-1"></i> Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Data Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Bulanan
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <!-- Chart.js -->
    <link rel="stylesheet" href="{{ asset('plugins/chart.js/Chart.min.css') }}">
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            // Initialize chart (you'll need to populate this with actual data)
            var ctx = document.getElementById('barChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            backgroundColor: '#28a745',
                            data: [65, 59, 80, 81, 56, 55]
                        },
                        {
                            label: 'Barang Keluar',
                            backgroundColor: '#007bff',
                            data: [28, 48, 40, 19, 86, 27]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endsection
