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
        // List
        Route::get('/', [ProdukController::class, 'index']); // Halaman utama
        Route::post('/list', [ProdukController::class, 'list']); // List data produk

        // Show
        Route::get('/{id}/show', [ProdukController::class, 'show']); // Detail produk

        // Create
        Route::get('/create', [ProdukController::class, 'create']); // Tampilkan form
        Route::post('/upload', [ProdukController::class, 'upload']); // Simpan data baru

        // Edit
        Route::get('/{id}/edit', [ProdukController::class, 'edit']); // Tampilkan form edit
        Route::put('/{id}/update', [ProdukController::class, 'update']); // Update data

        // Delete
        Route::get('/{id}/delete', [ProdukController::class, 'confirm']); // Konfirmasi hapus
        Route::delete('/{id}/delete', [ProdukController::class, 'delete']); // Hapus data
    });
});
