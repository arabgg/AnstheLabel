<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\UkuranController;
use App\Http\Controllers\WarnaController;
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
Route::post('/cart/add', [HomeController::class, 'add_cart'])->name('cart.add');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart.index');
Route::post('/cart/update', [HomeController::class, 'update_cart'])->name('cart.update');
Route::post('/cart/remove', [HomeController::class, 'remove_cart'])->name('cart.remove');
Route::get('/checkout', [HomeController::class, 'checkoutForm'])->name('checkout.form');
Route::post('/checkout/save', [HomeController::class, 'saveCheckout'])->name('checkout.save');
Route::get('/checkout/payment', [HomeController::class, 'paymentForm'])->name('checkout.payment');
Route::post('/checkout/process', [HomeController::class, 'processPayment'])->name('checkout.process');


//Route Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin']);
});

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'changePasswordForm'])
        ->name('auth.change-password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])
        ->name('auth.change-password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin', [AdminController::class, 'index']);

    Route::prefix('produk')->group(function () {
        // Filter
        Route::get('/filter', [ProdukController::class, 'filter'])->name('produk.filter');

        // List
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');

        // Show
        Route::get('/{id}/show', [ProdukController::class, 'show']);

        // Create
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/store', [ProdukController::class, 'store'])->name('produk.store');

        // Edit
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/{id}/update', [ProdukController::class, 'update'])->name('produk.update');

        // Delete
        Route::delete('/{id}/destroy', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });

    Route::prefix('kategori')->group(function () {
        // Filter
        Route::get('/filter', [KategoriController::class, 'filter'])->name('kategori.filter');

        // List
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');

        // Show
        Route::get('/{id}/show', [KategoriController::class, 'show'])->name('kategori.show');

        // Create
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');

        // Edit
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/{id}/update', [KategoriController::class, 'update'])->name('kategori.update');

        // Delete
        Route::delete('/{id}/destroy', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    Route::prefix('bahan')->group(function () {
        // Filter
        Route::get('/filter', [BahanController::class, 'filter'])->name('bahan.filter');

        // List
        Route::get('/', [BahanController::class, 'index'])->name('bahan.index');

        // Show
        Route::get('/{id}/show', [BahanController::class, 'show'])->name('bahan.show');

        // Create
        Route::get('/create', [BahanController::class, 'create'])->name('bahan.create');
        Route::post('/store', [BahanController::class, 'store'])->name('bahan.store');

        // Edit
        Route::get('/{id}/edit', [BahanController::class, 'edit'])->name('bahan.edit');
        Route::put('/{id}/update', [BahanController::class, 'update'])->name('bahan.update');

        // Delete
        Route::delete('/{id}/destroy', [BahanController::class, 'destroy'])->name('bahan.destroy');
    });

    Route::prefix('ukuran')->group(function () {
        // Filter
        Route::get('/filter', [UkuranController::class, 'filter'])->name('ukuran.filter');

        // List
        Route::get('/', [UkuranController::class, 'index'])->name('ukuran.index');

        // Show
        Route::get('/{id}/show', [UkuranController::class, 'show'])->name('ukuran.show');

        // Create
        Route::get('/create', [UkuranController::class, 'create'])->name('ukuran.create');
        Route::post('/store', [UkuranController::class, 'store'])->name('ukuran.store');

        // Edit
        Route::get('/{id}/edit', [UkuranController::class, 'edit'])->name('ukuran.edit');
        Route::put('/{id}/update', [UkuranController::class, 'update'])->name('ukuran.update');

        // Delete
        Route::delete('/{id}/destroy', [UkuranController::class, 'destroy'])->name('ukuran.destroy');
    });

    Route::prefix('warna')->group(function () {
        // Filter
        Route::get('/filter', [WarnaController::class, 'filter'])->name('warna.filter');

        // List
        Route::get('/', [WarnaController::class, 'index'])->name('warna.index');

        // Show
        Route::get('/{id}/show', [WarnaController::class, 'show'])->name('warna.show');

        // Create
        Route::get('/create', [WarnaController::class, 'create'])->name('warna.create');
        Route::post('/store', [WarnaController::class, 'store'])->name('warna.store');

        // Edit
        Route::get('/{id}/edit', [WarnaController::class, 'edit'])->name('warna.edit');
        Route::put('/{id}/update', [WarnaController::class, 'update'])->name('warna.update');

        // Delete
        Route::delete('/{id}/destroy', [WarnaController::class, 'destroy'])->name('warna.destroy');
    });
});
