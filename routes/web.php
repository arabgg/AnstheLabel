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