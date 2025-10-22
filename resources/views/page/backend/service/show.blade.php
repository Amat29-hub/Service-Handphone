@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card bg-dark text-light rounded-4 shadow-lg p-4">
        <h4 class="fw-bold mb-4">Detail Transaksi Service</h4>

        {{-- Informasi Service --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-2">
                <strong>No Invoice:</strong> {{ $service->no_invoice }}
            </div>
            <div class="col-md-4 mb-2">
                <strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}
            </div>
            <div class="col-md-4 mb-2">
                <strong>Teknisi:</strong> {{ $service->technician->name ?? '-' }}
            </div>

            <div class="col-md-4 mb-2">
                <strong>Handphone:</strong> {{ $service->handphone->brand ?? '-' }} {{ $service->handphone->model ?? '' }}
            </div>
            <div class="col-md-4 mb-2">
                <strong>Status:</strong>
                <span class="badge
                    @if($service->status == 'accepted') bg-primary
                    @elseif($service->status == 'process') bg-warning
                    @elseif($service->status == 'finished') bg-success
                    @elseif($service->status == 'taken') bg-info
                    @elseif($service->status == 'cancelled') bg-danger
                    @else bg-secondary @endif">
                    {{ ucfirst($service->status) }}
                </span>
            </div>
            <div class="col-md-4 mb-2">
                <strong>Status Pembayaran:</strong>
                <span class="badge
                    @if($service->status_paid == 'paid') bg-success
                    @elseif($service->status_paid == 'debt') bg-warning
                    @else bg-danger @endif">
                    {{ ucfirst($service->status_paid) }}
                </span>
            </div>
        </div>

        {{-- Tabel Item Service --}}
        <div class="card bg-secondary text-light rounded-4 mb-4 p-3">
            <h5>Produk / Item Tambahan</h5>
            <div class="table-responsive">
                <table class="table table-dark table-bordered align-middle text-center mb-0"
                       style="border-collapse: collapse; border: 1px solid #555;">
                    <thead style="background-color: #2b2b2b;">
                        <tr class="text-white">
                            <th style="border: 1px solid #555; width:5%">No</th>
                            <th style="border: 1px solid #555;">Nama Produk</th>
                            <th style="border: 1px solid #555; width:20%">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @forelse($service->details as $i => $detail)
                            @php $price = $detail->price ?? 0; $total += $price; @endphp
                            <tr style="border: 1px solid #555;">
                                <td style="border: 1px solid #555;">{{ $i + 1 }}</td>
                                <td style="border: 1px solid #555;">{{ $detail->serviceitem->service_name ?? '-' }}</td>
                                <td style="border: 1px solid #555;">{{ number_format($price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3" style="border: 1px solid #555;">
                                    Belum ada item service ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-end fw-bold mt-2">
                Total Biaya: {{ number_format($service->total_cost ?? $total, 0, ',', '.') }}
            </div>
        </div>

        {{-- Keterangan Kerusakan --}}
        <div class="mb-3">
            <strong>Keterangan Kerusakan:</strong>
            <p>{{ $service->damage_description ?? '-' }}</p>
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{-- Tombol Cancel --}}
            @if($service->status_paid != 'paid' && $service->status != 'finished' && $service->status != 'taken')
                <form action="{{ route('service.cancel', $service->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin membatalkan service ini?')"
                    class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">
                        <i class="bi bi-x-circle me-2"></i> Batalkan Service
                    </button>
                </form>
            @endif

            {{-- Tombol Ambil Barang --}}
            @if($service->status_paid == 'paid' && $service->status == 'finished')
                <form action="{{ route('service.take', $service->id) }}" method="POST" class="ms-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-info px-4 fw-bold shadow-sm">
                        <i class="bi bi-box-seam me-2"></i> Ambil Barang
                    </button>
                </form>
            @endif
         </div>

        <a href="{{ route('service.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
</div>

<style>
    .table-dark tbody tr:hover {
        background-color: #2f3337 !important;
        transition: background-color 0.2s ease;
    }
</style>
@endsection
