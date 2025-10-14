@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card bg-dark text-light rounded-4 shadow-lg p-4">
        <h4 class="fw-bold mb-4">Detail Transaksi Service</h4>

        <div class="row mb-3">
            <div class="col-md-4">
                <strong>No Invoice:</strong> {{ $service->no_invoice }}
            </div>
            <div class="col-md-4">
                <strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}
            </div>
            <div class="col-md-4">
                <strong>Teknisi:</strong> {{ $service->technician->name ?? '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Handphone:</strong> {{ $service->handphone->brand ?? '-' }} {{ $service->handphone->model ?? '' }}
            </div>
            <div class="col-md-4">
                <strong>Status:</strong> {{ ucfirst($service->status) }}
            </div>
            <div class="col-md-4">
                <strong>Status Pembayaran:</strong> {{ ucfirst($service->status_paid) }}
            </div>
        </div>

        <div class="card bg-secondary text-light rounded-4 mb-4 p-3">
            <h5>Produk / Item Tambahan</h5>
            <table class="table table-dark table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($service->details as $i => $detail)
                        @php $total += $detail->subtotal; @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $detail->serviceitem->service_name ?? '-' }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @if($service->details->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada item service ditambahkan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="text-end fw-bold mt-2">
                Total Biaya: {{ number_format($service->total_cost ?? $total, 0, ',', '.') }}
            </div>
        </div>

        <div>
            <strong>Keterangan Kerusakan:</strong>
            <p>{{ $service->damage_description ?? '-' }}</p>
        </div>

        <a href="{{ route('service.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
</div>
@endsection