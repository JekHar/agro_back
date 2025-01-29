<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginApiController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\OrderApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginApiController::class, 'Login']);
Route::post('password/email', [PasswordResetController::class, 'sendResetLink']);
Route::get('orders', [OrderApiController::class, 'index']);
Route::get('orders/{order}', [OrderApiController::class, 'show']);
