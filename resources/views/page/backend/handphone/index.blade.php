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
            <table class="table table-dark align-middle text-center mb-0 table-hover"
                   style="border-collapse: collapse; border: 1px solid #555;">
                <thead style="background-color: #2b2b2b;">
                    <tr class="text-white">
                        <th style="border: 1px solid #555;">No</th>
                        <th style="border: 1px solid #555;">Gambar</th>
                        <th style="border: 1px solid #555;">Brand</th>
                        <th style="border: 1px solid #555;">Model</th>
                        <th style="border: 1px solid #555;">Tahun Rilis</th>
                        <th style="border: 1px solid #555;">Status</th>
                        <th style="border: 1px solid #555;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($handphones as $index => $item)
                        <tr style="border: 1px solid #555;">
                            <td style="border: 1px solid #555;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #555;">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="Image"
                                         width="80" height="80"
                                         class="rounded border border-2 border-primary shadow-sm"
                                         style="object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $item->brand }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $item->model }}</td>
                            <td class="text-white" style="border: 1px solid #555;">
                                {{ $item->release_year ?? '-' }}
                            </td>
                            <td style="border: 1px solid #555;">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input 
                                        class="form-check-input bg-primary border-0 toggle-status"
                                        type="checkbox"
                                        id="statusSwitch{{ $item->id }}"
                                        data-id="{{ $item->id }}"
                                        {{ $item->is_active === 'active' ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td style="border: 1px solid #555;">
                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    {{-- Detail --}}
                                    <a href="{{ route('handphone.show', $item->id) }}" class="btn btn-info btn-sm flex-fill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('handphone.edit', $item->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    {{-- Hapus --}}
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
                            <td colspan="7" class="text-center text-white py-3" style="border: 1px solid #555;">
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

<script>
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const newStatus = this.checked ? 'active' : 'nonactive';

            fetch(`/handphone/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ is_active: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Gagal memperbarui status.');
                    this.checked = !this.checked; // revert jika gagal
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan, cek console/log server.');
                this.checked = !this.checked; // revert jika error
            });
        });
    });
</script>

<style>
    .table-dark tbody tr:hover {
        background-color: #2f3337 !important;
        transition: background-color 0.2s ease;
    }

    /* Toggle switch modern seperti index user */
    .form-check-input.bg-primary {
        cursor: pointer;
        width: 50px;
        height: 26px;
        border-radius: 50px;
        transition: 0.3s;
    }

    .form-check-input.bg-primary:checked {
        background-color: #198754;
    }
</style>
@endsection