@extends('layouts.admin')

@section('title', 'Laporan Barang Masuk')

@section('content_header')
    <h1>Laporan Barang Masuk</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.laporan.barang-masuk') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control select2" id="supplier_id" name="supplier_id">
                                <option value="">Semua Supplier</option>
                                @foreach($supplier as $s)
                                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="barang_id">Barang</label>
                            <select class="form-control select2" id="barang_id" name="barang_id">
                                <option value="">Semua Barang</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.laporan.barang-masuk') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Data Barang Masuk</h3>
                <div>
                    <a href="{{ route('admin.laporan.barang-masuk', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.laporan.barang-masuk', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Transaksi</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="20%">Supplier</th>
                        <th width="15%">Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($barangMasuk as $index => $item)
                        <tr>
                            <td>{{ $barangMasuk->firstItem() + $index }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang masuk</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total:</th>
                        <th>{{ $barangMasuk->sum('jumlah') }}</th>
                        <th colspan="2"></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $barangMasuk->appends(request()->except('page'))->links() }}
        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@stop

@section('scripts')
    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Set default date range if empty
            if (!$('#start_date').val() && !$('#end_date').val()) {
                const now = new Date();
                const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                $('#start_date').val(formatDate(firstDay));
                $('#end_date').val(formatDate(lastDay));
            }
        });
    </script>
@stop
