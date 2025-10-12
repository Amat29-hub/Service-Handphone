@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg">
        <h5 class="mb-4 fw-bold">Edit Transaksi Service</h5>

        <form action="{{ route('service.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- No Invoice --}}
                <div class="col-md-4">
                    <label class="form-label">No Invoice</label>
                    <input type="text" name="no_invoice" class="form-control bg-dark text-white" value="{{ $service->no_invoice }}" readonly>
                </div>

                {{-- Pelanggan --}}
                <div class="col-md-4">
                    <label class="form-label">Pelanggan</label>
                    <select name="customer_id" class="form-select bg-dark text-white" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ $service->customer_id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Handphone --}}
                <div class="col-md-4">
                    <label class="form-label">Handphone</label>
                    <select name="handphone_id" class="form-select bg-dark text-white" required>
                        <option value="">-- Pilih Handphone --</option>
                        @foreach($handphones as $h)
                            <option value="{{ $h->id }}" {{ $service->handphone_id == $h->id ? 'selected' : '' }}>
                                {{ $h->brand }} {{ $h->model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Service Item + Biaya Perkiraan --}}
                <div class="col-md-4">
                    <label class="form-label">Jenis Service</label>
                    <select name="service_item_id" id="service_item" class="form-select bg-dark text-white" required>
                        <option value="">-- Pilih Service --</option>
                        @foreach($serviceItems as $item)
                            <option value="{{ $item->id }}" data-cost="{{ $item->price }}" {{ $service->service_item_id == $item->id ? 'selected' : '' }}>
                                {{ $item->service_name }} (Rp {{ number_format($item->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Biaya Perkiraan (Readonly) --}}
                <div class="col-md-4">
                    <label class="form-label">Biaya Perkiraan</label>
                    <input type="number" name="estimated_cost" id="estimated_cost" class="form-control bg-dark text-white" value="{{ $service->estimated_cost }}" readonly>
                </div>

                {{-- Biaya Lain --}}
                <div class="col-md-4">
                    <label class="form-label">Biaya Lain</label>
                    <input type="number" name="other_cost" class="form-control bg-dark text-white" value="{{ $service->other_cost }}">
                </div>

                {{-- Deskripsi Kerusakan --}}
                <div class="col-md-12">
                    <label class="form-label">Deskripsi Kerusakan</label>
                    <textarea name="damage_description" class="form-control bg-dark text-white" rows="2" required>{{ $service->damage_description }}</textarea>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="col-md-4">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="paymentmethod" class="form-select bg-dark text-white" required>
                        <option value="cash" {{ $service->paymentmethod == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ $service->paymentmethod == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select bg-dark text-white" required>
                        <option value="accepted" {{ $service->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="process" {{ $service->status == 'process' ? 'selected' : '' }}>Proses</option>
                        <option value="finished" {{ $service->status == 'finished' ? 'selected' : '' }}>Selesai</option>
                        <option value="taken" {{ $service->status == 'taken' ? 'selected' : '' }}>Diambil</option>
                        <option value="cancelled" {{ $service->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Status Pembayaran --}}
                <div class="col-md-4">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status_paid" class="form-select bg-dark text-white" required>
                        <option value="unpaid" {{ $service->status_paid == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="debt" {{ $service->status_paid == 'debt' ? 'selected' : '' }}>Hutang</option>
                        <option value="paid" {{ $service->status_paid == 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                {{-- Tanggal Diterima --}}
                <div class="col-md-4">
                    <label class="form-label">Tanggal Diterima</label>
                    <input type="date" name="received_date" class="form-control bg-dark text-white" value="{{ $service->received_date->format('Y-m-d') }}" required>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="col-md-4">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="completed_date" class="form-control bg-dark text-white" value="{{ $service->completed_date?->format('Y-m-d') }}">
                </div>

                {{-- Dibayar --}}
                <div class="col-md-4">
                    <label class="form-label">Dibayar</label>
                    <input type="number" name="paid" class="form-control bg-dark text-white" value="{{ $service->paid }}">
                </div>

                {{-- Tombol --}}
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4">💾 Simpan</button>
                    <a href="{{ route('service.index') }}" class="btn btn-danger px-4">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script untuk update estimated_cost otomatis --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_item');
    const estimatedCostInput = document.getElementById('estimated_cost');

    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const cost = selectedOption.dataset.cost || 0;
        estimatedCostInput.value = cost;
    });

    // Set default estimated cost saat load
    if(serviceSelect.value) {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        estimatedCostInput.value = selectedOption.dataset.cost || 0;
    }
});
</script>
@endpush
@endsection