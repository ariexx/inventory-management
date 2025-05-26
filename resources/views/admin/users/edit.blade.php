@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content_header')
    <h1>Edit Pengguna</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Pengguna</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small></label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="level">Level <span class="text-danger">*</span></label>
                    <select name="level" id="level" class="form-control @error('level') is-invalid @enderror" required>
                        <option value="" disabled>-- Pilih Level --</option>
                        <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('level', $user->level) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('level', $user->level) == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('level')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
