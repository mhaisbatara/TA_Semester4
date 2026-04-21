<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TingkatanObesitasController;
use App\Http\Controllers\KategoriController;
use App\Models\UserMongo;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =============================
// PUBLIC
// =============================
Route::get('/', function () {
    return view('welcome');
});

// =============================
// AUTH
// =============================

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'loginPost'])->name('admin.login.post');

});

// Logout (FIX)
Route::post('/logout', function () {
    Auth::logout(); // logout user
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/admin/login');
})->name('logout');


// =============================
// PROTECTED (HARUS LOGIN)
// =============================
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD OBESITAS
    Route::prefix('dashboard/obesitas')->group(function () {

        Route::get('/', [TingkatanObesitasController::class, 'index'])->name('obesitas.index');
        Route::get('/create', [TingkatanObesitasController::class, 'create'])->name('obesitas.create');
        Route::post('/store', [TingkatanObesitasController::class, 'store'])->name('obesitas.store');

        Route::get('/edit/{id}', [TingkatanObesitasController::class, 'edit'])->name('obesitas.edit');
        Route::put('/update/{id}', [TingkatanObesitasController::class, 'update'])->name('obesitas.update');

        Route::delete('/delete/{id}', [TingkatanObesitasController::class, 'destroy'])->name('obesitas.delete');

        // KATEGORI
        Route::get('/dashboard/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/dashboard/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/dashboard/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');

    });

});

Route::get('/tes-dashboard', function () {
    return view('auth.admin.dashboard');
});

// =============================
// TEST MONGODB
// =============================
Route::get('/cobamongo', function () {

    UserMongo::create([
        'name' => 'Iqbal',
        'email' => 'iqbal@test.com',
        'password' => bcrypt('123456'),
        'role' => 'user'
    ]);

    return "Data berhasil masuk MongoDB!";
});

Route::get('/dashboard/kategori', [KategoriController::class, 'index']);
Route::post('/dashboard/kategori', [KategoriController::class, 'store']);
Route::get('/dashboard/kategori/edit/{id}', [KategoriController::class, 'edit']);

Route::get('/dashboard/manajemen-data', function () {
    return view('auth.admin.manajemen_data');
    })->name('manajemen.data');
