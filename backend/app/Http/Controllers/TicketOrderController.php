<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $schedule = TravelSchedule::lockForUpdate()->findOrFail($validated['travel_schedule_id']);

            if ($schedule->available_quota < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient quota. Available: ' . $schedule->available_quota
                ], 422);
            }

            $totalPrice = $schedule->price * $validated['quantity'];

            $order = TicketOrder::create([
                'user_id' => $request->user()->id,
                'travel_schedule_id' => $validated['travel_schedule_id'],
                'passenger_name' => $validated['passenger_name'],
                'passenger_phone' => $validated['passenger_phone'],
                'passenger_email' => $validated['passenger_email'],
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            $schedule->reduceQuota($validated['quantity']);

            $order->load('travelSchedule');

            return response()->json([
                'success' => true,
                'message' => 'Ticket order created successfully',
                'data' => [
                    'id' => $order->id,
                    'travel_schedule_id' => $order->travel_schedule_id,
                    'passenger_name' => $order->passenger_name,
                    'passenger_phone' => $order->passenger_phone,
                    'passenger_email' => $order->passenger_email,
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'travel_schedule' => [
                        'destination' => $order->travelSchedule->destination,
                        'departure_date' => $order->travelSchedule->departure_date,
                        'departure_time' => $order->travelSchedule->departure_time,
                    ],
                ]
            ], 201);
        });
    }

    public function index(Request $request)
    {
        $orders = TicketOrder::with(['travelSchedule', 'payment'])
                            ->where('user_id', $request->user()->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $data = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'destination' => $order->travelSchedule->destination,
                'departure_date' => $order->travelSchedule->departure_date,
                'departure_time' => $order->travelSchedule->departure_time,
                'passenger_name' => $order->passenger_name,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment_status' => $order->payment?->payment_status ?? 'pending',
                'created_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = TicketOrder::with(['travelSchedule', 'payment'])
                           ->where('user_id', $request->user()->id)
                           ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'travel_schedule' => [
                    'destination' => $order->travelSchedule->destination,
                    'departure_date' => $order->travelSchedule->departure_date,
                    'departure_time' => $order->travelSchedule->departure_time,
                    'price' => $order->travelSchedule->price,
                ],
                'passenger_name' => $order->passenger_name,
                'passenger_phone' => $order->passenger_phone,
                'passenger_email' => $order->passenger_email,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment' => $order->payment ? [
                    'payment_method' => $order->payment->payment_method,
                    'amount' => $order->payment->amount,
                    'payment_status' => $order->payment->payment_status,
                    'payment_date' => $order->payment->payment_date,
                ] : null,
                'created_at' => $order->created_at,
            ]
        ]);
    }

    public function cancel(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $order = TicketOrder::where('user_id', $request->user()->id)
                               ->findOrFail($id);

            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already cancelled'
                ], 422);
            }

            if ($order->status === 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel confirmed order'
                ], 422);
            }

            $order->cancel();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        });
    }
}
