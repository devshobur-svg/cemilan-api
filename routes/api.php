<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Endpoint Manajemen Produk & Stok
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // BARU: Untuk Fitur Delete

// Endpoint Riwayat Transaksi Kasir
Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);