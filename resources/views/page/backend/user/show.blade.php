@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg text-light mx-auto" style="max-width: 800px; width: 95%;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-white mb-2">👤 Detail User</h4>
            <div class="d-flex justify-content-center">
                @if($user->image)
                    <img src="{{ asset('storage/'.$user->image) }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle border border-3 border-primary shadow-sm"
                         width="120" height="120"
                         style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center border border-3 border-secondary shadow-sm"
                         style="width:120px; height:120px;">
                        <i class="bi bi-person fs-1 text-secondary"></i>
                    </div>
                @endif
            </div>
        </div>

        <hr class="border-secondary">

        <div class="mb-3">
            <h6 class="text-muted mb-1">Nama Lengkap</h6>
            <p class="fs-6 fw-semibold text-white">{{ $user->name }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Email</h6>
            <p class="fs-6 fw-semibold text-white">{{ $user->email }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Role</h6>
            <p class="fs-6 fw-semibold text-white">{{ ucfirst($user->role) }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Status Akun</h6>
            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill fs-6">
                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>

        <hr class="border-secondary">

        <div class="text-center mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-outline-light px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning px-4 ms-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection