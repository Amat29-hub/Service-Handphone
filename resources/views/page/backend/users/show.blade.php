@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 text-white">
        <h4 class="mb-4 text-center">Detail User</h4>

        <div class="text-center mb-4">
            @if($user->image)
                <img src="{{ asset('storage/' . $user->image) }}" width="120" height="120" class="rounded-circle border border-primary">
            @else
                <img src="{{ asset('img/user.jpg') }}" width="120" height="120" class="rounded-circle border border-secondary">
            @endif
        </div>

        <table class="table table-dark table-bordered">
            <tr><th>Nama</th><td>{{ $user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
            <tr><th>Status</th><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
        </table>

        <div class="text-center mt-3">
            <a href="{{ route('users.index') }}" class="btn btn-light">Kembali</a>
        </div>
    </div>
</div>
@endsection
