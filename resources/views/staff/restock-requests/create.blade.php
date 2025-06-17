@extends('layouts.staff')

@section('title', 'Buat Permintaan Restock')

@section('content_header')
    <h1>Buat Permintaan Restock</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('staff.restock-requests.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="barang_id">Barang yang Perlu Restock <span class="text-danger">*</span></label>
                    <select name="barang_id" id="barang_id" class="form-control select2 @error('barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->id }}" {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kode }} - {{ $item->nama }} (Stok: {{ $item->stok }}, Min. Stok: {{ $item->min_stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_diminta">Jumlah yang Dibutuhkan <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_diminta" id="jumlah_diminta" class="form-control @error('jumlah_diminta') is-invalid @enderror" value="{{ old('jumlah_diminta') }}" min="1" required>
                    @error('jumlah_diminta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alasan">Alasan <span class="text-danger">*</span></label>
                    <textarea name="alasan" id="alasan" rows="3" class="form-control @error('alasan') is-invalid @enderror" required>{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
                    <a href="{{ route('staff.restock-requests.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
