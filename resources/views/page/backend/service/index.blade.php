@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">🧾 Tabel Transaksi Service</h5>
            <a href="{{ route('service.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Transaksi
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle table-bordered text-center mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Teknisi</th>
                        <th>Handphone</th>
                        <th>Status</th>
                        <th>Status Bayar</th>
                        <th>Metode</th>
                        <th>Tanggal Diterima</th>
                        <th>Tanggal Selesai</th>
                        <th>Total Biaya</th>
                        <th width="200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-info">{{ $service->no_invoice }}</td>
                            <td>{{ $service->customer->name ?? '-' }}</td>
                            <td>{{ $service->technician->name ?? '-' }}</td>
                            <td>{{ $service->handphone->brand ?? '' }} {{ $service->handphone->model ?? '' }}</td>
                            <td>
                                @php
                                    $statusColor = [
                                        'accepted' => 'primary',
                                        'process' => 'warning',
                                        'finished' => 'success',
                                        'taken' => 'info',
                                        'cancelled' => 'danger',
                                    ][$service->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($service->status) }}</span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($service->status_paid == 'paid') bg-success 
                                    @elseif($service->status_paid == 'debt') bg-warning 
                                    @else bg-danger @endif">
                                    {{ ucfirst($service->status_paid) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($service->paymentmethod) }}</td>
                            <td>{{ \Carbon\Carbon::parse($service->received_date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $service->completed_date ? \Carbon\Carbon::parse($service->completed_date)->translatedFormat('d M Y') : '-' }}</td>
                            <td>Rp{{ number_format($service->total_cost ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm flex-fill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="flex-fill m-0 p-0"
                                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                            <td colspan="12" class="text-center text-white py-3">
                                Belum ada data service.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #343a40 !important;
    transition: background-color 0.2s ease;
}
</style>
@endsection