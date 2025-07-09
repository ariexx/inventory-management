@php
    $layout = auth()->user()->is_admin() ? 'admin' :
             (auth()->user()->is_manager() ? 'manager' : 'staff');
@endphp
@extends('layouts.' . $layout)


@section('title', 'Laporan Stok Barang')

@section('content_header')
    <h1>Laporan Stok Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route(auth()->user()->level.'.laporan.stok') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-control select2" id="kategori_id" name="kategori_id">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="status">Status Stok</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Kurang</option>
                                <option value="safe" {{ request('status') == 'safe' ? 'selected' : '' }}>Stok Aman</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group mb-0 w-100">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Data Stok Barang</h3>
                <div>
{{--                    <a href="{{ route('admin.laporan.stok', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger">--}}
{{--                        <i class="fas fa-file-pdf mr-1"></i> Export PDF--}}
{{--                    </a>--}}
{{--                    <a href="{{ route('admin.laporan.stok', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success">--}}
{{--                        <i class="fas fa-file-excel mr-1"></i> Export Excel--}}
{{--                    </a>--}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="20%">Nama Barang</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Satuan</th>
                        <th width="10%">Stok</th>
                        <th width="10%">Minimal</th>
                        <th width="10%">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($barang as $index => $item)
                        <tr class="{{ $item->stok < $item->minimal_stok ? 'bg-warning' : '' }}">
                            <td>{{ $barang->firstItem() + $index }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori->nama ?? 'N/A' }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->minimal_stok }}</td>
                            <td>
                                @if($item->stok < $item->minimal_stok)
                                    <span class="badge badge-warning">Stok Kurang</span>
                                @else
                                    <span class="badge badge-success">Stok Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data barang</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $barang->appends(request()->except('page'))->links() }}
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
        });
    </script>
@stop
