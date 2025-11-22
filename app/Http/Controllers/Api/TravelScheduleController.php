<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class TravelScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelSchedule::query();

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->has('date')) {
            $query->whereDate('departure_datetime', $request->date);
        }

        if ($request->has('available_only') && $request->available_only) {
            $query->where('available_quota', '>', 0);
        }

        $schedules = $query->orderBy('departure_datetime', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'departure_datetime' => 'required|date|after:now',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['available_quota'] = $validated['quota'];

        $schedule = TravelSchedule::create($validated);

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Travel schedule created successfully',
        ], 201);
    }

    public function show($id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $schedule,
        ]);
    }

    public function update(Request $request, $id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        $validated = $request->validate([
            'destination' => 'sometimes|string|max:255',
            'departure_datetime' => 'sometimes|date|after:now',
            'quota' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
        ]);

        if (isset($validated['quota'])) {
            $difference = $validated['quota'] - $schedule->quota;
            $validated['available_quota'] = $schedule->available_quota + $difference;
        }

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Travel schedule updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $schedule = TravelSchedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Travel schedule deleted successfully',
        ]);
    }

    public function passengers($id)
    {
        $schedule = TravelSchedule::with(['ticketOrders.user', 'ticketOrders.payment'])->findOrFail($id);

        $passengers = $schedule->ticketOrders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'passenger_name' => $order->user->name,
                'passenger_email' => $order->user->email,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment_status' => $order->payment->payment_status ?? 'pending',
                'booked_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'schedule' => [
                    'id' => $schedule->id,
                    'destination' => $schedule->destination,
                    'departure_datetime' => $schedule->departure_datetime,
                ],
                'passengers' => $passengers,
            ],
        ]);
    }
}
