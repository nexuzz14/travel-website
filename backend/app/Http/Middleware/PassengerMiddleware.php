<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PassengerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isPassenger()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Passenger access required.'
            ], 403);
        }

        return $next($request);
    }
}
