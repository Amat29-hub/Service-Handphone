@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">🧾 Tabel Transaksi Service</h5>
            <a href="{{ route('service.create') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Transaksi
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark align-middle text-center mb-0 table-hover"
                   style="border-collapse: collapse; border: 1px solid #555;">
                <thead style="background-color: #2b2b2b;">
                    <tr class="text-white">
                        <th style="border: 1px solid #555;">No</th>
                        <th style="border: 1px solid #555;">No Invoice</th>
                        <th style="border: 1px solid #555;">Pelanggan</th>
                        <th style="border: 1px solid #555;">Email</th>
                        <th style="border: 1px solid #555;">Handphone</th>
                        <th style="border: 1px solid #555;">Jenis Service</th>
                        <th style="border: 1px solid #555;">Harga Perkiraan</th>
                        <th style="border: 1px solid #555;">Status</th>
                        <th style="border: 1px solid #555;">Status Bayar</th>
                        <th style="border: 1px solid #555;">Metode</th>
                        <th style="border: 1px solid #555;">Diterima</th>
                        <th style="border: 1px solid #555;">Selesai</th>
                        <th style="border: 1px solid #555;">Total Biaya</th>
                        <th style="border: 1px solid #555;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $index => $service)
                        <tr style="border: 1px solid #555;">
                            <td style="border: 1px solid #555;">{{ $index + 1 }}</td>
                            <td class="fw-bold text-info" style="border: 1px solid #555;">{{ $service->no_invoice }}</td>

                            {{-- Pelanggan --}}
                            <td class="text-white" style="border: 1px solid #555;">{{ $service->customer->name ?? '-' }}</td>
                            <td class="text-white" style="border: 1px solid #555;">{{ $service->customer->email ?? '-' }}</td>

                            {{-- Handphone --}}
                            <td class="text-white" style="border: 1px solid #555;">
                                {{ $service->handphone->brand ?? '' }} {{ $service->handphone->model ?? '' }}
                            </td>

                            {{-- Jenis Service --}}
                            <td class="text-white" style="border: 1px solid #555;">{{ $service->serviceItem->service_name ?? '-' }}</td>
                            <td class="text-white" style="border: 1px solid #555;">
                                Rp{{ number_format($service->serviceItem->price ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
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
                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($service->status) }}</span>
                            </td>

                            {{-- Status Bayar --}}
                            <td style="border: 1px solid #555;">
                                <span class="badge 
                                    @if($service->status_paid == 'paid') bg-success 
                                    @elseif($service->status_paid == 'debt') bg-warning 
                                    @else bg-danger @endif">
                                    {{ ucfirst($service->status_paid) }}
                                </span>
                            </td>

                            {{-- Metode --}}
                            <td class="text-white" style="border: 1px solid #555;">{{ ucfirst($service->paymentmethod) }}</td>

                            {{-- Tanggal --}}
                            <td class="text-white" style="border: 1px solid #555;">
                                {{ \Carbon\Carbon::parse($service->received_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="text-white" style="border: 1px solid #555;">
                                {{ $service->completed_date ? \Carbon\Carbon::parse($service->completed_date)->translatedFormat('d M Y') : '-' }}
                            </td>

                            {{-- Total --}}
                            <td class="fw-bold text-success" style="border: 1px solid #555;">
                                Rp{{ number_format($service->total_cost ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Aksi --}}
                            <td style="border: 1px solid #555;">
                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    {{-- Detail --}}
                                    <a href="{{ route('service.show', $service->id) }}" class="btn btn-info btn-sm flex-fill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    {{-- Payment --}}
                                    <a href="{{ route('service.payment', $service->id) }}" class="btn btn-success btn-sm flex-fill">
                                        💰 Bayar
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('service.destroy', $service->id) }}" 
                                          method="POST" 
                                          class="flex-fill m-0 p-0"
                                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-white py-3" style="border: 1px solid #555;">
                                Belum ada data transaksi service.
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

    /* Tombol Back to Top */
    .back-to-top {
        position: fixed;
        bottom: 25px;
        right: 25px;
        display: none;
        z-index: 99;
    }
</style>

<script>
    // Animasi tombol back-to-top
    window.addEventListener('scroll', function() {
        const backToTop = document.querySelector('.back-to-top');
        if (window.scrollY > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });
</script>
@endsection