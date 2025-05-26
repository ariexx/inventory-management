@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content_header')
    <h1>Manajemen Pengguna</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Pengguna</h3>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pengguna
                </a>
            </div>
        </div>
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

            <div class="table-responsive">
                <table id="users-table" class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama</th>
                        <th width="25%">Email</th>
                        <th width="15%">Level</th>
                        <th width="10%">Tanggal Dibuat</th>
                        <th width="10%">Login terakhir</th>
                        <th width="15%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->level == 'admin')
                                    <span class="badge badge-danger">Admin</span>
                                @elseif($user->level == 'manager')
                                    <span class="badge badge-warning">Manager</span>
                                @else
                                    <span class="badge badge-info">Staff</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>{{ $user->last_login_at }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
        });

        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');
        }
    </script>
@stop
