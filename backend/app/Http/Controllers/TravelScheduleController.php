<?php

namespace App\Http\Controllers;

use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class TravelScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelSchedule::query();

        if ($request->has('destination')) {
            $query->byDestination($request->destination);
        }

        if ($request->has('date')) {
            $query->byDate($request->date);
        }

        if ($request->has('available') && $request->available == 'true') {
            $query->available();
        }

        $query->where('departure_date', '>=', now()->toDateString());

        $schedules = $query->orderBy('departure_date')
                          ->orderBy('departure_time')
                          ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function show($id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function adminIndex()
    {
        $schedules = TravelSchedule::orderBy('departure_date', 'desc')
                                   ->orderBy('departure_time', 'desc')
                                   ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required|date_format:H:i',
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $schedule = TravelSchedule::create([
            'destination' => $validated['destination'],
            'departure_date' => $validated['departure_date'],
            'departure_time' => $validated['departure_time'],
            'quota' => $validated['quota'],
            'available_quota' => $validated['quota'],
            'price' => $validated['price'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Travel schedule created successfully',
            'data' => $schedule
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        $validated = $request->validate([
            'destination' => 'sometimes|required|string|max:255',
            'departure_date' => 'sometimes|required|date|after_or_equal:today',
            'departure_time' => 'sometimes|required|date_format:H:i',
            'quota' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        if (isset($validated['quota'])) {
            $bookedQuota = $schedule->quota - $schedule->available_quota;
            $validated['available_quota'] = $validated['quota'] - $bookedQuota;

            if ($validated['available_quota'] < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'New quota cannot be less than already booked tickets'
                ], 422);
            }
        }

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Travel schedule updated successfully',
            'data' => $schedule
        ]);
    }

    public function destroy($id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        if ($schedule->ticketOrders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete schedule with existing orders'
            ], 422);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Travel schedule deleted successfully'
        ]);
    }

    public function orders($id)
    {
        $schedule = TravelSchedule::with(['ticketOrders.user', 'ticketOrders.payment'])
                                  ->findOrFail($id);

        $orders = $schedule->ticketOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'passenger_name' => $order->passenger_name,
                'passenger_email' => $order->passenger_email,
                'passenger_phone' => $order->passenger_phone,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment_status' => $order->payment?->payment_status,
                'created_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'schedule' => [
                    'id' => $schedule->id,
                    'destination' => $schedule->destination,
                    'departure_date' => $schedule->departure_date,
                    'departure_time' => $schedule->departure_time,
                    'quota' => $schedule->quota,
                    'available_quota' => $schedule->available_quota,
                ],
                'orders' => $orders
            ]
        ]);
    }
}
