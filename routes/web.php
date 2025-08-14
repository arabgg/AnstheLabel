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
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin', [AdminController::class, 'index']);

    Route::prefix('produk')->group(function () {
        // Filter
        Route::get('/filter', [ProdukController::class, 'filter'])->name('produk.filter');

        // List
        Route::get('/', [ProdukController::class, 'index']); 

        // Show
        Route::get('/{id}/show', [ProdukController::class, 'show']);

        // Create
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create'); 
        Route::post('/store', [ProdukController::class, 'store'])->name('produk.store'); 

        // Edit
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit'); 
        Route::put('/{id}/update', [ProdukController::class, 'update'])->name('produk.update');

        // Delete
        Route::get('/{id}/delete', [ProdukController::class, 'destroy'])->name('produk.destroy');   
        Route::delete('/{id}/delete', [ProdukController::class, 'delete'])->name('produk.delete'); 
    });
});
