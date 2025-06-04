<?php

use App\Http\Controllers\AircraftController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified', 'permissions'])->group(function () {
        //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        Route::redirect('/', '/orders');
        Route::prefix('clients')->name('clients.')->group(function () {
                Route::resource('merchants', MerchantController::class);
        });
        Route::prefix('tenants')->name('tenants.')->group(function () {
                Route::resource('merchants', MerchantController::class);
        });

        Route::get('clients/merchants/{merchant_id}/lots', [LotController::class, 'index'])
                ->name('clients.merchants.lots.index');


        Route::resource('services', ServiceController::class);
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('aircrafts', AircraftController::class);
        Route::resource('lots', LotController::class);

        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
});