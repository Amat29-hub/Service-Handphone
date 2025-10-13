@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">🧾 Struk Pembayaran</h4>
            <a href="{{ route('service.index') }}" class="btn btn-outline-light btn-sm">Kembali</a>
        </div>

        <div class="bg-dark rounded p-3 mb-3">
            <p class="mb-1"><strong>Nomor Invoice:</strong> {{ $service->no_invoice }}</p>
            <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($service->completed_date)->format('d M Y H:i') }}</p>
        </div>

        <div class="mb-3">
            <p><strong>Nama Customer:</strong> {{ $service->customer->name ?? '-' }}</p>
            <p><strong>Handphone:</strong> {{ $service->handphone->brand ?? '-' }} - {{ $service->handphone->model ?? '-' }}</p>
            <p><strong>Deskripsi Kerusakan:</strong> {{ $service->damage_description }}</p>
        </div>

        <table class="table table-dark table-striped mb-3">
            <tr>
                <th>Biaya Perkiraan</th>
                <td>Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Biaya Lain</th>
                <td>Rp {{ number_format($service->other_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="fw-bold text-warning">
                <th>Total Biaya</th>
                <td>Rp {{ number_format($service->total_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Jumlah Dibayar</th>
                <td>Rp {{ number_format($service->paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td>{{ strtoupper($service->paymentmethod) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td class="{{ $service->status_paid == 'paid' ? 'text-success' : 'text-danger' }}">
                    {{ strtoupper($service->status_paid) }}
                </td>
            </tr>
        </table>

        <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-light fw-bold">
                🖨️ Cetak Struk
            </button>
        </div>
    </div>
</div>
@endsection