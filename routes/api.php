<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginApiController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\FlightApiController;

Route::post('login', [LoginApiController::class, 'Login']);
Route::post('password/email', [PasswordResetController::class, 'sendResetLink']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('orders', [OrderApiController::class, 'index']);
    Route::get('orders/{order}', [OrderApiController::class, 'show']);
    Route::put('orders/{orderId}', [OrderApiController::class, 'updateStatus']);
    Route::put('order-lots/{orderId}', [OrderApiController::class, 'updateOrderLot']);


    // Flight endpoints
    Route::put('flights/{flightId}/status', [FlightApiController::class, 'updateStatus']);

    Route::post('logout', [LoginApiController::class, 'logout']);
});
