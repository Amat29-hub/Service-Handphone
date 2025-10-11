@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tambah User</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <form id="userForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control bg-dark text-light border-0" required>
                    <div class="invalid-feedback">Nama wajib diisi.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-light border-0" required>
                    <div class="invalid-feedback">Email tidak valid.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control bg-dark text-light border-0" required minlength="6">
                    <div class="invalid-feedback">Minimal 6 karakter.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select bg-dark text-light border-0" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="technician">Teknisi</option>
                        <option value="customer">Pelanggan</option>
                    </select>
                    <div class="invalid-feedback">Silakan pilih role.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select bg-dark text-light border-0" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" name="image" id="image" accept="image/*" class="form-control bg-dark text-light border-0">
                    <img id="preview" class="mt-3 rounded d-none" width="120">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary px-4">Simpan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

{{-- Script Preview & Validasi --}}
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const [file] = e.target.files;
    const preview = document.getElementById('preview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});

(() => {
    'use strict'
    const form = document.getElementById('userForm')
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }
        form.classList.add('was-validated')
    }, false)
})();
</script>
@endsection