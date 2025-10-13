@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card bg-dark text-light shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-white">Tambah Transaksi Service</h4>
                <a href="{{ route('service.index') }}" class="btn btn-warning fw-semibold px-4 rounded-3">
                    ← Kembali
                </a>
            </div>

            <form action="{{ route('service.store') }}" method="POST">
                @csrf

                {{-- ========== INFORMASI SERVICE ========== --}}
                <div class="card bg-secondary border-0 rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-info">Informasi Utama</h6>
                        <div class="row g-3">
                            {{-- No Invoice --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">No Invoice *</label>
                                <input type="text" name="no_invoice" class="form-control bg-dark text-white rounded-3 border-0" value="{{ $no_invoice }}" readonly>
                            </div>

                            {{-- Pelanggan --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Pelanggan *</label>
                                <select name="customer_id" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} ({{ $c->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Handphone --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Handphone *</label>
                                <select name="handphone_id" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="">-- Pilih Handphone --</option>
                                    @foreach($handphones as $h)
                                        <option value="{{ $h->id }}" {{ old('handphone_id') == $h->id ? 'selected' : '' }}>
                                            {{ $h->brand }} {{ $h->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jenis Service --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Jenis Service *</label>
                                <select name="service_item_id" id="service_item" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="">-- Pilih Service --</option>
                                    @foreach($serviceItems as $item)
                                        <option value="{{ $item->id }}" data-cost="{{ $item->price }}" {{ old('service_item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->service_name }} (Rp {{ number_format($item->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Biaya Perkiraan --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Biaya Perkiraan</label>
                                <input type="number" name="estimated_cost" id="estimated_cost" class="form-control bg-dark text-white rounded-3 border-0" value="{{ old('estimated_cost') }}" readonly>
                            </div>

                            {{-- Biaya Lain --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Biaya Lain</label>
                                <input type="number" name="other_cost" class="form-control bg-dark text-white rounded-3 border-0" value="{{ old('other_cost') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== DETAIL SERVICE ========== --}}
                <div class="card bg-secondary border-0 rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-info">Detail Service</h6>
                        <div class="row g-3">
                            {{-- Deskripsi --}}
                            <div class="col-md-12">
                                <label class="form-label text-light">Deskripsi Kerusakan *</label>
                                <textarea name="damage_description" class="form-control bg-dark text-white rounded-3 border-0" rows="3" required>{{ old('damage_description') }}</textarea>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Metode Pembayaran *</label>
                                <select name="paymentmethod" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="cash" {{ old('paymentmethod') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ old('paymentmethod') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Status *</label>
                                <select name="status" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="process" {{ old('status') == 'process' ? 'selected' : '' }}>Proses</option>
                                    <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                                    <option value="taken" {{ old('status') == 'taken' ? 'selected' : '' }}>Diambil</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            {{-- Status Pembayaran --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Status Pembayaran *</label>
                                <select name="status_paid" class="form-select bg-dark text-white rounded-3 border-0" required>
                                    <option value="unpaid" {{ old('status_paid') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="debt" {{ old('status_paid') == 'debt' ? 'selected' : '' }}>Hutang</option>
                                    <option value="paid" {{ old('status_paid') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Tanggal Diterima *</label>
                                <input type="date" name="received_date" class="form-control bg-dark text-white rounded-3 border-0" value="{{ old('received_date') ?? date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-light">Tanggal Selesai</label>
                                <input type="date" name="completed_date" class="form-control bg-dark text-white rounded-3 border-0" value="{{ old('completed_date') }}">
                            </div>

                            {{-- Dibayar --}}
                            <div class="col-md-4">
                                <label class="form-label text-light">Dibayar</label>
                                <input type="number" name="paid" class="form-control bg-dark text-white rounded-3 border-0" value="{{ old('paid', 0) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== TOMBOL AKSI ========== --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold rounded-3">
                        💾 Simpan
                    </button>
                    <a href="{{ route('service.index') }}" class="btn btn-danger px-5 py-2 fw-semibold rounded-3">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script otomatis biaya --}}
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

    if(serviceSelect.value) {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        estimatedCostInput.value = selectedOption.dataset.cost || 0;
    }
});
</script>
@endpush
@endsection