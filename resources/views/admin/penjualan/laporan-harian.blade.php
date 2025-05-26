@extends('layouts.admin')

@section('title', 'Laporan Penjualan Harian')

@section('content_header')
    <h1>Laporan Penjualan Harian</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Laporan Penjualan Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</h3>
                <div>
                    <form action="{{ route('admin.penjualan.laporan') }}" method="GET" class="form-inline">
                        <input type="hidden" name="periode" value="harian">
                        <div class="input-group mr-2">
                            <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('admin.penjualan.laporan', ['periode' => 'harian']) }}" class="btn btn-primary active">Harian</a>
{{--                            <a href="{{ route('admin.penjualan.laporan', ['periode' => 'bulanan']) }}" class="btn btn-primary">Bulanan</a>--}}
{{--                            <a href="{{ route('admin.penjualan.laporan', ['periode' => 'tahunan']) }}" class="btn btn-primary">Tahunan</a>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Barang Terjual</span>
                            <span class="info-box-number">{{ $totalPenjualan }} items</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Omset</span>
                            <span class="info-box-number">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Transaksi</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="15%">Harga Satuan</th>
                        <th width="15%">Total Harga</th>
                        <th width="20%">Pembeli</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($penjualans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $item->nama_pembeli ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data penjualan pada tanggal ini</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print mr-1"></i> Cetak Laporan</button>
                <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>
        </div>
    </div>
@stop
