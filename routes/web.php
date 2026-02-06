<?php

use App\Http\Middleware\isLogin;
use App\Http\Controllers\Web\AuthWeb;
use App\Http\Controllers\Web\ShopWeb;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StaffWeb;
use App\Http\Controllers\Web\Dashboard;
use App\Http\Controllers\Web\ProductWeb;
use App\Http\Controllers\Web\CategoryWeb;
use App\Http\Controllers\Web\EcommerceController;
use App\Http\Controllers\Web\MerchantWeb;
use App\Http\Controllers\Web\ProductHistoryWeb;
use App\Http\Controllers\Web\ProfileWeb;
use App\Http\Controllers\Web\ReportSales;
use App\Http\Controllers\Web\ReportStock;
use App\Http\Controllers\Web\TransactionWeb;
use App\Http\Controllers\Web\UserManagementWeb;
use Illuminate\Support\Facades\Cache;

Route::get('/', [EcommerceController::class, 'index']);
Route::get('getCategoryByMerchant',[EcommerceController::class, 'getCategory'])->name('getCategoryByMerchant');
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

// delete all cache
Route::get('/clear-cache', function () {
    Cache::flush();
    return 'Cache cleared';
});

Route::middleware(isLogin::class)->group(
    function () {
        Route::get('/dashboard', [Dashboard::class, 'dashboard']);

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::post('/', [ProfileWeb::class, 'update']);
            Route::get('/pageChangePic', [ProfileWeb::class, 'pageChangePic']);
            Route::post('/changeImg', [ProfileWeb::class, 'changeImg'])->name('changeImg');
        });
        Route::resource('profile', ProfileWeb::class)->except(['update', 'store']);

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
            Route::get('{id}/detail', [ProductWeb::class, 'detail'])->name('detail');
            Route::post('/uploadImage', [ProductWeb::class, 'uploadImage'])
                ->name('uploadImage');
            Route::get('/{id}/restock', [ProductWeb::class, 'restock']);
            Route::post('/restock', [ProductWeb::class, 'restockProcess']);
        });
        Route::resource('product', ProductWeb::class);

        // staff
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::post('/{id}/editRole', [StaffWeb::class, 'editBranch']);
            Route::get('/{id}/add', [StaffWeb::class, 'add']);
            Route::post('/{id}/add', [StaffWeb::class, 'store']);
        });
        Route::resource('staff', StaffWeb::class);

        // transaction
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/detail', [TransactionWeb::class, 'detail']);
        });
        Route::resource('transaction', TransactionWeb::class);

        // report
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/sales', [ReportSales::class, 'index']);
            Route::get('/sales/data', [ReportSales::class, 'show']);
            Route::get('{id}/{date}/detail', [ReportSales::class, 'detail'])->name('sales.detail');
            Route::get('{id}/{date}/chart', [ReportSales::class, 'chart'])->name('sales.chart');

            Route::get('/stock', [ReportStock::class, 'index']);
            Route::get('/stock/data', [ReportStock::class, 'show']);
        });

        // product history
        Route::post('/checkStock', [ProductHistoryWeb::class, 'checkStock']);

        // User management
        Route::prefix('userman')->name('userman.')->group(function () {
            Route::get('/{id}/edit', [UserManagementWeb::class, 'edit']);
        });
        Route::resource('userman', UserManagementWeb::class);
    }
);
Route::post('/product-history/check-stock', [ProductHistoryWeb::class, 'checkStock'])
    ->name('product-history.checkStock');
