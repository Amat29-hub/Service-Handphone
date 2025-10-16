@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Edit User</h4>

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

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">
                    Password <small class="text-warning">(kosongkan jika tidak ingin diubah)</small>
                </label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="technician" {{ old('role', $user->role) == 'technician' ? 'selected' : '' }}>Technician</option>
                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>

            {{-- Foto Profil --}}
            <div class="mb-3">
                <label for="image" class="form-label">Foto Profil</label>
                @if ($user->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$user->image) }}" alt="Avatar"
                             class="rounded-circle shadow-sm border border-light"
                             width="80" height="80">
                    </div>
                @endif
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="active" value="active"
                        {{ old('is_active', $user->is_active) === 'active' ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_active" id="nonactive" value="nonactive"
                        {{ old('is_active', $user->is_active) === 'nonactive' ? 'checked' : '' }}>
                    <label class="form-check-label" for="nonactive">Nonactive</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-2">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
