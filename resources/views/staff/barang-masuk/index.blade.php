@extends('layouts.staff')

@section('title', 'Data Barang Masuk')

@section('content_header')
    <h1>Data Barang Masuk</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang Masuk</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" id="search" class="form-control float-right" placeholder="Cari...">
                    <div class="input-group-append">
                        <button type="button" id="searchBtn" class="btn btn-default">
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
                        <th width="15%">Kode Transaksi</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Jumlah</th>
                        <th width="20%">Supplier</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($barangMasuk as $index => $item)
                        <tr>
                            <td>{{ $barangMasuk->firstItem() + $index }}</td>
                            <td>{{ $item->kode_transaksi }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm view-details"
                                        data-kode="{{ $item->kode_transaksi }}"
                                        data-barang="{{ $item->barang->nama ?? 'N/A' }}"
                                        data-kode-barang="{{ $item->barang->kode ?? 'N/A' }}"
                                        data-kategori="{{ $item->barang->kategori->nama ?? 'N/A' }}"
                                        data-supplier="{{ $item->supplier->nama ?? 'N/A' }}"
                                        data-jumlah="{{ $item->jumlah }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}"
                                        data-keterangan="{{ $item->keterangan ?? '-' }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang masuk</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $barangMasuk->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Barang Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Kode Transaksi</th>
                                    <td id="modal-kode">-</td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td id="modal-barang">-</td>
                                </tr>
                                <tr>
                                    <th>Kode Barang</th>
                                    <td id="modal-kode-barang">-</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td id="modal-kategori">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Supplier</th>
                                    <td id="modal-supplier">-</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td id="modal-jumlah">-</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td id="modal-tanggal">-</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td id="modal-keterangan">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
            // Search functionality
            $('#searchBtn').click(function() {
                const searchTerm = $('#search').val();
                window.location.href = '{{ route("staff.barang-masuk.index") }}?search=' + searchTerm;
            });

            // Enter key press in search box
            $('#search').keypress(function(e) {
                if(e.which == 13) {
                    $('#searchBtn').click();
                }
            });

            // View details functionality
            $('.view-details').click(function() {
                $('#modal-kode').text($(this).data('kode'));
                $('#modal-barang').text($(this).data('barang'));
                $('#modal-kode-barang').text($(this).data('kode-barang'));
                $('#modal-kategori').text($(this).data('kategori'));
                $('#modal-supplier').text($(this).data('supplier'));
                $('#modal-jumlah').text($(this).data('jumlah'));
                $('#modal-tanggal').text($(this).data('tanggal'));
                $('#modal-keterangan').text($(this).data('keterangan'));
                $('#detailModal').modal('show');
            });
        });
    </script>
@stop
