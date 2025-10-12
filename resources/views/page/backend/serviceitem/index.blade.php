@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">🛠️ Tabel Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Data Item Service</h6>
            <a href="{{ route('service-item.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-1"></i> Tambah Item
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-bordered align-middle text-center mb-0">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Service</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($service_items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->service_name }}</td>
                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $item->is_active == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($item->is_active) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('service-item.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('service-item.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('service-item.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus item ini?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada item service.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection