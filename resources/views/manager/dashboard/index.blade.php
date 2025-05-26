<!-- resources/views/manager/dashboard/index.blade.php -->
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
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBarang }}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="#" class="small-box-footer">Informasi Stok <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBarangMasuk }}</h3>
                    <p>Barang Masuk (Bulan Ini)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-down"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalBarangKeluar }}</h3>
                    <p>Barang Keluar (Bulan Ini)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-circle-up"></i>
                </div>
                <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lowStockCount }}</h3>
                    <p>Stok Kritis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="#" class="small-box-footer">Perlu Restock <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Sales Charts -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Grafik Penjualan Bulanan {{ date('Y') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Produk Terlaris (Bulan Ini)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="productChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- Daily Sales Report Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Laporan Penjualan Harian ({{ Carbon\Carbon::now()->format('F Y') }})
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="dailySalesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box mb-3 bg-warning">
                                <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Barang Terjual</span>
                                    <span class="info-box-number">{{ number_format($totalSoldGoods) }} items</span>
                                </div>
                            </div>
                            <div class="info-box mb-3 bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Omset</span>
                                    <span class="info-box-number">Rp {{ number_format($totalTurnover, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales Detail Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Penjualan Harian</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Barang</th>
                            <th>Total Omset</th>
                            <th>Detail</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dailySales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                                <td>{{ number_format($sale->total_items) }} items</td>
                                <td>Rp {{ number_format($sale->total_turnover, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('manager.laporan.barang-keluar') }}?date={{ $sale->date }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Status for Inventory Decisions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Status Stok untuk Keputusan Restock
                    </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Minimal Stok</th>
                            <th>Status</th>
                            <th>Rekomendasi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stockStatus as $item)
                            <tr class="{{ $item->stok < $item->minimal_stok ? 'bg-warning' : '' }}">
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kategori->nama ?? 'N/A' }}</td>
                                <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                <td>{{ $item->minimal_stok }} {{ $item->satuan }}</td>
                                <td>
                                    @if($item->stok < $item->minimal_stok)
                                        <span class="badge badge-danger">Stok Kritis</span>
                                    @elseif($item->stok < ($item->minimal_stok * 1.5))
                                        <span class="badge badge-warning">Stok Rendah</span>
                                    @else
                                        <span class="badge badge-success">Stok Aman</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->stok < $item->minimal_stok)
                                        <span class="text-danger">Segera Restock</span>
                                    @elseif($item->stok < ($item->minimal_stok * 1.5))
                                        <span class="text-warning">Rencanakan Restock</span>
                                    @else
                                        <span class="text-success">Tidak Perlu Restock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/chart.js/Chart.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            // Sales Chart
            var salesChartCanvas = document.getElementById('salesChart').getContext('2d');

            var salesChartData = {
                labels: @json($salesChartLabels),
                datasets: [
                    {
                        label: 'Jumlah Barang Keluar',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: true,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @json($salesChartData)
                    }
                ]
            };

            var salesChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            };

            new Chart(salesChartCanvas, {
                type: 'bar',
                data: salesChartData,
                options: salesChartOptions
            });

            // Product Chart
            var productChartCanvas = document.getElementById('productChart').getContext('2d');
            var productChartData = {
                labels: @json($productChartLabels),
                datasets: [
                    {
                        data: @json($productChartData),
                        backgroundColor: [
                            '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                            '#d2d6de', '#6c757d', '#343a40', '#007bff', '#17a2b8'
                        ],
                    }
                ]
            };
            var productChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: 'right',
                }
            };

            new Chart(productChartCanvas, {
                type: 'doughnut',
                data: productChartData,
                options: productChartOptions
            });

            // Search functionality
            $("input[name='table_search']").on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        // Daily Sales Chart - remove the duplicate chart creation
        var dailySalesChartCanvas = document.getElementById('dailySalesChart').getContext('2d');

        var dailySalesChart = new Chart(dailySalesChartCanvas, {
            type: 'line',
            data: {
                labels: @json($dailySalesLabels),
                datasets: [
                    {
                        label: 'Total Barang Terjual',
                        data: @json($dailySalesData),
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        borderColor: '#ffc107',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#ffc107',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Omset (Dalam Ribuan Rp)',
                        data: @json($dailyTurnoverData).map(value => value / 1000),
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: '#28a745',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#28a745',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';

                                if (label) {
                                    label += ': ';
                                }

                                if (context.datasetIndex === 0) {
                                    label += context.raw.toFixed(0) + ' items';
                                } else {
                                    label += 'Rp ' + (context.raw * 1000).toLocaleString('id-ID');
                                }

                                return label;
                            }
                        }
                    }
                }
            }
        });

            // Search functionality
            $("input[name='table_search']").on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
    </script>
@endsection
