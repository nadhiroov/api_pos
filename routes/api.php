<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/shop', [ShopController::class, 'search'])->middleware('auth:sanctum', 'ability:product-list');

Route::apiResource('transaction', TransactionController::class)->middleware('auth:sanctum');
Route::post('/branches/{branch}/transactions', [TransactionController::class, 'addTransaction'])->middleware('auth:sanctum');
Route::get('/transactions/filter', [TransactionController::class, 'getTransactionsByYearAndBranch'])->middleware('auth:sanctum');
Route::get('/products', [ProductController::class, 'index'])->middleware('auth:sanctum');
