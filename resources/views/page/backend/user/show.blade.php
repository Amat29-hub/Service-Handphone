@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Detail User</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <table class="table table-dark table-striped w-50 mx-auto">
            <tr><th>Nama</th><td>{{ $user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
            <tr><th>Status</th><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
            <tr><th>Foto</th><td>
                @if($user->image)
                    <img src="{{ asset('storage/'.$user->image) }}" width="100" class="rounded">
                @else
                    <em>Tidak ada</em>
                @endif
            </td></tr>
        </table>
        <div class="text-center mt-3">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection