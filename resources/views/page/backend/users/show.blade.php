@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Detail User</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Informasi User</h6>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light fw-bold">
                <i class="fa fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="row align-items-center">
            <!-- Foto Profil -->
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <img src="{{ $user->image ? asset($user->image) : asset('img/user.jpg') }}"
                     class="rounded-circle border border-3 border-primary mb-3"
                     width="120" height="120" alt="User Image">
                <h5 class="fw-bold text-white">{{ $user->name }}</h5>
                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <!-- Detail Data -->
            <div class="col-md-9">
                <table class="table table-borderless text-light mb-0">
                    <tr>
                        <th style="width: 25%">Nama</th>
                        <td>: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>: {{ ucfirst($user->role) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            :
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>: {{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui</th>
                        <td>: {{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4 text-end">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning text-white me-2">
                <i class="fa fa-edit me-1"></i>Edit
            </a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin hapus user ini?')">
                    <i class="fa fa-trash me-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
