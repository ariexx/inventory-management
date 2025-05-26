@extends('layouts.staff')

@section('title', 'Koreksi Stok')

@section('content_header')
    <h1>Koreksi Stok Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Form Koreksi Stok</h3>
            </div>
        </div>
        <form action="{{ route('staff.stok-koreksi.store') }}" method="POST">
            @csrf
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

                <div class="form-group">
                    <label for="barang_id">Barang</label>
                    <select class="form-control select2 @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->id }}" data-stok="{{ $item->stok }}" data-satuan="{{ $item->satuan }}">
                                {{ $item->kode }} - {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stok_tercatat">Stok Tercatat</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="stok_tercatat" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="satuan-tercatat"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stok_fisik">Stok Fisik Aktual</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stok_fisik') is-invalid @enderror" id="stok_fisik" name="stok_fisik" min="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="satuan-fisik"></span>
                                </div>
                            </div>
                            @error('stok_fisik')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selisih">Selisih</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="selisih" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="satuan-selisih"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Alasan Koreksi</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan alasan koreksi stok" required></textarea>
                    @error('keterangan')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    <i class="fas fa-save mr-1"></i> Simpan Koreksi
                </button>
            </div>
        </form>
    </div>

    <!-- History Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Koreksi Stok</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Nama Barang</th>
                        <th width="10%">Stok Awal</th>
                        <th width="10%">Stok Aktual</th>
                        <th width="10%">Selisih</th>
                        <th width="15%">Petugas</th>
                        <th width="15%">Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($koreksiStok as $index => $item)
                        <tr>
                            <td>{{ $koreksiStok->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y H:i') }}</td>
                            <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                            <td>{{ $item->stok_awal }} {{ $item->barang->satuan ?? '' }}</td>
                            <td>{{ $item->stok_akhir }} {{ $item->barang->satuan ?? '' }}</td>
                            <td>
                                    <span class="{{ $item->selisih > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }} {{ $item->barang->satuan ?? '' }}
                                    </span>
                            </td>
                            <td>{{ $item->user->name ?? 'N/A' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data koreksi stok</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $koreksiStok->links() }}
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
            // Initialize select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih barang untuk koreksi'
            });

            // Handle barang selection
            $('#barang_id').change(function() {
                const selected = $(this).find(':selected');
                const stok = selected.data('stok') || 0;
                const satuan = selected.data('satuan') || '';

                $('#stok_tercatat').val(stok);
                $('#satuan-tercatat').text(satuan);
                $('#satuan-fisik').text(satuan);
                $('#satuan-selisih').text(satuan);
                $('#stok_fisik').val('');
                $('#selisih').val('');
                $('#submitBtn').prop('disabled', true);
            });

            // Calculate difference when stok fisik is entered
            $('#stok_fisik').on('input', function() {
                const stokTercatat = parseFloat($('#stok_tercatat').val()) || 0;
                const stokFisik = parseFloat($(this).val()) || 0;
                const selisih = stokFisik - stokTercatat;

                $('#selisih').val(selisih);

                // Enable submit button only if there's a difference and a barang is selected
                $('#submitBtn').prop('disabled', selisih === 0 || $('#barang_id').val() === '');
            });
        });
    </script>
@stop
