@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('page-title', 'Detail Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori Barang</a></li>
    <li class="breadcrumb-item active">Detail Kategori</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kategori</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Kategori</th>
                            <td>{{ $kategori->nama }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $kategori->keterangan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Barang</th>
                            <td>{{ $kategori->barang->count() }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat pada</th>
                            <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui pada</th>
                            <td>{{ $kategori->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    @if($kategori->barang->count() == 0)
                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Barang dalam Kategori "{{ $kategori->nama }}"</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($kategori->barang as $barang)
                                <tr class="{{ $barang->stok <= $barang->minimal_stok ? 'table-warning' : '' }}">
                                    <td>{{ $barang->kode }}</td>
                                    <td>{{ $barang->nama }}</td>
                                    <td>{{ $barang->stok }}</td>
                                    <td>{{ $barang->satuan }}</td>
                                    <td>
                                        <a href="{{ route('admin.barang.show', $barang->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada barang dalam kategori ini</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($kategori->barang->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.barang.index') }}?kategori={{ $kategori->id }}" class="btn btn-primary">
                            <i class="fas fa-list mr-1"></i> Lihat Semua Barang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus kategori ini?');
        }
    </script>
@endsection
