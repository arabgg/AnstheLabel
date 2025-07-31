<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    return redirect()->route('home');
});

//Route Landing Page
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('/collection', [HomeController::class, 'collection'])->name('collection');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/detail/{id}', [HomeController::class, 'show_produk'])->name('detail.show');


//Route Login
// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin']);
});

// Authenticated-only routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin', [AdminController::class, 'index']);

    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index']);
        Route::post('/list', [ProdukController::class, 'list']);
        Route::get('/create_ajax', [ProdukController::class, 'create_ajax']);
        Route::post('/ajax', [ProdukController::class, 'store_ajax']);
    });
});