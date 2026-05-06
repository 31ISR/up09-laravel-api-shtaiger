<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class,'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class,'index']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/create', [BookingController::class,'create']);
    Route::delete('/bookings/{booking}', [BookingController::class,'destroy']);
    Route::get('/bookings/{booking}', [BookingController::class,'show']);
    });