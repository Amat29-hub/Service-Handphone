@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tambah User</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary mb-3">Kembali</a>

        {{-- Error alert --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                    <option value="technician" {{ old('role')=='technician' ? 'selected' : '' }}>Technician</option>
                    <option value="customer" {{ old('role')=='customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Foto Profil</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="inactive" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                    <label class="form-check-label" for="inactive">Inactive</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan User</button>
        </form>
    </div>
</div>
@endsection