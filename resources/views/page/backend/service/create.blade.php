@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-white rounded p-4 shadow-lg">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 fw-bold">🛠️ Tambah Transaksi Service</h5>
            <a href="{{ route('service.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <form action="{{ route('service.store') }}" method="POST" id="serviceForm">
            @csrf

            {{-- Informasi Utama --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label text-light">No Invoice</label>
                    <input type="text" name="no_invoice" class="form-control bg-dark text-light border-0" value="{{ $no_invoice }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">Pelanggan *</label>
                    <select name="customer_id" class="form-select bg-dark text-light border-0" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">Teknisi *</label>
                    <select name="technician_id" class="form-select bg-dark text-light border-0" required>
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach($technicians as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Detail Handphone dan Kerusakan --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label text-light">Handphone *</label>
                    <select name="handphone_id" class="form-select bg-dark text-light border-0" required>
                        <option value="">-- Pilih Handphone --</option>
                        @foreach($handphones as $h)
                            <option value="{{ $h->id }}">{{ $h->brand }} - {{ $h->model }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label text-light">Deskripsi Kerusakan *</label>
                    <input type="text" name="damage_description" class="form-control bg-dark text-light border-0" placeholder="Tuliskan deskripsi kerusakan..." required>
                </div>
            </div>

            {{-- Produk / Item Service --}}
            <h6 class="text-white mt-4 mb-2 fw-bold">🧾 Produk / Item Service</h6>
            <table class="table table-dark table-striped align-middle" id="productTable">
                <thead>
                    <tr>
                        <th style="width: 8%">Aksi</th>
                        <th style="width: 40%">Produk</th>
                        <th style="width: 10%">Qty</th>
                        <th style="width: 20%">Harga</th>
                        <th style="width: 22%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button>
                        </td>
                        <td>
                            <select name="products[]" class="form-select bg-dark text-light border-0 product-select">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->service_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="qty[]" class="form-control bg-dark text-light border-0 qty" value="1" min="1">
                        </td>
                        <td>
                            <input type="text" name="price[]" class="form-control bg-dark text-light border-0 price" readonly>
                        </td>
                        <td>
                            <input type="text" name="subtotal[]" class="form-control bg-dark text-light border-0 subtotal" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-primary mb-4" id="addRow">
                <i class="fa fa-plus-circle me-2"></i>Tambah Produk
            </button>

            {{-- Biaya Lain --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label text-light">Biaya Lain (Opsional)</label>
                    <input type="number" step="0.01" name="other_cost" id="otherCost" class="form-control bg-dark text-light border-0" value="0">
                </div>
            </div>

            {{-- Ringkasan Total --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-dark text-light mb-3 p-3">
                        <h6>Total Item: <span id="totalItem">0</span></h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-dark text-light mb-3 p-3">
                        <h6>Total Harga Produk: Rp <span id="totalPrice">0</span></h6>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success px-4 fw-bold">
                <i class="fa fa-save me-2"></i> Simpan Service
            </button>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const otherCostInput = document.getElementById('otherCost');
    const totalPriceEl = document.getElementById('totalPrice');
    const tbody = document.querySelector('#productTable tbody');

    function recalcTotals() {
        let totalItem = 0;
        let totalPrice = 0;
        document.querySelectorAll('#productTable tbody tr').forEach(function(row) {
            const qty = parseInt(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const subtotalField = row.querySelector('.subtotal');
            const subtotal = qty * price;
            subtotalField.value = subtotal.toFixed(2);
            totalItem += qty;
            totalPrice += subtotal;
        });
        document.getElementById('totalItem').textContent = totalItem;
        totalPriceEl.textContent = totalPrice.toFixed(2);
    }

    function updateRemoveButtons() {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(btnRow => {
            const btn = btnRow.querySelector('.removeRow');
            btn.disabled = (rows.length === 1);
        });
    }

    function attachEvents(row) {
        row.querySelector('.product-select').addEventListener('change', function() {
            const price = parseFloat(this.selectedOptions[0].dataset.price) || 0;
            row.querySelector('.price').value = price.toFixed(2);
            recalcTotals();
        });

        row.querySelector('.qty').addEventListener('input', recalcTotals);

        row.querySelector('.removeRow').addEventListener('click', function() {
            if (tbody.rows.length > 1) {
                row.remove();
                recalcTotals();
                updateRemoveButtons();
            }
        });
    }

    document.getElementById('addRow').addEventListener('click', function() {
        const newRow = tbody.rows[0].cloneNode(true);
        newRow.querySelectorAll('input').forEach(i => i.value = '');
        newRow.querySelector('.qty').value = 1;
        attachEvents(newRow);
        tbody.appendChild(newRow);
        updateRemoveButtons();
    });

    otherCostInput.addEventListener('input', recalcTotals);

    document.querySelectorAll('#productTable tbody tr').forEach(attachEvents);
    recalcTotals();
    updateRemoveButtons();
});
</script>
@endsection