<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketOrder;
use App\Models\TravelSchedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = TicketOrder::with(['travelSchedule', 'payment'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'travel_schedule' => [
                    'destination' => $order->travelSchedule->destination,
                    'departure_datetime' => $order->travelSchedule->departure_datetime,
                ],
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment' => [
                    'payment_status' => $order->payment->payment_status ?? 'pending',
                    'payment_date' => $order->payment->payment_date ?? null,
                ],
                'created_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $schedule = TravelSchedule::lockForUpdate()->findOrFail($validated['travel_schedule_id']);

            if (!$schedule->hasAvailableQuota($validated['quantity'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient quota available',
                ], 400);
            }

            $totalPrice = $schedule->price * $validated['quantity'];

            $order = TicketOrder::create([
                'user_id' => $request->user()->id,
                'travel_schedule_id' => $schedule->id,
                'order_code' => TicketOrder::generateOrderCode(),
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            Payment::create([
                'ticket_order_id' => $order->id,
                'payment_status' => 'pending',
            ]);

            $schedule->decreaseQuota($validated['quantity']);

            $order->load(['travelSchedule', 'payment']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'travel_schedule_id' => $order->travel_schedule_id,
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'travel_schedule' => [
                        'destination' => $order->travelSchedule->destination,
                        'departure_datetime' => $order->travelSchedule->departure_datetime,
                    ],
                    'payment' => [
                        'payment_status' => $order->payment->payment_status,
                    ],
                ],
                'message' => 'Order created successfully',
            ], 201);
        });
    }
}
