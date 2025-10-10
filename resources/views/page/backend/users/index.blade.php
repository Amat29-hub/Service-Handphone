@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0 text-white">Daftar User</h6>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                + Tambah User
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle table-bordered text-center mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="250px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($user->image && file_exists(public_path('storage/' . $user->image)))
                                    <img src="{{ asset('storage/' . $user->image) }}"
                                         class="rounded-circle border border-2 border-primary"
                                         width="50" height="50" alt="User Photo">
                                @else
                                    <img src="{{ asset('assets/img/default-user.png') }}"
                                         class="rounded-circle border border-2 border-secondary"
                                         width="50" height="50" alt="Default Photo">
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                           data-id="{{ $user->id }}"
                                           id="statusSwitch{{ $user->id }}"
                                           {{ $user->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="btn btn-info btn-sm flex-fill">Detail</a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="btn btn-warning btn-sm flex-fill">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus user ini?')" class="flex-fill m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white">Tidak ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Custom toggle colors: Aktif merah, Nonaktif abu-abu */
.form-check-input:checked {
    background-color: #dc3545 !important; /* merah saat aktif */
    border-color: #dc3545 !important;
}

.form-check-input:not(:checked) {
    background-color: #6c757d !important; /* abu-abu saat nonaktif */
    border-color: #6c757d !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const switches = document.querySelectorAll('.toggle-status');

    switches.forEach(sw => {
        sw.addEventListener('change', function() {
            const userId = this.dataset.id;
            const token = "{{ csrf_token() }}";
            const isActive = this.checked;

            fetch(`/users/toggle/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Gagal mengubah status user!');
                    sw.checked = !isActive;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan!');
                sw.checked = !isActive;
            });
        });
    });
});
</script>
@endpush
@endsection

