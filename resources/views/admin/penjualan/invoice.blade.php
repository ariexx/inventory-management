@extends('layouts.admin')

@section('title', 'Invoice #' . $penjualan->kode_transaksi)

@section('content')
<div class="d-flex justify-content-between">
    <h1>Invoice #{{ $penjualan->kode_transaksi }}</h1>
    <div>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
        <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> {{ $companyInfo['name'] }}
                <small class="float-right">Tanggal: {{ Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</small>
            </h4>
        </div>
    </div>

    <!-- info row -->
    <div class="row invoice-info mt-3">
        <div class="col-sm-4 invoice-col">
            Dari
            <address>
                <strong>{{ $companyInfo['name'] }}</strong><br>
                {{ $companyInfo['address'] }}<br>
                Phone: {{ $companyInfo['phone'] }}<br>
                Email: {{ $companyInfo['email'] }}
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            Kepada
            <address>
                <strong>{{ $penjualan->nama_pembeli ?? 'Pembeli Umum' }}</strong><br>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Invoice #{{ $penjualan->kode_transaksi }}</b><br>
            <b>Tanggal Transaksi:</b> {{ Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}<br>
        </div>
    </div>

    <!-- Table row -->
    <div class="row mt-4">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Kode Barang</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $penjualan->barang->nama }}</td>
                        <td>{{ $penjualan->barang->kode }}</td>
                        <td>{{ $penjualan->jumlah }}</td>
                        <td>Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <p class="lead">Keterangan:</p>
            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                {{ $penjualan->keterangan ?? 'Tidak ada keterangan' }}
            </p>
        </div>
        <div class="col-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td><strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .main-header, .main-sidebar, .content-header, .main-footer, .btn {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .invoice {
            padding: 10px !important;
            margin: 0 !important;
            width: 100% !important;
        }
    }
</style>

@stop

@section('css')
<style>
    .invoice {
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
        position: relative;
    }
</style>
@stop
