<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\UkuranController;
use App\Http\Controllers\WarnaController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Storage;

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

//Route Pemanggilan File Storage
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $allowedFolders = ['foto_produk', 'icons', 'banner', 'page'];

    if (!in_array($folder, $allowedFolders)) {
        abort(403, 'Folder tidak diizinkan');
    }

    $path = storage_path("app/public/{$folder}/{$filename}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('storage');

//Route Landing Page
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('/collection', [HomeController::class, 'collection'])->name('collection');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/detail/{id}', [HomeController::class, 'show_produk'])->name('detail.show');
Route::get('/invoice', [HomeController::class, 'invoice'])->name('invoice');
Route::post('/invoice', [HomeController::class, 'cekInvoice'])->name('invoice.cek');
Route::get('/transaksi/{kode_invoice}', [HomeController::class, 'transaksi'])->name('transaksi.show');


// Route Checkout
Route::post('/cart/add', [HomeController::class, 'add_cart'])->name('cart.add');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart.index');
Route::post('/cart/update', [HomeController::class, 'update_cart'])->name('cart.update');
Route::post('/cart/remove', [HomeController::class, 'remove_cart'])->name('cart.remove');
Route::get('/checkout', [HomeController::class, 'checkoutForm'])->name('checkout.form');
Route::post('/checkout/save', [HomeController::class, 'saveCheckout'])->name('checkout.save');
Route::get('/checkout/payment', [HomeController::class, 'paymentForm'])->name('checkout.payment');
Route::post('/checkout/process', [HomeController::class, 'processPayment'])->name('checkout.process');
Route::get('/checkout/success/{detail_id}', [HomeController::class, 'paymentSuccess'])->name('checkout.success');


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
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/filter', [ProdukController::class, 'filter'])->name('produk.filter');
        Route::get('/{id}/show', [ProdukController::class, 'show'])->name('produk.show');
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/store', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/{id}/update', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/{id}/destroy', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });

    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/show/{id}', [KategoriController::class, 'show'])->name('kategori.show');
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/destroy/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    Route::prefix('bahan')->group(function () {
        Route::get('/', [BahanController::class, 'index'])->name('bahan.index');
        Route::get('/filter', [BahanController::class, 'filter'])->name('bahan.filter');
        Route::get('/{id}/show', [BahanController::class, 'show'])->name('bahan.show');
        Route::get('/create', [BahanController::class, 'create'])->name('bahan.create');
        Route::post('/store', [BahanController::class, 'store'])->name('bahan.store');
        Route::get('/{id}/edit', [BahanController::class, 'edit'])->name('bahan.edit');
        Route::put('/{id}/update', [BahanController::class, 'update'])->name('bahan.update');
        Route::delete('/{id}/destroy', [BahanController::class, 'destroy'])->name('bahan.destroy');
    });

    Route::prefix('ukuran')->group(function () {
        Route::get('/', [UkuranController::class, 'index'])->name('ukuran.index');
        Route::get('/filter', [UkuranController::class, 'filter'])->name('ukuran.filter');
        Route::get('/{id}/show', [UkuranController::class, 'show'])->name('ukuran.show');
        Route::get('/create', [UkuranController::class, 'create'])->name('ukuran.create');
        Route::post('/store', [UkuranController::class, 'store'])->name('ukuran.store');
        Route::get('/{id}/edit', [UkuranController::class, 'edit'])->name('ukuran.edit');
        Route::put('/{id}/update', [UkuranController::class, 'update'])->name('ukuran.update');
        Route::delete('/{id}/destroy', [UkuranController::class, 'destroy'])->name('ukuran.destroy');
    });

    Route::prefix('warna')->group(function () {
        Route::get('/', [WarnaController::class, 'index'])->name('warna.index');
        Route::get('/filter', [WarnaController::class, 'filter'])->name('warna.filter');
        Route::get('/{id}/show', [WarnaController::class, 'show'])->name('warna.show');
        Route::get('/create', [WarnaController::class, 'create'])->name('warna.create');
        Route::post('/store', [WarnaController::class, 'store'])->name('warna.store');
        Route::get('/{id}/edit', [WarnaController::class, 'edit'])->name('warna.edit');
        Route::put('/{id}/update', [WarnaController::class, 'update'])->name('warna.update');
        Route::delete('/{id}/destroy', [WarnaController::class, 'destroy'])->name('warna.destroy');
    });

    Route::prefix('metode_pembayaran')->group(function () {
        Route::get('/', [MetodePembayaranController::class, 'index'])->name('metode_pembayaran.index');
        Route::get('/{id}/show', [MetodePembayaranController::class, 'show'])->name('metode_pembayaran.show');
        Route::get('/create', [MetodePembayaranController::class, 'create'])->name('metode_pembayaran.create');
        Route::post('/store', [MetodePembayaranController::class, 'store'])->name('metode_pembayaran.store');
        Route::get('/{id}/edit', [MetodePembayaranController::class, 'edit'])->name('metode_pembayaran.edit');
        Route::put('/{id}/update', [MetodePembayaranController::class, 'update'])->name('metode_pembayaran.update');
        Route::delete('/{id}/destroy', [MetodePembayaranController::class, 'destroy'])->name('metode_pembayaran.destroy');
    });

    Route::prefix('pesanan')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/show/{id}', [PesananController::class, 'show'])
            ->whereUuid('id')->name('pesanan.show');
        Route::put('/update/pembayaran/{id}', [PesananController::class, 'updatePembayaran'])
            ->whereUuid('id')->name('update.pembayaran');
        Route::put('/update/transaksi/{id}', [PesananController::class, 'updateTransaksi'])
            ->whereUuid('id')->name('update.transaksi');
    });

    Route::prefix('banner')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('banner.index');
        Route::get('/show/{id}', [BannerController::class, 'show'])->name('banner.show');
        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
        Route::put('/update{id}', [BannerController::class, 'update'])->name('banner.update');
        Route::delete('/destroy{id}', [BannerController::class, 'destroy'])->name('banner.destroy');
    });
});