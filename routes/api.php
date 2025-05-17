<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/shop', [ShopController::class, 'search'])->middleware('auth:sanctum', 'ability:product-list');

Route::apiResource('transaction', TransactionController::class)->middleware('auth:sanctum');
Route::post('/branches/{branch}/transactions', [TransactionController::class, 'addTransaction'])->middleware('auth:sanctum');
Route::get('/transactions/filter', [TransactionController::class, 'getTransactionsByYearAndBranch'])->middleware('auth:sanctum');
Route::get('/products', [ProductController::class, 'index'])->middleware('auth:sanctum');
Route::apiResource('/category', TransactionController::class)->middleware('auth:sanctum');