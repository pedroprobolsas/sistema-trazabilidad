<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceReceptionController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Service Receptions API
Route::get('/receptions', [ServiceReceptionController::class, 'apiIndex']);
Route::post('/receptions', [ServiceReceptionController::class, 'apiStore']);
Route::get('/receptions/{reception}', [ServiceReceptionController::class, 'apiShow']);
Route::delete('/receptions/{reception}', [ServiceReceptionController::class, 'apiDestroy']);

// Service Orders API
Route::get('/orders', [ServiceOrderController::class, 'apiIndex']);
Route::get('/orders/{order}', [ServiceOrderController::class, 'apiShow']);
Route::post('/orders', [ServiceOrderController::class, 'apiStore']);
Route::put('/orders/{order}', [ServiceOrderController::class, 'apiUpdate']);
Route::delete('/orders/{order}', [ServiceOrderController::class, 'apiDestroy']);

// Providers API
Route::get('/providers', [ProviderController::class, 'apiIndex']);

// Products API
Route::get('/products', [ProductController::class, 'apiIndex']);
