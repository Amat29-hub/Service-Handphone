@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg text-light mx-auto" style="max-width: 800px; width: 95%;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-white mb-2">📱 Detail Handphone</h4>
            @if($handphone->image)
                <img src="{{ asset('storage/' . $handphone->image) }}" 
                     alt="{{ $handphone->brand }} {{ $handphone->model }}" 
                     class="rounded shadow-sm border border-3 border-primary"
                     width="120" height="120"
                     style="object-fit: cover;">
            @else
                <div class="rounded bg-dark d-flex align-items-center justify-content-center border border-3 border-secondary shadow-sm"
                     style="width:120px; height:120px;">
                    <i class="bi bi-phone fs-1 text-secondary"></i>
                </div>
            @endif
        </div>

        <hr class="border-secondary">

        <div class="mb-3">
            <h6 class="text-muted mb-1">Brand</h6>
            <p class="fs-6 fw-semibold text-white">{{ $handphone->brand }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Model</h6>
            <p class="fs-6 fw-semibold text-white">{{ $handphone->model }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Tahun Rilis</h6>
            <p class="fs-6 fw-semibold text-white">{{ $handphone->release_year ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <h6 class="text-muted mb-1">Status</h6>
            <span class="badge {{ $handphone->is_active === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill fs-6">
                {{ ucfirst($handphone->is_active) }}
            </span>
        </div>

        <hr class="border-secondary">

        <div class="text-center mt-4">
            <a href="{{ route('handphone.index') }}" class="btn btn-outline-light px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('handphone.edit', $handphone->id) }}" class="btn btn-warning px-4 ms-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection