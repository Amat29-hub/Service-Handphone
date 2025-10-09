@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 text-white">Edit Data User</h6>
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control bg-dark text-white border-0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control bg-dark text-white border-0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Role</label>
                    <select name="role" class="form-select bg-dark text-white border-0">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technician</option>
                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Status</label>
                    <select name="is_active" class="form-select bg-dark text-white border-0">
                        <option value="active" {{ $user->is_active == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="nonactive" {{ $user->is_active == 'nonactive' ? 'selected' : '' }}>Nonactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Foto</label>
                    <input type="file" name="image" class="form-control bg-dark text-white border-0">
                    @if($user->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Foto User" width="70" height="70" class="rounded border border-primary">
                        </div>
                    @endif
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary mt-3 px-4">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
