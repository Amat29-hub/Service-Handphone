@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Detail Service Item</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold">Informasi Service</h6>
            <a href="{{ route('serviceitem.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <table class="table table-dark table-bordered align-middle mb-0">
            <tr>
                <th style="width: 220px;">Nama Service</th>
                <td>{{ $serviceitem->service_name }}</td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp{{ number_format($serviceitem->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($serviceitem->is_active === 'active')
                        <span class="badge bg-success px-3 py-2">Aktif</span>
                    @else
                        <span class="badge bg-danger px-3 py-2">Tidak Aktif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat pada</th>
                <td>{{ $serviceitem->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <th>Terakhir diperbarui</th>
                <td>{{ $serviceitem->updated_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection