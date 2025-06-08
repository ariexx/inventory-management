@extends('layouts.admin')

@section('title', 'Data Penjualan')

@section('content_header')
    <h1>Data Penjualan</h1>
@stop

@section('content')
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Penjualan</h3>
                    <div>
                        <a href="{{ route('admin.penjualan.laporan') }}" class="btn btn-info mr-2">
                            <i class="fas fa-chart-bar mr-1"></i> Laporan Penjualan
                        </a>
                        <a href="{{ route('admin.penjualan.laporan-stok') }}" class="btn btn-success mr-2">
                            <i class="fas fa-boxes mr-1"></i> Laporan Stok
                        </a>
                        <a href="{{ route('admin.penjualan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Penjualan
                        </a>
                    </div>
                </div>
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
                        <th width="15%">Tanggal</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="15%">Total Harga</th>
                        <th width="10%">Pembeli</th>
                        <th width="10%">Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($penjualans as $index => $item)
                        <tr>
                            <td>{{ $penjualans->firstItem() + $index }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $item->nama_pembeli ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm detail-btn" data-toggle="modal" data-target="#detailModal"
                                        data-kode="{{ $item->kode_transaksi }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}"
                                        data-barang="{{ $item->barang->nama ?? 'N/A' }}"
                                        data-jumlah="{{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}"
                                        data-harga="{{ number_format($item->harga_satuan, 0, ',', '.') }}"
                                        data-total="{{ number_format($item->total_harga, 0, ',', '.') }}"
                                        data-pembeli="{{ $item->nama_pembeli ?? '-' }}"
                                        data-keterangan="{{ $item->keterangan ?? '-' }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.admin.penjualan.invoice', $item) }}" class="btn btn-info btn-sm" title="Invoice">
                                    <i class="fas fa-file-invoice"></i> Invoice
                                </a>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $penjualans->links() }}
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kode Transaksi</th>
                            <td id="modal-kode"></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td id="modal-tanggal"></td>
                        </tr>
                        <tr>
                            <th>Barang</th>
                            <td id="modal-barang"></td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td id="modal-jumlah"></td>
                        </tr>
                        <tr>
                            <th>Harga Satuan</th>
                            <td>Rp <span id="modal-harga"></span></td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td>Rp <span id="modal-total"></span></td>
                        </tr>
                        <tr>
                            <th>Pembeli</th>
                            <td id="modal-pembeli"></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td id="modal-keterangan"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $('.detail-btn').click(function() {
                $('#modal-kode').text($(this).data('kode'));
                $('#modal-tanggal').text($(this).data('tanggal'));
                $('#modal-barang').text($(this).data('barang'));
                $('#modal-jumlah').text($(this).data('jumlah'));
                $('#modal-harga').text($(this).data('harga'));
                $('#modal-total').text($(this).data('total'));
                $('#modal-pembeli').text($(this).data('pembeli'));
                $('#modal-keterangan').text($(this).data('keterangan'));
            });
        });
    </script>
@stop
