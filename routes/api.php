<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\TransactionController;

Route::prefix('v1')->group(function () {

    // ============================================
    // PRODUCTS API
    // ============================================
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/filters', [ProductController::class, 'filters']);
    Route::get('/products/best', [ProductController::class, 'best']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // ============================================
    // CHECKOUT API
    // ============================================
    Route::get('/checkout-dependencies', [CheckoutController::class, 'dependencies']);
    Route::post('/checkout/validate-voucher', [CheckoutController::class, 'validateVoucher']);
    Route::post('/checkout/calculate', [CheckoutController::class, 'calculate']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // ============================================
    // TRANSACTIONS API
    // ============================================
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{kode_invoice}', [TransactionController::class, 'show']);
    Route::post('/transactions/{kode_invoice}/upload-payment', [TransactionController::class, 'uploadPayment']);
    Route::post('/transactions/{kode_invoice}/cancel', [TransactionController::class, 'cancel']);

});