<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ObesityController;
use App\Models\Article;

// =======================
// PUBLIC ROUTES
// =======================

Route::post('/register',   [ApiController::class, 'register']);
Route::post('/login',      [ApiController::class, 'login']);
Route::post('/input-data', [ApiController::class, 'inputData']);
Route::post('/chat',       [ApiController::class, 'chat']);

// Prediksi obesitas (public)
Route::post('/predict-obesity', [ObesityController::class, 'predict']);

// Artikel
Route::get('/articles', function () {
    return response()->json(
        Article::where('status', 'published')->get()
    );
});

// =======================
// PROTECTED ROUTES
// =======================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    [ApiController::class, 'user']);
    Route::post('/logout', [ApiController::class, 'logout']);

    // Simpan & history prediksi obesitas
    Route::post('/obesity/save',    [ObesityController::class, 'save']);
    Route::get('/obesity/history',  [ObesityController::class, 'history']);
});
