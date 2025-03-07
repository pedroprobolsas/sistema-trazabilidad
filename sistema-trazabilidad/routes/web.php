<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\ServiceReceptionController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProductController;

// Authentication routes
Auth::routes(['register' => false]);

// Home / Dashboard
Route::get('/', function () {
    return view('welcome');
});

// Service Orders
Route::get('/service-orders', [ServiceOrderController::class, 'index'])->name('service-orders.index');
Route::get('/service-orders/create', [ServiceOrderController::class, 'create'])->name('service-orders.create');
Route::post('/service-orders', [ServiceOrderController::class, 'store'])->name('service-orders.store');
Route::get('/service-orders/{serviceOrder}', [ServiceOrderController::class, 'show'])->name('service-orders.show');
Route::get('/service-orders/{serviceOrder}/print', [ServiceOrderController::class, 'print'])->name('service-orders.print');

// Service Receptions
Route::get('/service-orders/{serviceOrder}/reception/create', [ServiceReceptionController::class, 'create'])->name('service-receptions.create');
Route::post('/service-orders/{serviceOrder}/reception', [ServiceReceptionController::class, 'store'])->name('service-receptions.store');

// Master Data Management
Route::resource('service-types', ServiceTypeController::class);
Route::resource('providers', ProviderController::class);
Route::resource('products', ProductController::class);
