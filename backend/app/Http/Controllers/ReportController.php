<?php

namespace App\Http\Controllers;

use App\Models\TravelSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function passengers(Request $request)
    {
        $query = TravelSchedule::with(['ticketOrders' => function ($query) {
            $query->where('status', '!=', 'cancelled');
        }]);

        if ($request->has('date')) {
            $query->whereDate('departure_date', $request->date);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('departure_date', [$request->start_date, $request->end_date]);
        }

        $schedules = $query->orderBy('departure_date')
                          ->orderBy('departure_time')
                          ->get();

        $report = $schedules->map(function ($schedule) {
            $totalPassengers = $schedule->ticketOrders->sum('quantity');
            $totalOrders = $schedule->ticketOrders->count();
            $totalRevenue = $schedule->ticketOrders->sum('total_price');

            return [
                'schedule_id' => $schedule->id,
                'destination' => $schedule->destination,
                'departure_date' => $schedule->departure_date->format('Y-m-d'),
                'departure_time' => $schedule->departure_time,
                'quota' => $schedule->quota,
                'available_quota' => $schedule->available_quota,
                'total_passengers' => $totalPassengers,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ];
        });

        $summary = [
            'total_schedules' => $schedules->count(),
            'total_passengers' => $report->sum('total_passengers'),
            'total_orders' => $report->sum('total_orders'),
            'total_revenue' => $report->sum('total_revenue'),
        ];

        return response()->json([
            'success' => true,
            'data' => $report,
            'summary' => $summary
        ]);
    }

    public function byPeriod(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = DB::table('ticket_orders')
            ->join('travel_schedules', 'ticket_orders.travel_schedule_id', '=', 'travel_schedules.id')
            ->whereBetween('travel_schedules.departure_date', [$validated['start_date'], $validated['end_date']])
            ->where('ticket_orders.status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(travel_schedules.departure_date) as date'),
                DB::raw('COUNT(DISTINCT ticket_orders.id) as total_orders'),
                DB::raw('SUM(ticket_orders.quantity) as total_passengers'),
                DB::raw('SUM(ticket_orders.total_price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}
