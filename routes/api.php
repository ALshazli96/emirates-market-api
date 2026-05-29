<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CarController;

// Auth Routes — بدون حماية
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Cars — بدون حماية
Route::get('/cars',       [CarController::class, 'index']);
Route::get('/cars/{id}',  [CarController::class, 'show']);

// Routes محمية بـ JWT
Route::middleware('auth:api')->group(function () {
    Route::get('/me',      [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/cars',   [CarController::class, 'store']);
});