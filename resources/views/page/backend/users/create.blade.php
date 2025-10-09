@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 text-white">Tambah User Baru</h6>
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-white">Nama</label>
                    <input type="text" name="name" class="form-control bg-dark text-white border-0" placeholder="Masukkan nama" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-0" placeholder="Masukkan email" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Password</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-0" placeholder="Masukkan password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white">Role</label>
                    <select name="role" class="form-select bg-dark text-white border-0">
                        <option value="admin">Admin</option>
                        <option value="technician">Technician</option>
                        <option value="customer" selected>Customer</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Foto</label>
                    <input type="file" name="image" class="form-control bg-dark text-white border-0">
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white">Status</label>
                    <select name="is_active" class="form-select bg-dark text-white border-0">
                        <option value="active">Active</option>
                        <option value="nonactive">Nonactive</option>
                    </select>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-danger mt-3 px-4">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
