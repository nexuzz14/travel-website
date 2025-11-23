<?php

namespace App\Services;

use App\Models\TicketOrder;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generate(TicketOrder $order, Payment $payment): string
    {
        $data = [
            'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'date' => now()->format('d F Y'),
            'order' => $order,
            'payment' => $payment,
            'schedule' => $order->travelSchedule,
        ];

        $pdf = Pdf::loadView('invoices.ticket', $data);

        $filename = 'invoices/invoice-' . $order->id . '-' . time() . '.pdf';
        $path = storage_path('app/public' . $filename);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return $filename;
    }

    public function getInvoiceHtml(TicketOrder $order, Payment $payment): string
    {
        $invoiceNumber = 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $date = now()->format('d F Y');
        $schedule = $order->travelSchedule;

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Invoice</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #333; }
                .info { margin-bottom: 20px; }
                .info table { width: 100%; }
                .info td { padding: 5px; }
                .details { margin-top: 20px; }
                .details table { width: 100%; border-collapse: collapse; }
                .details th, .details td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                .details th { background-color: #f2f2f2; }
                .total { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; }
                .footer { margin-top: 50px; text-align: center; color: #666; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>INVOICE PEMESANAN TRAVEL</h1>
                <p>No. Invoice: <strong>{$invoiceNumber}</strong></p>
                <p>Tanggal: {$date}</p>
            </div>

            <div class='info'>
                <h3>Informasi Penumpang</h3>
                <table>
                    <tr>
                        <td width='150'><strong>Nama</strong></td>
                        <td>: {$order->passenger_name}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: {$order->passenger_email}</td>
                    </tr>
                    <tr>
                        <td><strong>Telepon</strong></td>
                        <td>: {$order->passenger_phone}</td>
                    </tr>
                </table>
            </div>

            <div class='details'>
                <h3>Detail Pemesanan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tujuan</th>
                            <th>Tanggal Keberangkatan</th>
                            <th>Waktu</th>
                            <th>Jumlah Tiket</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$schedule->destination}</td>
                            <td>" . $schedule->departure_date->format('d F Y') . "</td>
                            <td>{$schedule->departure_time}</td>
                            <td>{$order->quantity}</td>
                            <td>Rp " . number_format($schedule->price, 0, ',', '.') . "</td>
                            <td>Rp " . number_format($order->total_price, 0, ',', '.') . "</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class='total'>
                <p>Total Pembayaran: Rp " . number_format($payment->amount, 0, ',', '.') . "</p>
                <p>Metode Pembayaran: {$payment->payment_method}</p>
                <p>Status: <span style='color: green;'>LUNAS</span></p>
            </div>

            <div class='footer'>
                <p>Terima kasih telah menggunakan layanan kami!</p>
                <p>Invoice ini sah dan diproses secara elektronik</p>
            </div>
        </body>
        </html>
        ";
    }
}
