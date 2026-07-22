<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\HotelController;
use App\Http\Controllers\Api\V1\RoomController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Hotel public endpoints
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::get('/hotels/{hotel}/rooms', [HotelController::class, 'rooms']);
    Route::get('/rooms/availability', [BookingController::class, 'availability']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::apiResource('bookings', BookingController::class)->only(['index', 'store', 'show']);
        Route::apiResource('guests', GuestController::class)->only(['index', 'show', 'update']);
        Route::apiResource('rooms', RoomController::class)->only(['index', 'show']);
    });
});
