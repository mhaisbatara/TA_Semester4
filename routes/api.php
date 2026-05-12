<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
<<<<<<< HEAD
use App\Http\Controllers\ObesityController;

Route::post('/predict-obesity', [ObesityController::class, 'predict']);
=======
use App\Models\Article;
>>>>>>> d9838031c64c6b0632eb2aeb01a346469c5bae87

// =======================
// PUBLIC ROUTES
// =======================

Route::post('/register',   [ApiController::class, 'register']);
Route::post('/login',      [ApiController::class, 'login']);
Route::post('/input-data', [ApiController::class, 'inputData']);

// 🤖 CHAT AI (tanpa login)
Route::post('/chat', [ApiController::class, 'chat']);

// =======================
// PROTECTED ROUTES
// =======================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    [ApiController::class, 'user']);
    Route::post('/logout', [ApiController::class, 'logout']);
});

Route::get('/articles', function () {

    return response()->json(
        Article::where('status', 'published')->get()
    );

});