@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tabel Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Data Service Item</h6>
            <a href="{{ route('service-item.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-1"></i> Tambah
            </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-dark table-bordered align-middle text-center mb-0">
                <thead class="text-light bg-dark">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Service</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($service_items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->service_name }}</td>
                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->is_active === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('service-item.show', $item->id) }}" class="btn btn-info btn-sm d-inline-flex align-items-center">
                                    <i class="fa fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data service item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
