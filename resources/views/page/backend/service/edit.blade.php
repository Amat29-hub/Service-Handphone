@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg">
        <h5 class="mb-4 fw-bold">✏️ Edit Transaksi Service</h5>

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

                {{-- Jenis Service --}}
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

                {{-- Biaya Perkiraan --}}
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
                        <option value="qris" {{ $service->paymentmethod == 'qris' ? 'selected' : '' }}>QRIS</option>
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

                {{-- Status Pembayaran (otomatis) --}}
                <div class="col-md-4">
                    <label class="form-label">Status Pembayaran</label>
                    <input type="text" name="status_paid" id="status_paid" class="form-control bg-dark text-white" value="{{ ucfirst($service->status_paid) }}" readonly>
                </div>

                {{-- Tanggal Diterima --}}
                <div class="col-md-4">
                    <label class="form-label">Tanggal Diterima</label>
                    <input type="date" name="received_date" class="form-control bg-dark text-white" value="{{ $service->received_date ? $service->received_date->format('Y-m-d') : now()->format('Y-m-d') }}" required>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="col-md-4">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="completed_date" class="form-control bg-dark text-white" value="{{ $service->completed_date ? $service->completed_date->format('Y-m-d') : '' }}">
                </div>

                {{-- Dibayar --}}
                <div class="col-md-4">
                    <label class="form-label">Dibayar (Rp)</label>
                    <input type="number" name="paid" id="paid" class="form-control bg-dark text-white" value="{{ $service->paid ?? 0 }}">
                </div>

                {{-- Total Biaya --}}
                <div class="col-md-4">
                    <label class="form-label">Total Biaya (Rp)</label>
                    <input type="number" id="total_cost" class="form-control bg-dark text-white" value="{{ $service->total_cost }}" readonly>
                </div>

                {{-- Tombol --}}
                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-success px-4">💾 Simpan</button>
                    <a href="{{ route('service.index') }}" class="btn btn-danger px-4">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script otomatis update biaya & status bayar --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_item');
    const estimatedCostInput = document.getElementById('estimated_cost');
    const otherCostInput = document.querySelector('[name="other_cost"]');
    const paidInput = document.getElementById('paid');
    const totalCostInput = document.getElementById('total_cost');
    const statusPaidInput = document.getElementById('status_paid');

    function updateTotalAndStatus() {
        const estimated = parseFloat(estimatedCostInput.value) || 0;
        const other = parseFloat(otherCostInput.value) || 0;
        const paid = parseFloat(paidInput.value) || 0;
        const total = estimated + other;

        totalCostInput.value = total;

        if (paid <= 0) {
            statusPaidInput.value = 'Unpaid';
        } else if (paid < total) {
            statusPaidInput.value = 'Debt';
        } else {
            statusPaidInput.value = 'Paid';
        }
    }

    serviceSelect.addEventListener('change', function() {
        const cost = this.options[this.selectedIndex].dataset.cost || 0;
        estimatedCostInput.value = cost;
        updateTotalAndStatus();
    });

    otherCostInput.addEventListener('input', updateTotalAndStatus);
    paidInput.addEventListener('input', updateTotalAndStatus);

    updateTotalAndStatus();
});
</script>
@endpush
@endsection