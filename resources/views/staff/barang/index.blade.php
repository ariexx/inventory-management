@extends('layouts.staff')

@section('title', 'Data Barang')

@section('page-title', 'Data Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Barang</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" id="search" class="form-control float-right" placeholder="Cari barang...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-default" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Minimal Stok</th>
                        <th>Satuan</th>
                        <th width="10%">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($barang as $index => $item)
                        <tr class="{{ $item->stok <= $item->minimal_stok ? 'table-danger' : '' }}">
                            <td>{{ $barang->firstItem() + $index }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori->nama ?? 'Tidak Ada' }}</td>
                            <td class="font-weight-bold">{{ $item->stok }}</td>
                            <td>{{ $item->minimal_stok }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>
                                @if($item->stok <= 0)
                                    <span class="badge badge-danger">Habis</span>
                                @elseif($item->stok <= $item->minimal_stok)
                                    <span class="badge badge-warning">Stok Rendah</span>
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
            {{ $barang->links() }}
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="form-group">
                            <label for="kategori_filter">Kategori</label>
                            <select class="form-control" id="kategori_filter" name="kategori_filter">
                                <option value="">Semua Kategori</option>
                                @foreach(\App\Models\KategoriBarang::all() as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status_filter">Status Stok</label>
                            <select class="form-control" id="status_filter" name="status_filter">
                                <option value="">Semua Status</option>
                                <option value="low">Stok Rendah</option>
                                <option value="safe">Stok Aman</option>
                                <option value="empty">Habis</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="applyFilter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            // Search functionality - basic implementation
            $('#searchBtn').click(function() {
                const searchTerm = $('#search').val();
                window.location.href = '{{ route("staff.barang.index") }}?search=' + searchTerm;
            });

            // Enter key press in search box
            $('#search').keypress(function(e) {
                if(e.which == 13) {
                    $('#searchBtn').click();
                }
            });

            // Apply filter button
            $('#applyFilter').click(function() {
                const kategori = $('#kategori_filter').val();
                const status = $('#status_filter').val();

                let url = '{{ route("staff.barang.index") }}?';
                if (kategori) url += 'kategori=' + kategori + '&';
                if (status) url += 'status=' + status + '&';

                window.location.href = url;
            });
        });
    </script>
@endsection
