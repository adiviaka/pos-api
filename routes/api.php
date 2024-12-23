<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;

// Public Routes
Route::post('register', [AuthController::class, 'register']); // User registration
Route::post('login', [AuthController::class, 'login']); // User login

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/protected-route', function () {
        return response()->json(['message' => 'You are authorized!']);
    });
});

// Protected Routes (requires JWT authentication)
Route::middleware('jwt.auth')->group(function () {
    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']); // Get list of products
        Route::post('/', [ProductController::class, 'store']); // Create a product
        Route::get('{product}', [ProductController::class, 'show']); // Get a single product by ID
        Route::put('{product}', [ProductController::class, 'update']); // Update product by ID
        Route::delete('{product}', [ProductController::class, 'destroy']); // Delete product by ID
        Route::get('search', [ProductController::class, 'search']); // Search products by name
        Route::get('filter', [ProductController::class, 'filter']); // Filter products by category
    });

    // Transactions
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']); // Get list of transactions
        Route::post('/', [TransactionController::class, 'store']); // Create a transaction
        Route::get('{transaction}', [TransactionController::class, 'show']); // Get a single transaction by ID
        Route::put('{transaction}', [TransactionController::class, 'update']); // Update transaction by ID
        Route::delete('{transaction}', [TransactionController::class, 'destroy']); // Delete transaction by ID
    });

    // Transaction Details
    Route::prefix('transaction-details')->group(function () {
        Route::get('/', [TransactionDetailController::class, 'index']); // Get list of transaction details
        Route::post('/', [TransactionDetailController::class, 'store']); // Create a transaction detail
        Route::get('{transactionDetail}', [TransactionDetailController::class, 'show']); // Get a single transaction detail by ID
        Route::put('{transactionDetail}', [TransactionDetailController::class, 'update']); // Update transaction detail by ID
        Route::delete('{transactionDetail}', [TransactionDetailController::class, 'destroy']); // Delete transaction detail by ID
    });
});
