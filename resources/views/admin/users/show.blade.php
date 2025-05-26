@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content_header')
    <h1>Detail Pengguna</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Pengguna</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">Nama</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>
                        @if($user->level == 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @elseif($user->level == 'manager')
                            <span class="badge badge-warning">Manager</span>
                        @else
                            <span class="badge badge-info">Staff</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Dibuat</th>
                    <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Terakhir Diupdate</th>
                    <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if(auth()->id() !== $user->id)
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');
        }
    </script>
@stop
