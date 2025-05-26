@extends('layouts.admin')

@section('title', 'Detail Supplier')

@section('content_header')
    <h1>Detail Supplier</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Supplier</h3>
            <div class="card-tools">
                <a href="{{ route('admin.supplier.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">Nama Supplier</th>
                    <td>{{ $supplier->nama }}</td>
                </tr>
                <tr>
                    <th>Nomor Telepon</th>
                    <td>{{ $supplier->telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $supplier->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $supplier->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $supplier->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Dibuat</th>
                    <td>{{ $supplier->created_at ? $supplier->created_at->format('d F Y H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>Terakhir Diupdate</th>
                    <td>{{ $supplier->updated_at ? $supplier->updated_at->format('d F Y H:i') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
@stop
