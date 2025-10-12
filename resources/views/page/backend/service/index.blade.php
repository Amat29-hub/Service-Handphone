@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 fw-bold">Data Transaksi Service</h5>
            <a href="{{ route('service.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Transaksi
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle table-bordered text-center mb-0 shadow">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Handphone</th>
                        <th>Jenis Service</th>
                        <th>Harga Perkiraan</th>
                        <th>Status</th>
                        <th>Status Bayar</th>
                        <th>Metode</th>
                        <th>Diterima</th>
                        <th>Selesai</th>
                        <th>Total Biaya</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-info">{{ $service->no_invoice }}</td>

                            {{-- Pelanggan --}}
                            <td>{{ $service->customer->name ?? '-' }}</td>
                            <td>{{ $service->customer->email ?? '-' }}</td>

                            {{-- Handphone --}}
                            <td>{{ $service->handphone->brand ?? '' }} {{ $service->handphone->model ?? '' }}</td>

                            {{-- Jenis Service --}}
                            <td>{{ $service->serviceItem->service_name ?? '-' }}</td>
                            <td>Rp{{ number_format($service->serviceItem->price ?? 0, 0, ',', '.') }}</td>

                            {{-- Status --}}
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

                            {{-- Status Bayar --}}
                            <td>
                                <span class="badge 
                                    @if($service->status_paid == 'paid') bg-success 
                                    @elseif($service->status_paid == 'debt') bg-warning 
                                    @else bg-danger @endif">
                                    {{ ucfirst($service->status_paid) }}
                                </span>
                            </td>

                            {{-- Metode --}}
                            <td>{{ ucfirst($service->paymentmethod) }}</td>

                            {{-- Tanggal --}}
                            <td>{{ \Carbon\Carbon::parse($service->received_date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $service->completed_date ? \Carbon\Carbon::parse($service->completed_date)->translatedFormat('d M Y') : '-' }}</td>

                            {{-- Total --}}
                            <td class="fw-bold text-success">Rp{{ number_format($service->total_cost ?? 0, 0, ',', '.') }}</td>

                            {{-- Aksi --}}
                            <td>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('service.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-white py-3">Belum ada data transaksi service.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #2d3238 !important;
    transition: background-color 0.2s ease;
}
</style>
@endsection