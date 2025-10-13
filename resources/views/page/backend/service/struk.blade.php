<!DOCTYPE html>
<html>
<head>
    <title>Struk Servis {{ $service->no_invoice }}</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            color: #000;
        }
        .struk {
            width: 300px;
            margin: auto;
        }
        .header, .footer {
            text-align: center;
        }
        hr {
            border: 1px dashed #000;
        }
        table {
            width: 100%;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body onload="window.print()">
<div class="struk">
    <div class="header">
        <h3>📱 Service Handphone</h3>
        <p>Jl. Contoh No. 123 - Telp: 0812-3456-7890</p>
        <hr>
    </div>

    <p><strong>No Invoice:</strong> {{ $service->no_invoice }}</p>
    <p><strong>Pelanggan:</strong> {{ $service->customer->name ?? '-' }}</p>
    <p><strong>HP:</strong> {{ $service->handphone->brand ?? '' }} {{ $service->handphone->type ?? '' }}</p>
    <hr>

    <table>
        <tr>
            <td>Jenis Servis</td>
            <td style="text-align:right">{{ $service->serviceItem->service_name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Biaya Servis</td>
            <td style="text-align:right">Rp{{ number_format($service->estimated_cost,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Biaya Lain</td>
            <td style="text-align:right">Rp{{ number_format($service->other_cost,0,',','.') }}</td>
        </tr>
        <tr>
            <td><b>Total</b></td>
            <td style="text-align:right"><b>Rp{{ number_format($service->total_cost,0,',','.') }}</b></td>
        </tr>
    </table>

    <hr>
    <p>Status: {{ ucfirst($service->status_paid) }}</p>
    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda 🙏</p>
        <p id="tanggalSekarang"></p>
    </div>
</div>

<script>
    // Tampilkan tanggal & jam real-time saat halaman dibuka
    const now = new Date();
    const options = {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    };
    document.getElementById('tanggalSekarang').textContent =
        now.toLocaleString('id-ID', options);
</script>
</body>
</html>