@extends('layouts.app')

@section('content')

<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-shopping-cart fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Order</p>
                    <h6 class="mb-0">{{ $totalProductOrdered }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Customer</p>
                    <h6 class="mb-0">{{ $totalCustomer }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-wrench fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Service</p>
                    <h6 class="mb-0">{{ $totalService }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-wallet fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Money balance</p>
                    <h6 class="mb-0">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Latest service</h6>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">No</th>
                        <th scope="col">Date</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse ($recentServices as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($service->received_date)->format('d M Y') }}</td>
                            <td class="text-info fw-bold">{{ $service->no_invoice }}</td>
                            <td>{{ $service->customer->name ?? '-' }}</td>
                            <td>Rp{{ number_format($service->total_cost, 0, ',', '.') }}</td>
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
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('service.show', $service->id) }}"
                                   class="btn btn-sm btn-outline-light">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white py-3">
                                Belum ada riwayat pemesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Sales End -->

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
@endsection
