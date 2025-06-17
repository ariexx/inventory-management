@extends('layouts.staff')

@section('title', 'Permintaan Restock')

@section('page-title')
    <div class="d-flex justify-content-between">
        <h1>Permintaan Restock</h1>
        <a href="{{ route('staff.restock-requests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Buat Permintaan
        </a>
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

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="restock-requests-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Alasan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Catatan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->barang->kode }} - {{ $request->barang->nama }}</td>
                                <td>{{ $request->jumlah_diminta }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'completed' => 'info'
                                        ][$request->status];

                                        $statusText = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'completed' => 'Selesai'
                                        ][$request->status];
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>{{ Str::limit($request->alasan, 50) }}</td>
                                <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $request->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Anda belum membuat permintaan restock</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-info">
            <h3 class="card-title">Informasi</h3>
        </div>
        <div class="card-body">
            <p>Disini Anda dapat mengajukan permintaan restock untuk barang yang stoknya menipis.</p>
            <p>Status permintaan:</p>
            <ul>
                <li><span class="badge badge-warning">Menunggu</span> - Permintaan sedang menunggu persetujuan dari admin/manager</li>
                <li><span class="badge badge-success">Disetujui</span> - Permintaan telah disetujui dan sedang diproses</li>
                <li><span class="badge badge-danger">Ditolak</span> - Permintaan ditolak oleh admin/manager</li>
                <li><span class="badge badge-info">Selesai</span> - Permintaan telah selesai diproses dan barang telah di-restock</li>
            </ul>
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
            $('#restock-requests-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[ 5, "desc" ]]
            });

            // Auto close alert after 5 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
@stop
