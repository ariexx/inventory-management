@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)

@section('title', 'Terima Barang')

@section('page-title')
    <h1>Terima Barang: {{ $pesananBarang->kode_pesanan }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Pesanan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Kode Pesanan</th>
                            <td>{{ $pesananBarang->kode_pesanan }}</td>
                        </tr>
                        <tr>
                            <th>Barang</th>
                            <td>{{ $pesananBarang->barang->kode }} - {{ $pesananBarang->barang->nama }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $pesananBarang->supplier->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Dipesan</th>
                            <td>{{ $pesananBarang->jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pemesanan</th>
                            <td>{{ $pesananBarang->tanggal_pemesanan->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengiriman Diharapkan</th>
                            <td>{{ $pesananBarang->tanggal_pengiriman_diharapkan ? $pesananBarang->tanggal_pengiriman_diharapkan->format('d-m-Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Form Penerimaan Barang</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route($layout . '.pesanan-barang.process-receive', $pesananBarang) }}" method="POST">
                        @csrf

                        <!-- Tanggal Terima -->
                        <div class="form-group">
                            <label for="tanggal">Tanggal Penerimaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="form-group">
                            <label for="jumlah">Jumlah Diterima <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', $pesananBarang->jumlah) }}" min="1" max="{{ $pesananBarang->jumlah }}" required>
                            <small class="form-text text-muted">Maksimal jumlah yang dapat diterima adalah {{ $pesananBarang->jumlah }}</small>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="form-group">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Konfirmasi Penerimaan
                            </button>
                            <a href="{{ route($layout . '.pesanan-barang.show', $pesananBarang) }}" class="btn btn-secondary">
                                <i class="fas fa-times-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Catatan:</h5>
                        <p>Setelah mengonfirmasi penerimaan barang, stok akan otomatis diperbarui dan pesanan akan ditandai sebagai "Diterima".</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
