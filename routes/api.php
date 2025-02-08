<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/busca', \App\Http\Controllers\SearchFlightOrderController::class);
        Route::get('/status/{status}', [\App\Http\Controllers\FlightOrderController::class, 'getByStatus']);
        Route::get('/{id}', [\App\Http\Controllers\FlightOrderController::class, 'find']);
        Route::post('/', [\App\Http\Controllers\FlightOrderController::class, 'store']);
        Route::put('/status/{id}', \App\Http\Controllers\StatusFlightOrderController::class);
    });
});
