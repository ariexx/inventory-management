@extends('layouts.admin')

@section('title', 'Detail Barang')

@section('page-title', 'Detail Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}">Data Barang</a></li>
    <li class="breadcrumb-item active">Detail Barang</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Barang</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Kode Barang</th>
                                    <td>{{ $barang->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td>{{ $barang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $barang->kategori->nama ?? 'Tidak Ada' }}</td>
                                </tr>
                                <tr>
                                    <th>Stok Saat Ini</th>
                                    <td>
                                        <span class="badge {{ $barang->stok <= $barang->minimal_stok ? 'badge-danger' : 'badge-success' }} p-2" style="font-size: 14px;">
                                            {{ $barang->stok }} {{ $barang->satuan }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Minimal Stok</th>
                                    <td>{{ $barang->minimal_stok }} {{ $barang->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Satuan</th>
                                    <td>{{ $barang->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($barang->stok <= 0)
                                            <span class="badge badge-danger">Habis</span>
                                        @elseif($barang->stok <= $barang->minimal_stok)
                                            <span class="badge badge-warning">Stok Rendah</span>
                                        @else
                                            <span class="badge badge-success">Stok Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">Riwayat Transaksi Terakhir</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="barang-masuk-tab" data-toggle="pill" href="#barang-masuk" role="tab" aria-controls="barang-masuk" aria-selected="true">Barang Masuk</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="barang-keluar-tab" data-toggle="pill" href="#barang-keluar" role="tab" aria-controls="barang-keluar" aria-selected="false">Barang Keluar</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="barang-masuk" role="tabpanel" aria-labelledby="barang-masuk-tab">
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jumlah</th>
                                                        <th>Supplier</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($barang->barangMasuk && $barang->barangMasuk->count() > 0)
                                                        @foreach($barang->barangMasuk()->latest()->take(5)->get() as $masuk)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($masuk->tanggal)->format('d/m/Y') }}</td>
                                                                <td>{{ $masuk->jumlah }} {{ $barang->satuan }}</td>
                                                                <td>{{ $masuk->supplier->nama ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="3" class="text-center">Tidak ada riwayat</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="barang-keluar" role="tabpanel" aria-labelledby="barang-keluar-tab">
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jumlah</th>
                                                        <th>Tujuan</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($barang->barangKeluar && $barang->barangKeluar->count() > 0)
                                                        @foreach($barang->barangKeluar()->latest()->take(5)->get() as $keluar)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($keluar->tanggal)->format('d/m/Y') }}</td>
                                                                <td>{{ $keluar->jumlah }} {{ $barang->satuan }}</td>
                                                                <td>{{ $keluar->tujuan ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="3" class="text-center">Tidak ada riwayat</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
