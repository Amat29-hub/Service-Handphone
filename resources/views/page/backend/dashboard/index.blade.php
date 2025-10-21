@extends('layouts.app')

@section('content')

<!-- Statistik -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-shopping-cart fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-light small">Order</p>
                    <h6 class="mb-0 text-white">{{ $totalProductOrdered }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-user fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-light small">Customer</p>
                    <h6 class="mb-0 text-white">{{ $totalCustomer }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-wrench fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-light small">Service</p>
                    <h6 class="mb-0 text-white">{{ $totalService }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-wallet fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-light small">Money balance</p>
                    <h6 class="mb-0 text-white">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Service -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">🧾 Service Terbaru</h5>
            <a href="{{ route('service.index') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-list"></i> Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark align-middle text-center mb-0 table-hover"
                   style="border-collapse: collapse; border: 1px solid #555;">
                <thead style="background-color: #2b2b2b;">
                    <tr class="text-white">
                        <th style="border: 1px solid #555;">No</th>
                        <th style="border: 1px solid #555;">Tanggal</th>
                        <th style="border: 1px solid #555;">No Invoice</th>
                        <th style="border: 1px solid #555;">Pelanggan</th>
                        <th style="border: 1px solid #555;">Total Biaya</th>
                        <th style="border: 1px solid #555;">Status</th>
                        <th style="border: 1px solid #555;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentServices as $index => $service)
                        <tr style="border: 1px solid #555;">
                            <td style="border: 1px solid #555;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #555;">{{ \Carbon\Carbon::parse($service->received_date)->format('d M Y') }}</td>
                            <td class="fw-bold text-info" style="border: 1px solid #555;">{{ $service->no_invoice }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $service->customer->name ?? '-' }}</td>
                            <td class="fw-bold text-success" style="border: 1px solid #555;">
                                Rp{{ number_format($service->total_cost ?? 0, 0, ',', '.') }}
                            </td>
                            <td style="border: 1px solid #555;">
                                @php
                                    $statusColor = [
                                        'accepted' => 'primary',
                                        'process' => 'warning',
                                        'finished' => 'success',
                                        'taken' => 'info',
                                        'cancelled' => 'danger',
                                    ][$service->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </td>
                            <td style="border: 1px solid #555;">
                                <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white py-3" style="border: 1px solid #555;">
                                Belum ada data service terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tombol Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<style>
    .table-dark tbody tr:hover {
        background-color: #2f3337 !important;
        transition: background-color 0.2s ease;
    }

    .back-to-top {
        position: fixed;
        bottom: 25px;
        right: 25px;
        display: none;
        z-index: 99;
    }
</style>

<script>
    // Tombol back-to-top muncul saat scroll
    window.addEventListener('scroll', function() {
        const backToTop = document.querySelector('.back-to-top');
        backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
</script>
@endsection