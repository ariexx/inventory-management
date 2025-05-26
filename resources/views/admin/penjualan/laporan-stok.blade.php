@extends('layouts.admin')

@section('title', 'Laporan Stok Barang')

@section('content_header')
    <h1>Laporan Stok Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Status Stok Barang</h3>
                <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stok Kritis</span>
                            <span class="info-box-number">{{ $kritisList->count() }} items</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stok Rendah</span>
                            <span class="info-box-number">{{ $rendahList->count() }} items</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stok Aman</span>
                            <span class="info-box-number">{{ $amanList->count() }} items</span>
                        </div>
                    </div>
                </div>
            </div>

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">
                        Semua Barang <span class="badge badge-secondary">{{ $barangs->count() }}</span>
                    </a>
                    <a class="nav-item nav-link" id="nav-kritis-tab" data-toggle="tab" href="#nav-kritis" role="tab" aria-controls="nav-kritis" aria-selected="false">
                        Stok Kritis <span class="badge badge-danger">{{ $kritisList->count() }}</span>
                    </a>
                    <a class="nav-item nav-link" id="nav-rendah-tab" data-toggle="tab" href="#nav-rendah" role="tab" aria-controls="nav-rendah" aria-selected="false">
                        Stok Rendah <span class="badge badge-warning">{{ $rendahList->count() }}</span>
                    </a>
                    <a class="nav-item nav-link" id="nav-aman-tab" data-toggle="tab" href="#nav-aman" role="tab" aria-controls="nav-aman" aria-selected="false">
                        Stok Aman <span class="badge badge-success">{{ $amanList->count() }}</span>
                    </a>
                </div>
            </nav>
            <div class="tab-content mt-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="20%">Nama Barang</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Min. Stok</th>
                                <th width="15%">Status Stok</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($barangs as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                    <td>{{ $item->minimal_stok }} {{ $item->satuan }}</td>
                                    <td>
                                        @if($item->stok < $item->minimal_stok)
                                            <span class="badge badge-danger">Kritis</span>
                                        @elseif($item->stok < ($item->minimal_stok * 1.5))
                                            <span class="badge badge-warning">Rendah</span>
                                        @else
                                            <span class="badge badge-success">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data barang</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-kritis" role="tabpanel" aria-labelledby="nav-kritis-tab">
                    <!-- Kritis items table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="20%">Nama Barang</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Min. Stok</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($kritisList as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                    <td>{{ $item->minimal_stok }} {{ $item->satuan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada barang dengan stok kritis</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Similar tables for rendah and aman tabs -->
                <div class="tab-pane fade" id="nav-rendah" role="tabpanel" aria-labelledby="nav-rendah-tab">
                    <!-- Rendah items table (similar structure) -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="20%">Nama Barang</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Min. Stok</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($rendahList as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                    <td>{{ $item->minimal_stok }} {{ $item->satuan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada barang dengan stok rendah</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-aman" role="tabpanel" aria-labelledby="nav-aman-tab">
                    <!-- Aman items table (similar structure) -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="20%">Nama Barang</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Min. Stok</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($amanList as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                    <td>{{ $item->minimal_stok }} {{ $item->satuan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada barang dengan stok aman</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print mr-1"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>
@stop
