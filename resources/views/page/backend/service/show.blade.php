@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-light rounded p-4">
        <h4 class="fw-bold mb-4">Detail Service</h4>

        <table class="table table-dark table-striped">
            <tr><th>No Invoice</th><td>{{ $service->no_invoice }}</td></tr>
            <tr><th>Pelanggan</th><td>{{ $service->customer->name ?? '-' }}</td></tr>
            <tr><th>Handphone</th><td>{{ $service->handphone->brand ?? '-' }} - {{ $service->handphone->type ?? '-' }}</td></tr>
            <tr><th>Jenis Servis</th><td>{{ $service->serviceItem->service_name ?? '-' }}</td></tr>
            <tr><th>Deskripsi Kerusakan</th><td>{{ $service->damage_description }}</td></tr>
            <tr><th>Estimasi Biaya</th><td>Rp{{ number_format($service->estimated_cost, 0, ',', '.') }}</td></tr>
            <tr><th>Biaya Lain</th><td>Rp{{ number_format($service->other_cost, 0, ',', '.') }}</td></tr>
            <tr><th>Total</th><td>Rp{{ number_format($service->total_cost, 0, ',', '.') }}</td></tr>
            <tr><th>Status Servis</th><td><span class="badge bg-info">{{ $service->status }}</span></td></tr>
            <tr><th>Status Pembayaran</th><td><span class="badge bg-warning">{{ $service->status_paid }}</span></td></tr>
            <tr><th>Tanggal Diterima</th><td>{{ $service->received_date }}</td></tr>
            <tr><th>Tanggal Selesai</th><td>{{ $service->completed_date }}</td></tr>
        </table>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('service.index') }}" class="btn btn-light">Kembali</a>
            <a href="{{ route('service.cetakStruk', $service->id) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-print"></i> Cetak Struk
            </a>
        </div>
    </div>
</div>
@endsection