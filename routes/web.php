<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TingkatanObesitasController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\UserController;
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

    // DATA USER
    Route::get('/data_user', [UserController::class, 'index'])->name('users.index');
    Route::delete('/data_user/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // MANAJEMEN DATA ✅ (tidak duplikat, pakai controller untuk kirim $data & $totalData)
    Route::get('/manajemen-data', [DashboardController::class, 'manajemenData'])->name('manajemen.data');
    Route::post('/manajemen-data/upload', [DashboardController::class, 'uploadData'])->name('admin.upload-data');
    Route::delete('/manajemen-data/delete-all', [DashboardController::class, 'deleteAllData'])->name('admin.delete-all-data');

    // ARTICLES
    Route::resource('articles', ArticleController::class);

    // KATEGORI
    // Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

});

Route::middleware(['auth'])->group(function () {
    Route::resource('kategori', TingkatanObesitasController::class);
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

// Tambahkan sementara di web.php (hapus setelah debug selesai)
Route::get('/debug-excel', function () {
    $path = storage_path('app/test.xlsx');

    // Pastikan Anda taruh file xlsx di storage/app/test.xlsx dulu
    if (!file_exists($path)) {
        return "File tidak ditemukan di storage/app/test.xlsx";
    }

    $rows = \Maatwebsite\Excel\Facades\Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
        public function array(array $array) { return $array; }
    }, $path);

    // Tampilkan baris pertama (header) dan baris kedua (data pertama)
    return response()->json([
        'header_row' => $rows[0][0] ?? 'tidak ada',
        'first_data' => $rows[0][1] ?? 'tidak ada',
    ]);
});


use App\Http\Controllers\Admin\ForgotPasswordController;

Route::get('/admin/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('admin.forgot-password.form');

Route::post('/admin/forgot-password/verify', [ForgotPasswordController::class, 'verifyEmail'])
    ->name('admin.forgot-password.verify');

Route::post('/admin/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])
    ->name('admin.forgot-password.reset');
