@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">📱 Tabel Handphone</h5>
            <a href="{{ route('handphone.create') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle table-bordered text-center mb-0">
                <thead>
                    <tr class="text-white text-center">
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Tanggal Rilis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($handphones as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="Image"
                                         width="55" height="55"
                                         class="rounded border border-2 border-primary shadow-sm">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="text-white">{{ $item->brand }}</td>
                            <td class="text-white">{{ $item->model }}</td>
                            <td class="text-white">
                                {{ \Carbon\Carbon::parse($item->release_year)->translatedFormat('d F Y') }}
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input 
                                        class="form-check-input bg-primary border-0" 
                                        type="checkbox"
                                        id="statusSwitch{{ $item->id }}"
                                        {{ $item->status === 'active' ? 'checked' : '' }}
                                        onchange="toggleStatus({{ $item->id }})">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    <!-- Edit -->
                                    <a href="{{ route('handphone.edit', $item->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('handphone.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="flex-fill m-0 p-0"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center text-white py-3">
                                Tidak ada data handphone.
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
        fetch(`/handphone/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
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
    /* Efek hover tabel */
    .table-dark tbody tr:hover {
        background-color: #2b3035 !important;
        transition: background-color 0.2s ease;
    }

    /* Notifikasi kecil */
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
