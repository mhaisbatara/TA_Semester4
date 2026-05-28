<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Models\Article;

// =======================
// PUBLIC ROUTES
// =======================

Route::post('/register',   [ApiController::class, 'register']);
Route::post('/login',      [ApiController::class, 'login']);
Route::post('/input-data', [ApiController::class, 'inputData']);
Route::post('/chat',       [ApiController::class, 'chat']);

// Prediksi obesitas (public)
Route::post('/predict-obesity', [ApiController::class, 'predict']);



// Endpoint Test Koneksi
Route::get('/test', function () {
    return response()->json([
        'status' => 'success'
    ]);
});

// Artikel
Route::get('/articles', function () {
    return response()->json(
        Article::where('status', 'published')->get()
    );
});

// Serve image route (bypass symlink issue on artisan serve)
Route::get('/image/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

// =======================
// PROTECTED ROUTES
// =======================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    [ApiController::class, 'user']);
    Route::post('/logout', [ApiController::class, 'logout']);

    // Simpan & history prediksi obesitas
    Route::post('/obesity/save',    [ApiController::class, 'save']);
    Route::get('/obesity/history',  [ApiController::class, 'history']);

    Route::post('/change-password', [ApiController::class, 'changePassword']);
});
