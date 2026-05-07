<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnalisisController;
use App\Http\Controllers\Api\ChatController;

// Public routes
Route::post('/register',   [AuthController::class, 'register']);
Route::post('/login',      [AuthController::class, 'login']);
Route::post('/input-data', [AnalisisController::class, 'inputData']);

// Protected routes (butuh token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🤖 Chat AI
    Route::post('/chat',   [ChatController::class, 'send']);
});
