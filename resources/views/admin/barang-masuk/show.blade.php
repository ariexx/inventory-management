@extends('layouts.admin')

@section('title', 'Detail Barang Masuk')

@section('content_header')
    <h1>Detail Barang Masuk</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Barang Masuk</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.barang-masuk.edit', $barangMasuk->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.barang-masuk.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Kode Transaksi</th>
                                    <td>{{ $barangMasuk->kode_transaksi }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td>{{ $barangMasuk->barang->nama ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Barang</th>
                                    <td>{{ $barangMasuk->barang->kode ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $barangMasuk->barang->kategori->nama ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Supplier</th>
                                    <td>{{ $barangMasuk->supplier->nama ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $barangMasuk->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($barangMasuk->tanggal)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $barangMasuk->keterangan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.barang-masuk.destroy', $barangMasuk->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus data barang masuk ini? Stok barang akan berkurang sejumlah data yang dihapus.');
        }
    </script>
@stop
