<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TicketOrder;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function confirm(Request $request, $orderId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
        ]);

        return DB::transaction(function () use ($request, $orderId, $validated) {
            $order = TicketOrder::with('travelSchedule')
                               ->where('user_id', $request->user()->id)
                               ->findOrFail($orderId);

            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot pay for cancelled order'
                ], 422);
            }

            $payment = Payment::where('ticket_order_id', $orderId)->first();

            if ($payment && $payment->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid'
                ], 422);
            }

            if (!$payment) {
                $payment = Payment::create([
                    'ticket_order_id' => $orderId,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $order->total_price,
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                ]);
            } else {
                $payment->update([
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                ]);
            }

            $order->confirm();

            $invoicePath = $this->invoiceService->generate($order, $payment);
            $payment->update(['invoice_path' => $invoicePath]);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'data' => [
                    'id' => $payment->id,
                    'ticket_order_id' => $payment->ticket_order_id,
                    'payment_method' => $payment->payment_method,
                    'amount' => $payment->amount,
                    'payment_status' => $payment->payment_status,
                    'payment_date' => $payment->payment_date,
                    'invoice_url' => url("/api/payments/{$orderId}/invoice"),
                ]
            ]);
        });
    }

    public function invoice(Request $request, $orderId)
    {
        $order = TicketOrder::with(['payment', 'travelSchedule'])
                           ->where('user_id', $request->user()->id)
                           ->findOrFail($orderId);

        if (!$order->payment || !$order->payment->invoice_path) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }

        $filePath = storage_path('app/public' . $order->payment->invoice_path);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice file not found'
            ], 404);
        }

        return response()->download($filePath, 'invoice-' . $order->id . '.pdf');
    }
}
