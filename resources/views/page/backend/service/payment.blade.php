@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4 shadow-lg text-light">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0">💰 Pembayaran Service</h5>
            <a href="{{ route('service.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Informasi Transaksi --}}
        <div class="card bg-dark border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-primary mb-3">🧾 Informasi Service</h6>
                <div class="row">
                    <div class="col-md-6 mb-2"><strong>No. Invoice:</strong> {{ $service->no_invoice }}</div>
                    <div class="col-md-6 mb-2"><strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}</div>
                    <div class="col-md-6 mb-2"><strong>Handphone:</strong> {{ $service->handphone->brand ?? '-' }} {{ $service->handphone->type ?? '' }}</div>
                    <div class="col-md-6 mb-2"><strong>Kerusakan:</strong> {{ $service->damage_description }}</div>
                    <div class="col-md-6 mb-2"><strong>Tanggal Diterima:</strong> {{ \Carbon\Carbon::parse($service->received_date)->format('d/m/Y') }}</div>
                    @if($service->completed_date)
                        <div class="col-md-6 mb-2"><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($service->completed_date)->format('d/m/Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="card bg-dark border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-primary mb-3">💵 Rincian Biaya</h6>
                <table class="table table-dark table-striped table-hover mb-0">
                    <tr><th>Estimasi Biaya</th><td>Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Lain</th><td>Rp {{ number_format($service->other_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Total</th><td class="fw-bold text-warning">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Sudah Dibayar</th><td class="fw-bold text-success">Rp {{ number_format($service->paid, 0, ',', '.') }}</td></tr>
                    <tr><th>Sisa Pembayaran</th><td class="fw-bold text-danger">Rp {{ number_format(max($service->total_cost - $service->paid, 0), 0, ',', '.') }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <form action="{{ route('service.payment.process', $service->id) }}" method="POST" id="paymentForm">
            @csrf
            <div class="card bg-dark border-0">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">💳 Pembayaran Baru</h6>

                    <div class="mb-3">
                        <label for="paid" class="form-label fw-bold">Nominal Pembayaran Baru (Rp)</label>
                        <input type="number" name="paid" id="paid" class="form-control bg-secondary text-white border-0" placeholder="Masukkan jumlah pembayaran" required>
                    </div>

                    <div class="mb-3">
                        <label for="paymentmethod" class="form-label fw-bold">Metode Pembayaran</label>
                        <select name="paymentmethod" id="paymentmethod" class="form-select bg-secondary text-white border-0">
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="alert alert-info p-2 mt-3">
                        <strong>Status Saat Ini:</strong>
                        <span class="badge 
                            @if($service->status_paid == 'paid') bg-success 
                            @elseif($service->status_paid == 'debt') bg-warning 
                            @else bg-danger @endif">
                            {{ strtoupper($service->status_paid) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success px-4 fw-bold">
                            <i class="fa fa-check-circle me-2"></i> Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Template Struk --}}
<div id="printArea" style="display:none;">
    <div style="font-family: monospace; width: 300px; margin: auto;">
        <h3 style="text-align:center;">📱 SERVICE HANDPHONE</h3>
        <p style="text-align:center;">==============================</p>
        <p><strong>No.Invoice:</strong> {{ $service->no_invoice }}</p>
        <p><strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}</p>
        <p><strong>HP:</strong> {{ $service->handphone->brand ?? '-' }} {{ $service->handphone->type ?? '' }}</p>
        <p><strong>Tanggal:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p>==============================</p>
        <p>Estimasi Biaya : Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}</p>
        <p>Biaya Lain     : Rp {{ number_format($service->other_cost, 0, ',', '.') }}</p>
        <p>Total          : Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>
        <p>Sudah Dibayar  : Rp {{ number_format($service->paid, 0, ',', '.') }}</p>
        <p>Pembayaran Baru: Rp <span id="printPaid">0</span></p>
        <p>Kembalian      : Rp <span id="printChange">0</span></p>
        <p>==============================</p>
        <p style="text-align:center;">Terima Kasih 🙏</p>
        <p style="text-align:center;">Semoga Hari Anda Menyenangkan!</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btnPrint').addEventListener('click', function() {
    const paidBaru = parseFloat(document.getElementById('paid').value) || 0;
    const total = {{ $service->total_cost }};
    const sudahBayar = {{ $service->paid }};
    const totalDibayar = paidBaru + sudahBayar;
    const change = (totalDibayar > total) ? totalDibayar - total : 0;

    document.getElementById('printPaid').textContent = new Intl.NumberFormat('id-ID').format(paidBaru);
    document.getElementById('printChange').textContent = new Intl.NumberFormat('id-ID').format(change);

    let printWindow = window.open('', '', 'width=400,height=600');
    printWindow.document.write(document.getElementById('printArea').innerHTML);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});
</script>
@endsection