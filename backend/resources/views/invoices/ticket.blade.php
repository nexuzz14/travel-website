<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3b82f6;
        }

        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header .company-name {
            font-size: 20px;
            color: #3b82f6;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .invoice-info-left,
        .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-info-right {
            text-align: right;
        }

        .info-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            color: #1e40af;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-box p {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .details-table th {
            background-color: #3b82f6;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .details-table tr:hover {
            background-color: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #3b82f6;
        }

        .payment-status {
            display: inline-block;
            padding: 8px 20px;
            background-color: #10b981;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }

        .notes {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }

        .notes h4 {
            color: #92400e;
            margin-bottom: 10px;
        }

        .notes ul {
            list-style-position: inside;
            color: #78350f;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">TRAVEL BOOKING SYSTEM</div>
        <h1>INVOICE PEMESANAN</h1>
        <p style="font-size: 12px; color: #6b7280;">Jl. Raya Jakarta No. 123 | Telp: (021) 1234-5678 | Email: info@travelbooking.com</p>
    </div>

    <div class="invoice-info">
        <div class="invoice-info-left">
            <p><span class="info-label">No. Invoice:</span> <strong>{{ $invoice_number }}</strong></p>
            <p><span class="info-label">Tanggal Invoice:</span> {{ $date }}</p>
            <p><span class="info-label">Order ID:</span> #{{ $order->id }}</p>
        </div>
        <div class="invoice-info-right">
            <p><strong>Status Pembayaran:</strong></p>
            <div class="payment-status">LUNAS</div>
        </div>
    </div>

    <div class="info-box">
        <h3>📋 Informasi Penumpang</h3>
        <p><span class="info-label">Nama Penumpang:</span> {{ $order->passenger_name }}</p>
        <p><span class="info-label">Email:</span> {{ $order->passenger_email }}</p>
        <p><span class="info-label">Telepon:</span> {{ $order->passenger_phone }}</p>
    </div>

    <div class="info-box">
        <h3>🚗 Detail Perjalanan</h3>
        <p><span class="info-label">Tujuan:</span> {{ $schedule->destination }}</p>
        <p><span class="info-label">Tanggal Keberangkatan:</span> {{ \Carbon\Carbon::parse($schedule->departure_date)->format('d F Y') }}</p>
        <p><span class="info-label">Waktu Keberangkatan:</span> {{ $schedule->departure_time }} WIB</p>
        <p><span class="info-label">Jumlah Penumpang:</span> {{ $order->quantity }} orang</p>
    </div>

    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 40%;">Deskripsi</th>
                <th style="width: 15%;" class="text-right">Jumlah</th>
                <th style="width: 20%;" class="text-right">Harga Satuan</th>
                <th style="width: 25%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Tiket Travel</strong><br>
                    <small style="color: #6b7280;">{{ $schedule->destination }}</small><br>
                    <small style="color: #6b7280;">{{ \Carbon\Carbon::parse($schedule->departure_date)->format('d M Y') }} - {{ $schedule->departure_time }}</small>
                </td>
                <td class="text-right">{{ $order->quantity }}</td>
                <td class="text-right">Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span style="display: inline-block; width: 200px; text-align: left;">Subtotal:</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span style="display: inline-block; width: 200px; text-align: left;">Pajak (0%):</span>
            <span>Rp 0</span>
        </div>
        <div class="total-amount">
            <span style="display: inline-block; width: 200px; text-align: left;">TOTAL PEMBAYARAN:</span>
            <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="info-box" style="margin-top: 30px;">
        <h3>💳 Informasi Pembayaran</h3>
        <p><span class="info-label">Metode Pembayaran:</span> {{ $payment->payment_method }}</p>
        <p><span class="info-label">Tanggal Pembayaran:</span> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d F Y H:i') }} WIB</p>
        <p><span class="info-label">Status:</span> <strong style="color: #10b981;">LUNAS</strong></p>
    </div>

    <div class="notes">
        <h4>⚠️ Informasi Penting:</h4>
        <ul>
            <li>Harap tiba di lokasi penjemputan 15 menit sebelum waktu keberangkatan</li>
            <li>Bawa invoice ini saat keberangkatan sebagai bukti pemesanan</li>
            <li>Pembatalan atau perubahan jadwal dapat dilakukan maksimal 24 jam sebelum keberangkatan</li>
            <li>Hubungi customer service kami jika ada pertanyaan: (021) 1234-5678</li>
        </ul>
    </div>

    <div class="qr-section">
        <p style="font-weight: bold; margin-bottom: 10px;">Kode Booking</p>
        <p style="font-size: 24px; font-weight: bold; color: #3b82f6; letter-spacing: 2px;">
            TRV-{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
        </p>
        <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Tunjukkan kode ini kepada petugas saat keberangkatan</p>
    </div>

    <div class="footer">
        <p><strong>Terima kasih telah menggunakan layanan kami!</strong></p>
        <p>Invoice ini sah dan diproses secara elektronik</p>
        <p>Dokumen ini dicetak pada {{ now()->format('d F Y H:i:s') }} WIB</p>
        <p style="margin-top: 10px; font-size: 11px;">
            © {{ date('Y') }} Travel Booking System. All rights reserved.
        </p>
    </div>
</body>
</html>
