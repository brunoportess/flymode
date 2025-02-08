<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/busca', \App\Http\Controllers\SearchFlightOrderController::class)->name('orders.search');
        Route::get('/status/{status}', [\App\Http\Controllers\FlightOrderController::class, 'getByStatus'])->name('orders.status');
        Route::get('/{id}', [\App\Http\Controllers\FlightOrderController::class, 'find'])->name('orders.find');
        Route::post('/', [\App\Http\Controllers\FlightOrderController::class, 'store'])->name('orders.store');
        Route::put('/status/{id}', \App\Http\Controllers\StatusFlightOrderController::class)->name('orders.status.update');
    });
});
