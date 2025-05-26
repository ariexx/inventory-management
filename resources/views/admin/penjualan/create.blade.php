@extends('layouts.admin')

@section('title', 'Tambah Penjualan')

@section('content_header')
    <h1>Tambah Data Penjualan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Penjualan Barang</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-1"></i> {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.penjualan.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="tanggal">Tanggal Penjualan <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="barang_id">Pilih Barang <span class="text-danger">*</span></label>
                    <select class="form-control select2bs4 @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required style="width: 100%;">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-harga="{{ $barang->harga_jual }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->kode }} - {{ $barang->nama }} (Stok: {{ $barang->stok }} {{ $barang->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required>
                            @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan (Rp)</label>
                            <input type="text" class="form-control" id="harga_satuan" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_harga">Total Harga (Rp)</label>
                            <input type="text" class="form-control" id="total_harga" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_pembeli">Nama Pembeli</label>
                    <input type="text" class="form-control @error('nama_pembeli') is-invalid @enderror" id="nama_pembeli" name="nama_pembeli" value="{{ old('nama_pembeli') }}">
                    @error('nama_pembeli')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Stok Tersedia:</strong> <span id="stok-tersedia">0</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Sisa Stok:</strong> <span id="sisa-stok">0</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                    <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@stop

@section('scripts')
    <!-- Select2 JS -->
    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // Initialize Select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: "-- Pilih Barang --",
                allowClear: true
            });

            // Format number to currency
            function formatRupiah(angka) {
                if (!angka) return '0';
                var number = parseInt(angka);
                var reverse = number.toString().split('').reverse().join('');
                var ribuan = reverse.match(/\d{1,3}/g);
                if (ribuan) {
                    return ribuan.join('.').split('').reverse().join('');
                }
                return '0';
            }

            // Calculate and update price
            function calculatePrice() {
                var jumlah = parseInt($('#jumlah').val()) || 0;
                var selectedOption = $('#barang_id option:selected');
                var harga = parseInt(selectedOption.data('harga')) || 0;
                var stok = parseInt(selectedOption.data('stok')) || 0;

                console.log('Jumlah:', jumlah, 'Harga:', harga, 'Stok:', stok);

                $('#harga_satuan').val('Rp ' + formatRupiah(harga));
                $('#total_harga').val('Rp ' + formatRupiah(harga * jumlah));

                // Add hidden field for controller
                if ($('#harga_satuan_hidden').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'harga_satuan_hidden',
                        name: 'harga_satuan_hidden',
                        value: harga
                    }).appendTo('form');
                } else {
                    $('#harga_satuan_hidden').val(harga);
                }

                $('#stok-tersedia').text(stok);
                var sisaStok = stok - jumlah;
                $('#sisa-stok').text(sisaStok);

                if (sisaStok < 0) {
                    $('#sisa-stok').addClass('text-danger font-weight-bold');
                } else {
                    $('#sisa-stok').removeClass('text-danger font-weight-bold');
                }
            }

            // Calculate on barang change
            $('#barang_id').on('change', calculatePrice);

            // Calculate on quantity change
            $('#jumlah').on('input', calculatePrice);

            // Initial calculation
            calculatePrice();
        });
    </script>
@stop
