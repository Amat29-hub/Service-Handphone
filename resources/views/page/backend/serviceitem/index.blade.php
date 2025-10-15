@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">🛠️ Tabel Service Item</h5>
            <a href="{{ route('serviceitem.create') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark align-middle text-center mb-0 table-hover"
                   style="border-collapse: collapse; border: 1px solid #555;">
                <thead style="background-color: #2b2b2b;">
                    <tr class="text-white">
                        <th style="border: 1px solid #555;">No</th>
                        <th style="border: 1px solid #555;">Nama Service</th>
                        <th style="border: 1px solid #555;">Harga</th>
                        <th style="border: 1px solid #555;">Status</th>
                        <th style="border: 1px solid #555;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($serviceitems as $index => $item)
                        <tr style="border: 1px solid #555;">
                            <td style="border: 1px solid #555;">{{ $index + 1 }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $item->service_name }}</td>
                            <td class="text-white" style="border: 1px solid #555;">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
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
                                    <a href="{{ route('serviceitem.show', $item->id) }}" class="btn btn-info btn-sm flex-fill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('serviceitem.edit', $item->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('serviceitem.destroy', $item->id) }}" 
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
                            <td colspan="5" class="text-center text-white py-3" style="border: 1px solid #555;">
                                Tidak ada data service item.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- Script toggle status -->
<script>
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;

            fetch(`/serviceitem/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Gagal memperbarui status!');
                    this.checked = !this.checked; // revert jika gagal
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan!');
                this.checked = !this.checked;
            });
        });
    });
</script>

<style>
    .table-dark tbody tr:hover {
        background-color: #2f3337 !important;
        transition: background-color 0.2s ease;
    }

    /* Toggle modern */
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