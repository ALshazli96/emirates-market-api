<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PropertyController;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Cars
Route::get('/cars',       [CarController::class, 'index']);
Route::get('/cars/{id}',  [CarController::class, 'show']);

// Properties
Route::get('/properties',       [PropertyController::class, 'index']);
Route::get('/properties/{id}',  [PropertyController::class, 'show']);

// محمية بـ JWT
Route::middleware('auth:api')->group(function () {
    Route::get('/me',           [AuthController::class, 'me']);
    Route::post('/logout',      [AuthController::class, 'logout']);
    Route::post('/cars',        [CarController::class, 'store']);
    Route::post('/properties',  [PropertyController::class, 'store']);
});