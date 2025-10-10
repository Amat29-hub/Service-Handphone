@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0 text-white">Tabel Handphone</h6>
            <a href="{{ route('handphone.create') }}" class="btn btn-danger btn-sm">+ Tambah</a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th>No</th>
                        <th>Image</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Release Year</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($handphones as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="Image"
                                         width="50" height="50"
                                         class="rounded border border-2 border-primary">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="text-white">{{ $item->brand }}</td>
                            <td class="text-white">{{ $item->model }}</td>
                            <td class="text-white">{{ \Carbon\Carbon::parse($item->release_year)->format('d F Y') }}</td>
                            <td>
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input bg-primary border-0" type="checkbox" role="switch"
                                        id="statusSwitch{{ $item->id }}"
                                        {{ $item->status === 'active' ? 'checked' : '' }}
                                        onchange="toggleStatus({{ $item->id }})">
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('handphone.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('handphone.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white">Tidak ada data handphone.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<script>
    function toggleStatus(id) {
        fetch(`/handphone/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => console.log(data))
          .catch(error => console.error('Error:', error));
    }
</script>

<style>
    /* Tambahan efek hover biar lebih elegan */
    .table-dark tbody tr:hover {
        background-color: #343a40 !important;
    }
</style>
@endsection
