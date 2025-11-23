<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\TicketOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/travel-schedules', [TravelScheduleController::class, 'index']);
    Route::get('/travel-schedules/{id}', [TravelScheduleController::class, 'show']);

    Route::get('/ticket-orders', [TicketOrderController::class, 'index']);
    Route::post('/ticket-orders', [TicketOrderController::class, 'store']);
    Route::get('/ticket-orders/{id}', [TicketOrderController::class, 'show']);
    Route::post('/ticket-orders/{id}/cancel', [TicketOrderController::class, 'cancel']);

    Route::post('/payments/{orderId}/confirm', [PaymentController::class, 'confirm']);
    Route::get('/payments/{orderId}/invoice', [PaymentController::class, 'invoice']);

    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/travel-schedules', [TravelScheduleController::class, 'adminIndex']);
        Route::post('/travel-schedules', [TravelScheduleController::class, 'store']);
        Route::get('/travel-schedules/{id}', [TravelScheduleController::class, 'show']);
        Route::put('/travel-schedules/{id}', [TravelScheduleController::class, 'update']);
        Route::delete('/travel-schedules/{id}', [TravelScheduleController::class, 'destroy']);
        Route::get('/travel-schedules/{id}/orders', [TravelScheduleController::class, 'orders']);


        Route::get('/reports/passengers', [ReportController::class, 'passengers']);
        Route::get('/reports/by-period', [ReportController::class, 'byPeriod']);
    });
});
