@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <h4 class="text-white mb-4 fw-bold">Tambah Service</h4>

    <div class="bg-secondary text-light rounded p-4 shadow-sm">
        <form action="{{ route('service.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>No Invoice</label>
                    <input type="text" name="no_invoice" class="form-control bg-dark text-light" required>
                </div>

                <div class="col-md-4">
                    <label>Pelanggan</label>
                    <select name="customer_id" class="form-select bg-dark text-light" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Teknisi</label>
                    <select name="technician_id" class="form-select bg-dark text-light" required>
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Handphone</label>
                    <select name="handphone_id" class="form-select bg-dark text-light" required>
                        <option value="">-- Pilih Handphone --</option>
                        @foreach($handphones as $hp)
                            <option value="{{ $hp->id }}">{{ $hp->brand }} {{ $hp->model }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Deskripsi Kerusakan</label>
                    <textarea name="damage_description" class="form-control bg-dark text-light" rows="2" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Estimasi Biaya</label>
                    <input type="number" step="0.01" name="estimated_cost" class="form-control bg-dark text-light">
                </div>
                <div class="col-md-4">
                    <label>Biaya Lain</label>
                    <input type="number" step="0.01" name="other_cost" class="form-control bg-dark text-light">
                </div>
                <div class="col-md-4">
                    <label>Metode Pembayaran</label>
                    <select name="paymentmethod" class="form-select bg-dark text-light" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select bg-dark text-light" required>
                        <option value="accepted">Diterima</option>
                        <option value="process">Proses</option>
                        <option value="finished">Selesai</option>
                        <option value="taken">Sudah Diambil</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Status Pembayaran</label>
                    <select name="status_paid" class="form-select bg-dark text-light" required>
                        <option value="paid">Lunas</option>
                        <option value="debt">Hutang</option>
                        <option value="unpaid">Belum Dibayar</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="received_date" class="form-control bg-dark text-light" required>
                </div>

                <div class="col-md-2">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="completed_date" class="form-control bg-dark text-light">
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('service.index') }}" class="btn btn-outline-light me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection