@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Detail Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Informasi Service</h6>
            <a href="{{ route('service-item.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <table class="table table-dark table-bordered align-middle mb-0">
            <tr>
                <th style="width: 200px;">Nama Service</th>
                <td>{{ $service_item->service_name }}</td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp{{ number_format($service_item->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($service_item->is_active === 'active')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection