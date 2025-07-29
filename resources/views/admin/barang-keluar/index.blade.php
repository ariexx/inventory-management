@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)


@section('title', 'Data Barang Keluar')

@section('content_header')
    <h1>Data Barang Keluar</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Barang Keluar</h3>
                @if(auth()->user()->is_admin())
                    <a href="{{ route('admin.barang-keluar.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Barang Keluar
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {!! session('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-1"></i> {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Transaksi</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="15%">Tujuan</th>
                        <th width="15%">Tanggal</th>
                        @if(auth()->user()->is_admin())
                            <th width="10%">Aksi</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($barangKeluar as $index => $item)
                        <tr>
                            <td>{{ $barangKeluar->firstItem() + $index }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->tujuan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            @if(auth()->user()->is_admin())
                                <td>
                                        <a href="{{ route('admin.barang-keluar.show', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.barang-keluar.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.barang-keluar.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang keluar</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $barangKeluar->links() }}
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
