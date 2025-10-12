@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg mx-auto" style="max-width: 900px;">
        <h4 class="fw-bold mb-4 text-center">📄 Detail Transaksi Service</h4>

        <div class="row g-3">
            {{-- No Invoice --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">No Invoice</h6>
                <p class="fw-semibold text-info">{{ $service->no_invoice }}</p>
            </div>

            {{-- Pelanggan --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Pelanggan</h6>
                <p class="fw-semibold">{{ $service->customer->name ?? '-' }}</p>
            </div>

            {{-- Handphone --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Handphone</h6>
                <p class="fw-semibold">
                    {{ $service->handphone->brand ?? '' }} {{ $service->handphone->model ?? '' }}
                </p>
            </div>

            {{-- Jenis Service --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Jenis Service</h6>
                <p class="fw-semibold">{{ $service->serviceItem->service_name ?? '-' }}</p>
            </div>

            {{-- Biaya Perkiraan --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Biaya Perkiraan</h6>
                <p class="fw-semibold">Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}</p>
            </div>

            {{-- Biaya Lain --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Biaya Lain</h6>
                <p class="fw-semibold">Rp {{ number_format($service->other_cost ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Deskripsi Kerusakan --}}
            <div class="col-md-12">
                <h6 class="text-muted mb-1">Deskripsi Kerusakan</h6>
                <p class="fw-semibold">{{ $service->damage_description }}</p>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Metode Pembayaran</h6>
                <p class="fw-semibold text-capitalize">{{ $service->paymentmethod }}</p>
            </div>

            {{-- Status --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Status Service</h6>
                @php
                    $statusColor = [
                        'accepted' => 'primary',
                        'process' => 'warning',
                        'finished' => 'success',
                        'taken' => 'info',
                        'cancelled' => 'danger',
                    ][$service->status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $statusColor }} px-3 py-2 rounded-pill">
                    {{ ucfirst($service->status) }}
                </span>
            </div>

            {{-- Status Pembayaran --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Status Pembayaran</h6>
                <span class="badge 
                    @if($service->status_paid == 'paid') bg-success 
                    @elseif($service->status_paid == 'debt') bg-warning 
                    @else bg-danger @endif
                    px-3 py-2 rounded-pill">
                    {{ ucfirst($service->status_paid) }}
                </span>
            </div>

            {{-- Tanggal Diterima --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Tanggal Diterima</h6>
                <p class="fw-semibold">{{ \Carbon\Carbon::parse($service->received_date)->translatedFormat('d F Y') }}</p>
            </div>

            {{-- Tanggal Selesai --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Tanggal Selesai</h6>
                <p class="fw-semibold">
                    {{ $service->completed_date ? \Carbon\Carbon::parse($service->completed_date)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>

            {{-- Dibayar --}}
            <div class="col-md-4">
                <h6 class="text-muted mb-1">Dibayar</h6>
                <p class="fw-semibold">Rp {{ number_format($service->paid ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Total Biaya --}}
            <div class="col-md-12 text-end border-top border-secondary pt-3">
                <h5 class="fw-bold text-success mb-0">
                    Total: Rp {{ number_format(($service->estimated_cost + ($service->other_cost ?? 0)), 0, ',', '.') }}
                </h5>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="text-center mt-4">
            <a href="{{ route('service.index') }}" class="btn btn-outline-light px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning px-4 ms-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>
</div>

<style>
    .fw-semibold { font-weight: 600; }
    .badge { font-size: 0.9rem; }
    .bg-secondary { background-color: #2b2f36 !important; }
</style>
@endsection