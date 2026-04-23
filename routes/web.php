<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TingkatanObesitasController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Models\UserMongo;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'loginPost'])->name('admin.login.post');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/admin/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED (LOGIN + ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN DATA
    Route::get('/manajemen-data', function () {
        return view('auth.admin.manajemen_data');
    })->name('manajemen.data');

    // ARTICLES ✅
    Route::resource('articles', ArticleController::class);

    // KATEGORI
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

});



/*
|--------------------------------------------------------------------------
| TEST MONGODB
|--------------------------------------------------------------------------
*/
Route::get('/cobamongo', function () {

    UserMongo::create([
        'name' => 'Iqbal',
        'email' => 'iqbal@test.com',
        'password' => bcrypt('123456'),
        'role' => 'user'
    ]);

    return "Data berhasil masuk MongoDB!";
});
