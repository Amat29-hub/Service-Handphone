@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg text-light">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary pb-2">
            <h4 class="fw-bold text-white mb-0">
                <i class="fa-solid fa-money-bill-wave me-2 text-success"></i> Pembayaran Service
            </h4>
            <a href="{{ route('service.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Informasi Service --}}
        <div class="card bg-dark border-0 mb-4 rounded-3 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold text-info mb-3">
                    <i class="fa-solid fa-circle-info me-2"></i>Informasi Service
                </h6>
                <div class="row gy-2 small">
                    <div class="col-md-6"><strong>No. Invoice:</strong> {{ $service->no_invoice }}</div>
                    <div class="col-md-6"><strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}</div>
                    <div class="col-md-6"><strong>Handphone:</strong> {{ $service->handphone->brand ?? '-' }} {{ $service->handphone->type ?? '' }}</div>
                    <div class="col-md-6"><strong>Teknisi:</strong> {{ $service->technician->name ?? '-' }}</div>
                    <div class="col-md-6"><strong>Kerusakan:</strong> {{ $service->damage_description }}</div>
                    <div class="col-md-6"><strong>Tanggal Diterima:</strong> {{ \Carbon\Carbon::parse($service->received_date)->format('d M Y') }}</div>
                    @if($service->completed_date)
                        <div class="col-md-6"><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($service->completed_date)->format('d M Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="card bg-dark border-0 mb-4 rounded-3 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold text-warning mb-3">
                    <i class="fa-solid fa-receipt me-2"></i>Rincian Biaya
                </h6>
                <div class="table-responsive rounded">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead class="bg-gradient text-light text-center border-bottom border-secondary">
                            <tr>
                                <th style="width: 40%">Item Service</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Harga (Rp)</th>
                                <th style="width: 25%">Subtotal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalService = 0; @endphp
                            @forelse($service->details as $detail)
                                @php
                                    $price = $detail->serviceitem->price ?? 0;
                                    $subtotal = $detail->subtotal ?? ($price * $detail->qty);
                                    $totalService += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $detail->serviceitem->name ?? '-' }}</td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-end">Rp {{ number_format($price, 0, ',', '.') }}</td>
                                    <td class="text-end text-success fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada item service ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Total Section --}}
                @php
                    $otherCost = $service->other_cost ?? 0;
                    $grandTotal = $service->total_cost ?? ($totalService + $otherCost);
                    $paid = $service->paid ?? 0;
                    $remaining = max($grandTotal - $paid, 0);
                @endphp

                <div class="mt-4 bg-secondary rounded-3 p-4 border-start border-4 border-info shadow-sm">
                    <h6 class="fw-bold text-info mb-3">💰 Ringkasan Pembayaran</h6>
                    <div class="small">
                        <p class="mb-1"><strong>Total Item Service:</strong> Rp {{ number_format($totalService, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Biaya Lain:</strong> Rp {{ number_format($otherCost, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong class="text-info">Total Keseluruhan:</strong> Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong class="text-success">Sudah Dibayar:</strong> Rp {{ number_format($paid, 0, ',', '.') }}</p>
                        <p class="fw-bold text-danger mt-2"><i class="fa-solid fa-coins me-1"></i> Sisa Pembayaran: Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <form action="{{ route('service.payment.process', $service->id) }}" method="POST" id="paymentForm">
            @csrf
            <div class="card bg-dark border-0 rounded-3 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-success mb-3">
                        <i class="fa-solid fa-credit-card me-2"></i>Pembayaran Baru
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="paid" class="form-label fw-bold">Nominal Pembayaran (Rp)</label>
                            <input type="number" name="paid" id="paid" class="form-control bg-secondary text-white border-0" placeholder="Masukkan jumlah pembayaran" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="paymentmethod" class="form-label fw-bold">Metode Pembayaran</label>
                            <select name="paymentmethod" id="paymentmethod" class="form-select bg-secondary text-white border-0">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="alert alert-dark border border-info p-3 mt-2">
                        <strong>Status Saat Ini:</strong>
                        <span class="badge 
                            @if($service->status_paid == 'paid') bg-success 
                            @elseif($service->status_paid == 'debt') bg-warning 
                            @else bg-danger @endif text-uppercase px-3 py-2">
                            {{ strtoupper($service->status_paid) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                            <i class="fa fa-check-circle me-2"></i> Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection