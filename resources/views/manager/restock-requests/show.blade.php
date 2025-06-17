@extends('layouts.admin')

@section('title', 'Detail Permintaan Restock')

@section('page-title')
    <div class="d-flex justify-content-between">
        <h1>Detail Permintaan Restock #{{ $restockRequest->id }}</h1>
        <a href="{{ route('manager.restock-requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Permintaan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">ID Permintaan</th>
                            <td>{{ $restockRequest->id }}</td>
                        </tr>
                        <tr>
                            <th>Barang</th>
                            <td>{{ $restockRequest->barang->kode }} - {{ $restockRequest->barang->nama }}</td>
                        </tr>
                        <tr>
                            <th>Diajukan Oleh</th>
                            <td>{{ $restockRequest->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>{{ $restockRequest->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Diminta</th>
                            <td>{{ $restockRequest->jumlah_diminta }}</td>
                        </tr>
                        <tr>
                            <th>Stok Saat Ini</th>
                            <td>{{ $restockRequest->barang->stok }}</td>
                        </tr>
                        <tr>
                            <th>Minimum Stok</th>
                            <td>{{ $restockRequest->barang->min_stok }}</td>
                        </tr>
                        <tr>
                            <th>Alasan</th>
                            <td>{{ $restockRequest->alasan }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'completed' => 'info'
                                    ][$restockRequest->status];

                                    $statusText = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'completed' => 'Selesai'
                                    ][$restockRequest->status];
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>
                        @if($restockRequest->processed_by)
                        <tr>
                            <th>Diproses Oleh</th>
                            <td>{{ $restockRequest->processedBy->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Diproses</th>
                            <td>{{ $restockRequest->processed_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $restockRequest->notes ?? '-' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($restockRequest->status === 'pending')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Proses Permintaan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.restock-requests.process', $restockRequest) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Status</label>
                                <div class="d-flex flex-column">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="status_approved" name="status" value="approved" class="custom-control-input">
                                        <label class="custom-control-label" for="status_approved">Setujui</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="status_rejected" name="status" value="rejected" class="custom-control-input">
                                        <label class="custom-control-label" for="status_rejected">Tolak</label>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" id="process-btn" disabled>
                                <i class="fas fa-check-circle"></i> Proses Permintaan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($restockRequest->status === 'approved')
                <div class="card bg-success">
                    <div class="card-header">
                        <h3 class="card-title">Permintaan Disetujui</h3>
                    </div>
                    <div class="card-body">
                        <p>Permintaan ini telah disetujui. Anda perlu membuat pesanan ke supplier.</p>
                        <a href="{{ route('manager.pesanan-barang.create', [
                            'barang_id' => $restockRequest->barang_id,
                            'jumlah' => $restockRequest->jumlah_diminta,
                            'restock_request_id' => $restockRequest->id
                        ]) }}" class="btn btn-light btn-block">
                            <i class="fas fa-shopping-cart"></i> Buat Pesanan
                        </a>
                    </div>
                </div>
            @endif

            @if($restockRequest->status === 'rejected')
                <div class="card bg-danger">
                    <div class="card-header">
                        <h3 class="card-title">Permintaan Ditolak</h3>
                    </div>
                    <div class="card-body">
                        <p>Permintaan ini telah ditolak dengan alasan:</p>
                        <p class="font-weight-bold">{{ $restockRequest->notes ?? 'Tidak ada catatan' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="status"]').change(function() {
                $('#process-btn').prop('disabled', false);
            });
        });
    </script>
@stop
