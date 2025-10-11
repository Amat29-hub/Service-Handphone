@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Edit User</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <form id="userEditForm" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control bg-dark text-light border-0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control bg-dark text-light border-0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control bg-dark text-light border-0" minlength="6">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select bg-dark text-light border-0" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Teknisi</option>
                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Pelanggan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select bg-dark text-light border-0" required>
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto (Opsional)</label>
                    <input type="file" name="image" id="imageEdit" accept="image/*" class="form-control bg-dark text-light border-0">
                    <div class="mt-3">
                        @if($user->image)
                            <img id="previewEdit" src="{{ asset('storage/'.$user->image) }}" class="rounded" width="120">
                        @else
                            <img id="previewEdit" class="d-none rounded" width="120">
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-warning px-4">Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

{{-- Script Preview & Validasi --}}
<script>
document.getElementById('imageEdit').addEventListener('change', function(e) {
    const [file] = e.target.files;
    const preview = document.getElementById('previewEdit');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});

(() => {
    'use strict'
    const form = document.getElementById('userEditForm')
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