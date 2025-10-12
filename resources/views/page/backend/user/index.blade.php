@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">👤 Tabel User</h5>
            <a href="{{ route('users.create') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark align-middle text-center mb-0 table-hover"
                   style="border-collapse: collapse; border: 1px solid #555;">
                <thead style="background-color: #2b2b2b;">
                    <tr class="text-white">
                        <th style="border: 1px solid #555;">No</th>
                        <th style="border: 1px solid #555;">Foto</th>
                        <th style="border: 1px solid #555;">Nama</th>
                        <th style="border: 1px solid #555;">Email</th>
                        <th style="border: 1px solid #555;">Role</th>
                        <th style="border: 1px solid #555;">Status</th>
                        <th style="border: 1px solid #555;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr style="border: 1px solid #555;">
                            <td style="border: 1px solid #555;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #555;">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}"
                                         alt="Foto User"
                                         width="80" height="80"
                                         class="rounded border border-2 border-primary shadow-sm"
                                         style="object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $user->name }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $user->email }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ ucfirst($user->role) }}</td>
                            <td style="border: 1px solid #555;">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input 
                                        class="form-check-input bg-primary border-0 toggle-status" 
                                        type="checkbox"
                                        id="statusSwitch{{ $user->id }}"
                                        {{ $user->is_active ? 'checked' : '' }}
                                        onchange="toggleStatus({{ $user->id }})">
                                </div>
                            </td>
                            <td style="border: 1px solid #555;">
                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm flex-fill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" 
                                          method="POST" 
                                          class="flex-fill m-0 p-0"
                                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white py-3" style="border: 1px solid #555;">
                                Tidak ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tombol Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- Script toggle status -->
<script>
    function toggleStatus(id) {
        fetch(`/users/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const toast = document.createElement('div');
                toast.textContent = 'Status berhasil diperbarui!';
                toast.className = 'toast-msg';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

<style>
    .table-dark tbody tr:hover {
        background-color: #2f3337 !important;
        transition: background-color 0.2s ease;
    }

    .toast-msg {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #198754;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        font-size: 14px;
        animation: fadeInOut 2.5s ease forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(10px); }
        10%, 90% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(10px); }
    }
</style>
@endsection