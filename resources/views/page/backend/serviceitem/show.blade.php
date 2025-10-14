@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center" style="padding-top: 40px; padding-bottom: 40px;">
    <div class="bg-secondary rounded p-4 shadow-lg text-light" style="max-width: 800px; width: 90%;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-white mb-2">🛠️ Detail Service Item</h4>
        </div>

        <hr class="border-secondary">

        <div class="mb-3">
            <h6 class="text-muted mb-1">Nama Service</h6>
            <p class="fs-6 fw-semibold text-white">{{ $serviceitem->service_name }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Harga</h6>
            <p class="fs-6 fw-semibold text-white">Rp {{ number_format($serviceitem->price, 0, ',', '.') }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Status</h6>
            <span class="badge {{ $serviceitem->is_active === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill fs-6">
                {{ ucfirst($serviceitem->is_active) }}
            </span>
        </div>

        <hr class="border-secondary">

        <div class="text-center mt-4">
            <a href="{{ route('serviceitem.index') }}" class="btn btn-outline-light px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('serviceitem.edit', $serviceitem->id) }}" class="btn btn-warning px-4 ms-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection