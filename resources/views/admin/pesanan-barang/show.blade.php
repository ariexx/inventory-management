@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)

@section('title', 'Detail Pesanan Barang')

@section('page-title')
    <div class="d-flex justify-content-between">
        <h1>Detail Pesanan: {{ $pesananBarang->kode_pesanan }}</h1>
        <div>
            <a href="{{ route($layout . '.pesanan-barang.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if($pesananBarang->status === 'approved')
                <a href="{{ route($layout . '.pesanan-barang.receive', $pesananBarang) }}" class="btn btn-success">
                    <i class="fas fa-truck-loading"></i> Terima Barang
                </a>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pesanan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Kode Pesanan</th>
                            <td>{{ $pesananBarang->kode_pesanan }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'received' => 'success',
                                        'cancelled' => 'danger'
                                    ][$pesananBarang->status] ?? 'secondary';

                                    $statusLabel = [
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'received' => 'Diterima',
                                        'cancelled' => 'Dibatalkan'
                                    ][$pesananBarang->status] ?? $pesananBarang->status;
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
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
                            <th>Jumlah</th>
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
                        <tr>
                            <th>Harga Satuan</th>
                            <td>{{ $pesananBarang->harga_satuan ? 'Rp ' . number_format($pesananBarang->harga_satuan, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td>{{ $pesananBarang->harga_satuan ? 'Rp ' . number_format($pesananBarang->harga_satuan * $pesananBarang->jumlah, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $pesananBarang->keterangan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $pesananBarang->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $pesananBarang->updated_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if(!auth()->user()->is_admin() && !auth()->user()->is_staff())
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <a href="{{ route($layout . '.pesanan-barang.edit', $pesananBarang) }}" class="btn btn-warning mb-2">
                                <i class="fas fa-edit"></i> Edit Pesanan
                            </a>

                            @if($pesananBarang->status === 'pending')
                                <form action="{{ route($layout . '.pesanan-barang.update', $pesananBarang) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="barang_id" value="{{ $pesananBarang->barang_id }}">
                                    <input type="hidden" name="supplier_id" value="{{ $pesananBarang->supplier_id }}">
                                    <input type="hidden" name="jumlah" value="{{ $pesananBarang->jumlah }}">
                                    <input type="hidden" name="tanggal_pemesanan" value="{{ $pesananBarang->tanggal_pemesanan->format('Y-m-d') }}">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-info mb-2">
                                        <i class="fas fa-check"></i> Setujui Pesanan
                                    </button>
                                </form>

                                <form action="{{ route($layout . '.pesanan-barang.update', $pesananBarang) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="barang_id" value="{{ $pesananBarang->barang_id }}">
                                    <input type="hidden" name="supplier_id" value="{{ $pesananBarang->supplier_id }}">
                                    <input type="hidden" name="jumlah" value="{{ $pesananBarang->jumlah }}">
                                    <input type="hidden" name="tanggal_pemesanan" value="{{ $pesananBarang->tanggal_pemesanan->format('Y-m-d') }}">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger mb-2">
                                        <i class="fas fa-times"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif

                            @if($pesananBarang->status !== 'received' && $pesananBarang->status !== 'cancelled')
                                <form action="{{ route($layout . '.pesanan-barang.destroy', $pesananBarang) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                        <i class="fas fa-trash"></i> Hapus Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            @if($pesananBarang->barangMasuk)
                <div class="card mt-4">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Informasi Penerimaan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Kode Transaksi</th>
                                <td>{{ $pesananBarang->barangMasuk->kode_transaksi }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terima</th>
                                <td>{{ $pesananBarang->barangMasuk->tanggal->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Diterima</th>
                                <td>{{ $pesananBarang->barangMasuk->jumlah }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="{{ route($layout . '.barang-masuk.show', $pesananBarang->barangMasuk->id) }}" class="btn btn-sm btn-info btn-block">
                                        Lihat Detail Barang Masuk
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
