@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)


@section('title', 'Daftar Pesanan Barang')

@section('page-title')
    <div class="d-flex justify-content-between">
        <h1>Daftar Pesanan Barang</h1>
        @if(!auth()->user()->is_manager())
        <a href="{{ route('admin.pesanan-barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Tambah Pesanan
        </a>
        @endif
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="pesanan-table">
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Barang</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                            <tr>
                                <td>{{ $item->kode_pesanan }}</td>
                                <td>{{ $item->barang->nama }}</td>
                                <td>{{ $item->supplier->nama }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->tanggal_pemesanan->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'received' => 'success',
                                            'cancelled' => 'danger'
                                        ][$item->status] ?? 'secondary';

                                        $statusLabel = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'received' => 'Diterima',
                                            'cancelled' => 'Dibatalkan'
                                        ][$item->status] ?? $item->status;
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route($layout . '.pesanan-barang.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route($layout . '.pesanan-barang.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($item->status === 'approved')
                                            <a href="{{ route($layout . '.pesanan-barang.receive', $item) }}" class="btn btn-sm btn-success" title="Terima Barang">
                                                <i class="fas fa-truck-loading"></i>
                                            </a>
                                        @endif
                                        @if($item->status !== 'received')
                                            <form action="{{ route($layout . '.pesanan-barang.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pesanan-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[ 4, "desc" ]] // Sort by tanggal_pemesanan column by default
            });

            // Auto close alert after 5 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
@stop
