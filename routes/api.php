<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::middleware(['jwt.auth'])->group(function () {
//     Route::get('/protected-route', function () {
//         return response()->json(['message' => 'You are authorized!']);
//     });
// });

Route::middleware('jwt.auth')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('search', [ProductController::class, 'search']);
        Route::get('filter', [ProductController::class, 'filterByCategory']);
        Route::get('{product}', [ProductController::class, 'show']);
        Route::put('{product}', [ProductController::class, 'update']);
        Route::delete('{product}', [ProductController::class, 'destroy']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('{transaction}', [TransactionController::class, 'show']);
    });

    Route::prefix('transaction-details')->group(function () {
        Route::post('/', [TransactionDetailController::class, 'store']);
    });
});
