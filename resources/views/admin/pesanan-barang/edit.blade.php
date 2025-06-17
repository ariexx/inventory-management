@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)

@section('title', 'Edit Pesanan Barang')

@section('page-title')
    <h1>Edit Pesanan: {{ $pesananBarang->kode_pesanan }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route($layout . '.pesanan-barang.update', $pesananBarang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <!-- Barang -->
                        <div class="form-group">
                            <label for="barang_id">Barang <span class="text-danger">*</span></label>
                            <select name="barang_id" id="barang_id" class="form-control select2 @error('barang_id') is-invalid @enderror" required {{ $pesananBarang->status === 'received' ? 'disabled' : '' }}>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->id }}" {{ (old('barang_id', $pesananBarang->barang_id) == $item->id) ? 'selected' : '' }}>
                                        {{ $item->kode }} - {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if($pesananBarang->status === 'received')
                                <input type="hidden" name="barang_id" value="{{ $pesananBarang->barang_id }}">
                            @endif
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div class="form-group">
                            <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2 @error('supplier_id') is-invalid @enderror" required {{ $pesananBarang->status === 'received' ? 'disabled' : '' }}>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($supplier as $item)
                                    <option value="{{ $item->id }}" {{ (old('supplier_id', $pesananBarang->supplier_id) == $item->id) ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if($pesananBarang->status === 'received')
                                <input type="hidden" name="supplier_id" value="{{ $pesananBarang->supplier_id }}">
                            @endif
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="form-group">
                            <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', $pesananBarang->jumlah) }}" min="1" required {{ $pesananBarang->status === 'received' ? 'readonly' : '' }}>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tanggal Pemesanan -->
                        <div class="form-group">
                            <label for="tanggal_pemesanan">Tanggal Pemesanan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemesanan" id="tanggal_pemesanan" class="form-control @error('tanggal_pemesanan') is-invalid @enderror"
                                   value="{{ old('tanggal_pemesanan', $pesananBarang->tanggal_pemesanan->format('Y-m-d')) }}"
                                   required {{ $pesananBarang->status === 'received' ? 'readonly' : '' }}>
                            @error('tanggal_pemesanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Pengiriman Diharapkan -->
                        <div class="form-group">
                            <label for="tanggal_pengiriman_diharapkan">Tanggal Pengiriman Diharapkan</label>
                            <input type="date" name="tanggal_pengiriman_diharapkan" id="tanggal_pengiriman_diharapkan"
                                   class="form-control @error('tanggal_pengiriman_diharapkan') is-invalid @enderror"
                                   value="{{ old('tanggal_pengiriman_diharapkan', $pesananBarang->tanggal_pengiriman_diharapkan ? $pesananBarang->tanggal_pengiriman_diharapkan->format('Y-m-d') : '') }}"
                                   {{ $pesananBarang->status === 'received' ? 'readonly' : '' }}>
                            @error('tanggal_pengiriman_diharapkan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga Satuan -->
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="harga_satuan" id="harga_satuan"
                                       class="form-control @error('harga_satuan') is-invalid @enderror"
                                       value="{{ old('harga_satuan', $pesananBarang->harga_satuan) }}" min="0" step="0.01"
                                       {{ $pesananBarang->status === 'received' ? 'readonly' : '' }}>
                            </div>
                            @error('harga_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ (old('status', $pesananBarang->status) == 'pending') ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ (old('status', $pesananBarang->status) == 'approved') ? 'selected' : '' }}>Disetujui</option>
                                <option value="received" {{ (old('status', $pesananBarang->status) == 'received') ? 'selected' : '' }} {{ $pesananBarang->status !== 'received' ? 'disabled' : '' }}>Diterima</option>
                                <option value="cancelled" {{ (old('status', $pesananBarang->status) == 'cancelled') ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Keterangan -->
                        <div class="form-group">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pesananBarang->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route($layout . '.pesanan-barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
