<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\TransactionController;
Route::prefix('v1')->group(function () {
    // Product
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    // Checkout
    Route::get('/checkout-dependencies', [CheckoutController::class, 'dependencies']);
    Route::post('/checkout', [CheckoutController::class, 'store']);
    // Transaction
    Route::get('/transactions/{kode_invoice}', [TransactionController::class, 'show']);
    Route::post('/transactions/{kode_invoice}/upload-payment', [TransactionController::class, 'uploadPayment']);
});