<?php

use App\Http\Middleware\isLogin;
use App\Http\Controllers\Web\AuthWeb;
use App\Http\Controllers\Web\ShopWeb;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StaffWeb;
use App\Http\Controllers\Web\Dashboard;
use App\Http\Controllers\Web\ProductWeb;
use App\Http\Controllers\Web\CategoryWeb;
use App\Http\Controllers\Web\MerchantWeb;
use App\Http\Middleware\CheckRole;

// auth
Route::get('/login', [AuthWeb::class, 'loginPage']);
Route::get('/register', function () {
    return view('auth.register');
});
Route::post('/register', [AuthWeb::class, 'register']);
Route::get('/forgot', function () {
    return view('auth.forgot');
});
Route::post('/authenticate', [AuthWeb::class, 'login']);
Route::get('/logout', [AuthWeb::class, 'logout']);

// show image
Route::get('/product/image/{filename}', [ProductWeb::class, 'showImage'])
    ->name('product.image');

Route::middleware(isLogin::class)->group(
    function () {
        Route::get('/', [Dashboard::class, 'dashboard']);
        Route::get('/dashboard', [Dashboard::class, 'dashboard']);

        // shop
        Route::prefix('shop')->name('shop.')->group(function () {
            Route::post('/uploadImage', [ShopWeb::class, 'uploadImage'])->name('uploadImage');
            Route::post('/join', [ShopWeb::class, 'join'])->name('join');
            Route::get('/image/{filename}', [ShopWeb::class, 'showImage'])->name('image');
        });

        Route::resource('shop', ShopWeb::class);

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
            Route::get('/{branch}/staffs', [MerchantWeb::class, 'showStaff']);
        });
        Route::resource('merchant', MerchantWeb::class);

        // product
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/add', [ProductWeb::class, 'add'])->name('addNew');
            Route::get('{id}/add', [ProductWeb::class, 'add'])->name('add');
            Route::post('/uploadImage', [ProductWeb::class, 'uploadImage'])
                ->name('uploadImage');
            // Route::get('/image/{filename}', [ProductWeb::class, 'showImage'])
            //     ->name('image');
        });
        Route::resource('product', ProductWeb::class);

        // staff
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::post('/{id}/editRole', [StaffWeb::class, 'editBranch']);
            Route::get('/{id}/add', [StaffWeb::class, 'add']);
            Route::post('/{id}/add', [StaffWeb::class, 'store']);
        });
        Route::resource('staff', StaffWeb::class);
    }
);
