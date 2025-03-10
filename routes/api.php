<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginApiController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\OrderApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('orders', [OrderApiController::class, 'index']);
    Route::get('orders/{order}', [OrderApiController::class, 'show']);
    Route::put('orders/{orderId}', [OrderApiController::class, 'updateStatus']);
    Route::put('order-lots/{orderId}', [OrderApiController::class, 'updateOrderLot']);


    Route::post('logout', [LoginApiController::class, 'logout']);
});
Route::post('login', [LoginApiController::class, 'Login']);

Route::post('password/email', [PasswordResetController::class, 'sendResetLink']);
