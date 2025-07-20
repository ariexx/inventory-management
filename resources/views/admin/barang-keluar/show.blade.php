@extends('layouts.admin')

@section('title', 'Detail Barang Keluar')

@section('content_header')
    <h1>Detail Barang Keluar</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Barang Keluar</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.barang-keluar.edit', $barangKeluar->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="javascript:window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print mr-1"></i> Print
                        </a>
                        <a href="{{ route('admin.barang-keluar.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $barangKeluar->kode_transaksi }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td>{{ $barangKeluar->barang->nama ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Barang</th>
                                    <td>{{ $barangKeluar->barang->kode ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $barangKeluar->barang->kategori->nama ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Jumlah</th>
                                    <td>{{ $barangKeluar->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($barangKeluar->tanggal)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tujuan</th>
                                    <td>{{ $barangKeluar->tujuan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Penerima</th>
                                    <td>{{ $barangKeluar->penerima ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $barangKeluar->keterangan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.barang-keluar.destroy', $barangKeluar->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
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
            return confirm('Apakah Anda yakin ingin menghapus data barang keluar ini? Stok barang akan bertambah sejumlah data yang dihapus.');
        }
    </script>
@stop
