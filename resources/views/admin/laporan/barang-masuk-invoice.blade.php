@extends('layouts.admin')

@section('title', 'Bukti Penerimaan #' . $barangMasuk->kode_transaksi)

@section('content')
    <div class="d-flex justify-content-between">
        <h1>Bukti Penerimaan #{{ $barangMasuk->kode_transaksi }}</h1>
        <div>
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="{{ route(auth()->user()->level . '.laporan.barang-masuk') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> {{ $companyInfo['name'] }}
                    <small class="float-right">Tanggal: {{ Carbon\Carbon::parse($barangMasuk->tanggal)->format('d/m/Y') }}</small>
                </h4>
            </div>
        </div>

        <!-- info row -->
        <div class="row invoice-info mt-3">
            <div class="col-sm-4 invoice-col">
                Penerima
                <address>
                    <strong>{{ $companyInfo['name'] }}</strong><br>
                    {{ $companyInfo['address'] }}<br>
                    Phone: {{ $companyInfo['phone'] }}<br>
                    Email: {{ $companyInfo['email'] }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                Supplier
                <address>
                    <strong>{{ $barangMasuk->supplier->nama ?? 'N/A' }}</strong><br>
                    {{ $barangMasuk->supplier->alamat ?? 'N/A' }}<br>
                    Phone: {{ $barangMasuk->supplier->telepon ?? 'N/A' }}<br>
                    Email: {{ $barangMasuk->supplier->email ?? 'N/A' }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Kode Transaksi: #{{ $barangMasuk->kode_transaksi }}</b><br>
                <b>Tanggal Penerimaan:</b> {{ Carbon\Carbon::parse($barangMasuk->tanggal)->format('d/m/Y') }}<br>
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
{{--                        <th>Harga Satuan</th>--}}
{{--                        <th>Subtotal</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $barangMasuk->barang->nama }}</td>
                        <td>{{ $barangMasuk->barang->kode }}</td>
                        <td>{{ $barangMasuk->jumlah }}</td>
{{--                        <td>Rp {{ number_format($barangMasuk->harga_satuan ?? $barangMasuk->barang->harga_beli, 0, ',', '.') }}</td>--}}
{{--                        <td>Rp {{ number_format(($barangMasuk->harga_satuan ?? $barangMasuk->barang->harga_beli) * $barangMasuk->jumlah, 0, ',', '.') }}</td>--}}
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <p class="lead">Keterangan:</p>
                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    {{ $barangMasuk->keterangan ?? 'Tidak ada keterangan' }}
                </p>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Total:</th>
                            <td><strong>Rp {{ number_format(($barangMasuk->harga_satuan ?? $barangMasuk->barang->harga_beli) * $barangMasuk->jumlah, 0, ',', '.') }}</strong></td>
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

@section('styles')
    <style>
        .invoice {
            background-color: #fff;
            border: 1px solid rgba(0,0,0,.125);
            position: relative;
        }
    </style>
@stop
