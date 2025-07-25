@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('page-title', 'Tambah Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.barang.index') }}">Data Barang</a></li>
    <li class="breadcrumb-item active">Tambah Barang</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Data Barang</h3>
                </div>
                <form action="{{ route('admin.barang.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="kode">Kode Barang</label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode') }}" required>
                            @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori Barang</label>
                            <select class="form-control @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga_beli">Harga Jual</label>
                            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', 0) }}" min="0" required>
                            @error('harga_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok">Stok Awal</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                                    @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="minimal_stok">Minimal Stok</label>
                                    <input type="number" class="form-control @error('minimal_stok') is-invalid @enderror" id="minimal_stok" name="minimal_stok" value="{{ old('minimal_stok', 0) }}" min="0" required>
                                    @error('minimal_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="satuan">Satuan</label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan') }}" required>
                            @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: pcs, kg, box, dll</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
