<?php

use App\Http\Controllers\AircraftController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');

Route::middleware(['auth', 'verified'])->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('clients')->name('merchants.clients.')->group(function () {
        Route::resource('merchants', MerchantController::class); 
    }); 
    Route::prefix('tenants')->name('merchants.tenants.')->group(function () {
        Route::resource('merchants', MerchantController::class);
    });

    Route::resource('services', ServiceController::class);
    Route::resource('aircrafts', AircraftController::class);

});
require __DIR__ . '/auth.php';