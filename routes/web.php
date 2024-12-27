<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth', 'verified'])->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('services', ServiceController::class);
    Route::prefix('clients')->name('merchants.clients.')->group(function () {
        Route::resource('merchants', MerchantController::class); 
    }); 
    Route::prefix('tenants')->name('merchants.tenants.')->group(function () {
        Route::resource('merchants', MerchantController::class);
    });

});

Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');

require __DIR__ . '/auth.php';