<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnalisisController;
use App\Http\Controllers\Api\ChatController;

// =======================
// PUBLIC ROUTES
// =======================

Route::post('/register',   [AuthController::class, 'register']);
Route::post('/login',      [AuthController::class, 'login']);
Route::post('/input-data', [AnalisisController::class, 'inputData']);

// 🤖 CHAT AI (tanpa login)
Route::post('/chat', [ChatController::class, 'send']);


// =======================
// PROTECTED ROUTES
// =======================

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user',    [AuthController::class, 'user']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
