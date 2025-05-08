<?php

use App\Http\Middleware\isLogin;
use App\Http\Controllers\Web\AuthWeb;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Dashboard;
use App\Http\Controllers\Web\CategoryWeb;
use App\Http\Controllers\Web\MerchantWeb;
use App\Http\Controllers\Web\ProductWeb;

Route::get('/login', [AuthWeb::class, 'loginPage']);
Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/forgot', function () {
    return view('auth.forgot');
});
Route::post('/authenticate', [AuthWeb::class, 'login']);
Route::get('/logout', [AuthWeb::class, 'logout']);

Route::middleware(isLogin::class)->group(
    function () {
        Route::get('/', [Dashboard::class, 'dashboard']);
        Route::get('/dashboard', [Dashboard::class, 'dashboard']);

        // category
        Route::get('/category/add', [CategoryWeb::class, 'add']);
        Route::get('/category/{id}/edit', [CategoryWeb::class, 'edit']);
        Route::post('/category/{id}', [CategoryWeb::class, 'update']);
        Route::resource('category', CategoryWeb::class);

        // merchant
        Route::prefix('merchant')->name('merchant.')->group(function () {
            Route::get('{id}/detail', [MerchantWeb::class, 'detail'])->name('detail');
            Route::get('/add', [MerchantWeb::class, 'add']);
            Route::get('/{id}/edit', [MerchantWeb::class, 'edit']);
            Route::post('/{id}', [MerchantWeb::class, 'update']);
        });
        Route::resource('merchant', MerchantWeb::class);


        // product
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/add', [ProductWeb::class, 'add']);
        });
        Route::resource('product', ProductWeb::class);

    }
);
